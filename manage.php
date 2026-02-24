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
echo '<form action="'.$PAGE->url.'" method="get" class="form-inline">';
echo '<div class="input-group">';
echo '<input type="text" name="search" class="form-control" placeholder="Search by name or email" value="'.s($search).'">';
echo '<div class="input-group-append">';
echo '<button type="submit" class="btn btn-primary" title="'.s(get_string('searchusers', 'local_customreg')).'"><i class="fa fa-search"></i></button>';
echo '</div>';
if ($search) {
    echo '<div class="input-group-append ml-2 ms-2">';
    echo '<a href="'.$PAGE->url.'" class="btn btn-secondary">Clear</a>';
    echo '</div>';
}
echo '</div>';
echo '</form>';
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

// Modal HTML for Identity Document Preview
echo '
<div class="modal fade" id="idPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between align-items-center flex-wrap">
        <h5 class="modal-title mr-3">Identity Document Preview</h5>
        <div id="imageControls" style="display:none;" class="mx-auto">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="zoomOut"><i class="fa fa-search-minus"></i></button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="zoomIn"><i class="fa fa-search-plus"></i></button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="resetZoom">Reset</button>
        </div>
        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0" style="overflow: auto; height: 70vh; background: #f8f9fa;">
        <iframe id="previewIframe" src="" style="width:100%; height:100%; border:none; display:none;"></iframe>
        <div id="imageWrap" style="display:none; text-align:center; height:100%;">
            <img id="previewImage" src="" style="max-width:100%; transform-origin: top center; transition: transform 0.2s;">
        </div>
      </div>
    </div>
  </div>
</div>';

// Modal HTML for Timeline Log
echo '
<div class="modal fade" id="logTimelineModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between align-items-center">
        <h5 class="modal-title m-0">Registration Timeline</h5>
        <button type="button" class="close ml-auto ms-auto" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close" style="float: right;">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="logTimelineBody" style="max-height: 60vh; overflow-y: auto;">
        Loading...
      </div>
    </div>
  </div>
</div>';

// JavaScript to handle the click and update the modal using Moodle standard AMD
$PAGE->requires->js_amd_inline("
require(['jquery'], function($) {
    var zoomLevel = 1;

    // Timeline Log Logic
    $('.view-log-trigger').on('click', function(e) {
        e.preventDefault();
        var userid = $(this).attr('data-userid');
        $('#logTimelineBody').html('<div class=\"text-center\"><i class=\"fa fa-spinner fa-spin\"></i> Loading...</div>');
        
        // Show modal (BS detection)
        if (typeof($.fn.modal) !== 'undefined') {
            $('#logTimelineModal').modal('show');
        } else {
            console.error('Modal library not found');
        }

        $.get('manage.php', {action: 'getlogs', userid: userid}, function(data) {
            if (data.length === 0) {
                $('#logTimelineBody').html('<div class=\"alert alert-info\">No logs found for this user.</div>');
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
            $('#logTimelineBody').html(html);
        });
    });

    // Identity Preview Modal Logic
    $('.view-id-trigger').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('data-url');
        var isImage = url.match(/\.(jpg|jpeg|png|gif|webp)/i);
        
        zoomLevel = 1;
        $('#previewImage').css('transform', 'scale(1)');

        if (isImage) {
            $('#previewIframe').hide().attr('src', '');
            $('#imageWrap').show();
            $('#previewImage').attr('src', url);
            $('#imageControls').show();
        } else {
            $('#imageWrap').hide();
            $('#previewImage').attr('src', '');
            $('#imageControls').hide();
            $('#previewIframe').show().attr('src', url);
        }
        
        var modalEl = document.getElementById('idPreviewModal');
        if (window.bootstrap && window.bootstrap.Modal) {
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        } else if ($(modalEl).modal) {
            $(modalEl).modal('show');
        }
    });

    $('#zoomIn').on('click', function() {
        zoomLevel += 0.2;
        $('#previewImage').css('transform', 'scale(' + zoomLevel + ')');
    });

    $('#zoomOut').on('click', function() {
        if (zoomLevel > 0.4) {
            zoomLevel -= 0.2;
            $('#previewImage').css('transform', 'scale(' + zoomLevel + ')');
        }
    });

    $('#resetZoom').on('click', function() {
        zoomLevel = 1;
        $('#previewImage').css('transform', 'scale(1)');
    });
    
    $('#idPreviewModal').on('hidden.bs.modal', function () {
        $('#previewIframe').attr('src', '');
        $('#previewImage').attr('src', '');
    });
});
");

echo $OUTPUT->footer();
