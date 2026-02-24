<?php
require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_login();

admin_externalpage_setup('local_customreg_manage');

$context = context_system::instance();

$action = optional_param('action', '', PARAM_ALPHANUMEXT);
$userid = optional_param('userid', 0, PARAM_INT);
$search = optional_param('search', '', PARAM_TEXT);
$page   = optional_param('page', 0, PARAM_INT);
$perpage = 20;

// Handle Log Retrieval (AJAX-like response)
if ($action === 'getlogs' && $userid > 0) {
    header('Content-Type: application/json');
    $logs = $DB->get_records_sql("
        SELECT l.*, u.firstname, u.lastname 
        FROM {local_customreg_logs} l 
        LEFT JOIN {user} u ON l.adminid = u.id 
        WHERE l.userid = ? 
        ORDER BY l.timecreated DESC", [$userid]);
    
    $output = [];
    foreach ($logs as $log) {
        $admin = ($log->adminid == 0) ? 'System/User' : fullname($log);
        $output[] = [
            'date' => userdate($log->timecreated, get_string('strftimedatetimeshort', 'langconfig')),
            'action' => ucfirst($log->action),
            'admin' => $admin,
            'details' => $log->details
        ];
    }
    echo json_encode($output);
    exit;
}

// Handle Approval Action
if ($action === 'approve' && $userid > 0 && confirm_sesskey()) {
    $DB->set_field('local_customreg', 'status', 'approved', ['userid' => $userid]);
    $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $userid]);
    
    // Log approval
    require_once($CFG->dirroot . '/local/customreg/lib.php');
    local_customreg_log($userid, 'approved', 'Registration request approved.');
    
    redirect($PAGE->url, get_string('userapproved', 'local_customreg'), 2);
}

// Handle Deny Action
if ($action === 'deny' && $userid > 0 && confirm_sesskey()) {
    $DB->set_field('local_customreg', 'status', 'denied', ['userid' => $userid]);
    $DB->set_field('local_customreg', 'documentuploaded', 0, ['userid' => $userid]);
    $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $userid]);
    
    // Log denial
    require_once($CFG->dirroot . '/local/customreg/lib.php');
    local_customreg_log($userid, 'denied', 'Registration request denied.');
    
    // Optional: Delete existing files to save space and avoid confusion
    $fs = get_file_storage();
    $fs->delete_area_files($context->id, 'local_customreg', 'govid', $userid);
    
    redirect($PAGE->url, get_string('userdenied', 'local_customreg'), 2);
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('manageusers', 'local_customreg'));

// Search Bar
echo '<div class="mb-4 d-flex justify-content-end">';
echo $OUTPUT->render_from_template('core/search_input', [
    'action' => $PAGE->url->out(false),
    'name' => 'search',
    'value' => $search,
    'placeholder' => get_string('searchusers', 'local_customreg'),
    'viewid' => 'search-users-input'
]);
echo '</div>';

// Build SQL
$params = [];
$where = "1=1";
if ($search) {
    $where .= " AND (u.firstname LIKE :s1 OR u.lastname LIKE :s2 OR u.email LIKE :s3)";
    $params['s1'] = '%'.$search.'%';
    $params['s2'] = '%'.$search.'%';
    $params['s3'] = '%'.$search.'%';
}

$sql = "SELECT cr.*, u.firstname, u.lastname, u.email 
          FROM {local_customreg} cr
          JOIN {user} u ON cr.userid = u.id
         WHERE $where
      ORDER BY cr.timecreated DESC";

$totalcount = $DB->count_records_sql("SELECT COUNT(*) FROM {local_customreg} cr JOIN {user} u ON cr.userid = u.id WHERE $where", $params);
$records = $DB->get_records_sql($sql, $params, $page * $perpage, $perpage);

if (!$records) {
    echo $OUTPUT->notification('No records found.', 'info');
} else {
    $table = new html_table();
    $table->head = [
        'User',
        'Institution ID',
        get_string('documentstatus', 'local_customreg'),
        get_string('action', 'local_customreg')
    ];

    foreach ($records as $rec) {
        $userlink = html_writer::link(new moodle_url('/user/profile.php', ['id' => $rec->userid]), fullname($rec));
        
        $statusstr = get_string($rec->status, 'local_customreg');
        $statuscolor = 'badge-warning';
        if ($rec->status === 'approved') {
            $statuscolor = 'badge-success';
        } else if ($rec->status === 'denied') {
            $statuscolor = 'badge-danger';
        }
        $statusbadge = html_writer::tag('span', $statusstr, ['class' => 'badge ' . $statuscolor]);

        $actions = [];

        // File link with Popup Eye Icon - Moved to Actions
        if ($rec->documentuploaded) {
            $fs = get_file_storage();
            $files = $fs->get_area_files($context->id, 'local_customreg', 'govid', $rec->userid, 'id DESC', false);
            if ($files) {
                $file = reset($files);
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
                
                // Consolidation: Preview in a Modal instead of a new tab/window
                $actions[] = html_writer::link('#', $OUTPUT->pix_icon('t/preview', 'View ID'), [
                    'class' => 'view-id-trigger',
                    'data-url' => $url->out(false),
                    'title' => 'View ID'
                ]);
            }
        }

        if ($rec->status !== 'approved') {
            $approveurl = new moodle_url($PAGE->url, ['action' => 'approve', 'userid' => $rec->userid, 'sesskey' => sesskey()]);
            $actions[] = $OUTPUT->action_icon($approveurl, new pix_icon('t/check', get_string('approve', 'local_customreg')));
            
            $denyurl = new moodle_url($PAGE->url, ['action' => 'deny', 'userid' => $rec->userid, 'sesskey' => sesskey()]);
            $actions[] = $OUTPUT->action_icon($denyurl, new pix_icon('i/invalid', get_string('deny', 'local_customreg')));
        }
        
        // Addition: Log History Icon
        $actions[] = html_writer::link('#', $OUTPUT->pix_icon('i/menu', 'View Timeline'), [
            'class' => 'view-log-trigger',
            'data-userid' => $rec->userid,
            'title' => 'Registration Timeline'
        ]);

        $table->data[] = [
            $userlink . '<br><small>' . s($rec->email) . '</small>',
            s($rec->institutionid),
            $statusbadge,
            implode(' ', $actions)
        ];
    }

    echo html_writer::table($table);

    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $PAGE->url);
}

// JavaScript to handle the click and update the modal using Moodle standard AMD
$PAGE->requires->js_amd_inline("
require(['jquery', 'core/modal_factory', 'core/modal_events'], function($, ModalFactory, ModalEvents) {
    var zoomLevel = 1;
    var timelineModal = null;
    var previewModal = null;

    // --- Timeline Log Logic ---
    ModalFactory.create({
        title: 'Registration Timeline',
        type: ModalFactory.types.DEFAULT,
    }).then(function(modal) {
        timelineModal = modal;
        $('.view-log-trigger').on('click', function(e) {
            e.preventDefault();
            var userid = $(this).attr('data-userid');
            timelineModal.setBody('<div class=\"text-center\"><i class=\"fa fa-spinner fa-spin\"></i> Loading...</div>');
            timelineModal.show();

            $.get('manage.php', {action: 'getlogs', userid: userid}, function(data) {
                if (data.length === 0) {
                    timelineModal.setBody('<div class=\"alert alert-info\">No logs found for this user.</div>');
                    return;
                }
                var html = '<ul class=\"list-group list-group-flush\">';
                $.each(data, function(i, log) {
                    var badgeClass = 'secondary';
                    if (log.action.toLowerCase() === 'approved') badgeClass = 'success';
                    if (log.action.toLowerCase() === 'denied') badgeClass = 'danger';
                    if (log.action.toLowerCase() === 'raised') badgeClass = 'primary';
                    if (log.action.toLowerCase() === 'uploaded') badgeClass = 'info';

                    html += '<li class=\"list-group-item px-1 border-bottom\">' +
                            '<div class=\"d-flex justify-content-between align-items-center mb-1\">' +
                            '<strong><span class=\"badge badge-' + badgeClass + ' bg-' + badgeClass + '\">' + log.action + '</span></strong>' +
                            '<small class=\"text-muted text-right\">' + log.date + '</small>' +
                            '</div>' +
                            '<div class=\"small\">' + log.details + '</div>' +
                            '<div class=\"small text-muted\"><em>By: ' + log.admin + '</em></div>' +
                            '</li>';
                });
                html += '</ul>';
                timelineModal.setBody(html);
            });
        });
    });

    // --- Identity Preview Modal Logic ---
    ModalFactory.create({
        title: 'Identity Document Preview',
        type: ModalFactory.types.LARGE,
    }).then(function(modal) {
        previewModal = modal;
        
        $('.view-id-trigger').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('data-url');
            var isImage = url.match(/\.(jpg|jpeg|png|gif|webp)/i);
            zoomLevel = 1;

            var body = $('<div style=\"height: 70vh; display: flex; flex-direction: column;\"></div>');
            
            // Add Controls if image
            if (isImage) {
                var controls = $('<div class=\"mb-2 text-center\">' +
                    '<button type=\"button\" class=\"btn btn-outline-secondary btn-sm mr-1\" id=\"modalZoomOut\"><i class=\"fa fa-search-minus\"></i></button>' +
                    '<button type=\"button\" class=\"btn btn-outline-secondary btn-sm mr-1\" id=\"modalZoomIn\"><i class=\"fa fa-search-plus\"></i></button>' +
                    '<button type=\"button\" class=\"btn btn-outline-secondary btn-sm\" id=\"modalReset\">Reset</button>' +
                '</div>');
                body.append(controls);
                
                var wrap = $('<div style=\"flex-grow: 1; overflow: auto; background: #f8f9fa; text-align: center;\"></div>');
                var img = $('<img id=\"modalPreviewImage\" src=\"' + url + '\" style=\"max-width: 100%; transform-origin: top center; transition: transform 0.2s;\">');
                wrap.append(img);
                body.append(wrap);
            } else {
                var iframe = $('<iframe src=\"' + url + '\" style=\"width: 100%; flex-grow: 1; border: none;\"></iframe>');
                body.append(iframe);
            }

            previewModal.setBody(body);
            previewModal.show();

            // Bind zoom events after the body is inserted
            $('#modalZoomIn').on('click', function() {
                zoomLevel += 0.2;
                $('#modalPreviewImage').css('transform', 'scale(' + zoomLevel + ')');
            });
            $('#modalZoomOut').on('click', function() {
                if (zoomLevel > 0.4) {
                    zoomLevel -= 0.2;
                    $('#modalPreviewImage').css('transform', 'scale(' + zoomLevel + ')');
                }
            });
            $('#modalReset').on('click', function() {
                zoomLevel = 1;
                $('#modalPreviewImage').css('transform', 'scale(1)');
            });
        });

        // Clear when closed
        previewModal.getRoot().on(ModalEvents.hidden, function() {
            previewModal.setBody('');
        });
    });
});
");

echo $OUTPUT->footer();
