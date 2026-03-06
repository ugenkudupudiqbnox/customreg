<?php
require('../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/local/customreg/lib.php');
require_login();

admin_externalpage_setup('local_customreg_manage');

$context = context_system::instance();

$action = optional_param('action', '', PARAM_ALPHANUMEXT);
$userid = optional_param('userid', 0, PARAM_INT);
$search = optional_param('search', '', PARAM_TEXT);
$page   = optional_param('page', 0, PARAM_INT);
$perpage = 20;

// Handle CSV Export
if ($action === 'downloadcsv') {
    require_capability('local/customreg:manage', $context);
    
    // Build SQL for all records matches search but no pagination
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
          
    $records = $DB->get_records_sql($sql, $params);
    
    $filename = 'customreg_data_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $filename);
    
    $output = fopen('php://output', 'w');
    
    // Add UTF-8 BOM for Excel compatibility
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Header row
    fputcsv($output, [
        get_string('csv_firstname', 'local_customreg'),
        get_string('csv_lastname', 'local_customreg'),
        get_string('csv_email', 'local_customreg'),
        get_string('csv_institutionid', 'local_customreg'),
        get_string('csv_status', 'local_customreg'),
        get_string('csv_courses', 'local_customreg'),
        get_string('csv_timecreated', 'local_customreg')
    ]);
    
    foreach ($records as $rec) {
        // Format course list for CSV
        $coursesjson = json_decode($rec->courseidsjson, true) ?: [];
        $courselist = [];
        foreach ($coursesjson as $cinfo) {
            $course = $DB->get_record('course', ['id' => $cinfo['id']], 'shortname');
            if ($course) {
                $courselist[] = "{$course->shortname} ({$cinfo['status']})";
            }
        }
        
        fputcsv($output, [
            $rec->firstname,
            $rec->lastname,
            $rec->email,
            $rec->institutionid,
            $rec->status,
            implode(' | ', $courselist),
            userdate($rec->timecreated, '%Y-%m-%d %H:%M:%S')
        ]);
    }
    
    fclose($output);
    exit;
}

// Handle Log Retrieval (AJAX-like response)
if ($action === 'getlogs' && $userid > 0) {
    require_capability('local/customreg:manage', $context);
    header('Content-Type: application/json');
    // Limit to latest 50 logs to avoid performance issues
    $logs = $DB->get_records_sql("
        SELECT l.*, u.firstname, u.lastname 
        FROM {local_customreg_logs} l 
        LEFT JOIN {user} u ON l.adminid = u.id 
        WHERE l.userid = ? 
        ORDER BY l.timecreated DESC", [$userid], 0, 50);
    
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
    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_approve_comment', 'local_customreg');
    }
    $DB->set_field('local_customreg', 'status', 'approved', ['userid' => $userid]);
    $DB->set_field('local_customreg', 'admin_comments', $comments, ['userid' => $userid]);
    $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $userid]);
    
    // Log approval
    local_customreg_log($userid, 'approved', 'Registration overall approved. Comments: ' . $comments);
    
    // Notify user
    $rec = $DB->get_record('local_customreg', ['userid' => $userid]);
    $courses = json_decode($rec->courseidsjson, true) ?: [];
    $approved_ids = [];
    foreach ($courses as $c) {
        if ($c['status'] === 'approved') $approved_ids[] = $c['id'];
    }
    local_customreg_notify_user_status($userid, 'approved', $comments, $approved_ids);

    redirect($PAGE->url, get_string('userapproved', 'local_customreg'), 2);
}

// Handle Deny Action
if ($action === 'deny' && $userid > 0 && confirm_sesskey()) {
    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_deny_comment', 'local_customreg');
    }
    $DB->set_field('local_customreg', 'status', 'rejected', ['userid' => $userid]);
    $DB->set_field('local_customreg', 'admin_comments', $comments, ['userid' => $userid]);
    $DB->set_field('local_customreg', 'documentuploaded', 0, ['userid' => $userid]);
    $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $userid]);
    
    // Log denial
    local_customreg_log($userid, 'rejected', 'Registration request rejected. Comments: ' . $comments);
    
    // Notify user
    local_customreg_notify_user_status($userid, 'rejected', $comments);

    // Optional: Delete existing files to save space and avoid confusion
    $fs = get_file_storage();
    $fs->delete_area_files($context->id, 'local_customreg', 'govid', $userid);
    
    redirect($PAGE->url, get_string('userdenied', 'local_customreg'), 2);
}

// Handle Individual Course Approval
if ($action === 'approvecourse' && $userid > 0 && confirm_sesskey()) {
    $courseid = required_param('courseid', PARAM_INT);
    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_approve_course_comment', 'local_customreg');
    }
    $rec = $DB->get_record('local_customreg', ['userid' => $userid], '*', MUST_EXIST);
    $courses = json_decode($rec->courseidsjson, true) ?: [];
    
    // Count already approved courses
    $approvedcount = 0;
    foreach ($courses as $c) {
        if ($c['status'] === 'approved') {
            $approvedcount++;
        }
    }

    if ($approvedcount >= 5) {
        redirect($PAGE->url, get_string('maxcoursesreached', 'local_customreg'), 2);
    }

    foreach ($courses as &$c) {
        if ($c['id'] == $courseid) {
            $c['status'] = 'approved';
            local_customreg_enroll_user_into_course($userid, $courseid);
            local_customreg_log($userid, 'approvecourse', "Course ID $courseid approved and user enrolled. Comments: $comments");
            
            // Notify user for single course
            local_customreg_notify_course_approved($userid, $courseid, $comments);
        }
    }
    
    $DB->set_field('local_customreg', 'courseidsjson', json_encode($courses), ['id' => $rec->id]);
    redirect($PAGE->url, get_string('enrollsuccess', 'local_customreg'), 2);
}

// Handle Individual Course Denial
if ($action === 'denycourse' && $userid > 0 && confirm_sesskey()) {
    $courseid = required_param('courseid', PARAM_INT);
    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_deny_course_comment', 'local_customreg');
    }
    $rec = $DB->get_record('local_customreg', ['userid' => $userid], '*', MUST_EXIST);
    $courses = json_decode($rec->courseidsjson, true) ?: [];
    
    foreach ($courses as &$c) {
        if ($c['id'] == $courseid) {
            $c['status'] = 'rejected';
            local_customreg_log($userid, 'denycourse', "Course ID $courseid rejected. Comments: $comments");
        }
    }
    
    $DB->set_field('local_customreg', 'courseidsjson', json_encode($courses), ['id' => $rec->id]);
    redirect($PAGE->url, get_string('userdenied', 'local_customreg'), 2);
}

// Handle Bulk Course Approval
if ($action === 'approveallcourses' && $userid > 0 && confirm_sesskey()) {
    $comments = optional_param('comments', '', PARAM_TEXT);
    if (empty($comments)) {
        $comments = get_string('default_approve_course_comment', 'local_customreg');
    }
    $rec = $DB->get_record('local_customreg', ['userid' => $userid], '*', MUST_EXIST);
    $courses = json_decode($rec->courseidsjson, true) ?: [];
    
    $newly_approved = [];
    foreach ($courses as &$c) {
        if ($c['status'] === 'pending') {
            $c['status'] = 'approved';
            local_customreg_enroll_user_into_course($userid, $c['id']);
            local_customreg_log($userid, 'approvecourse', "Course ID {$c['id']} approved via bulk action. Comments: $comments");
            $newly_approved[] = $c['id'];
        }
    }

    if (!empty($newly_approved)) {
        // Send ONE email for all newly approved courses in bulk
        local_customreg_notify_user_status($userid, 'approved', $comments, $newly_approved);
    }
    
    $DB->set_field('local_customreg', 'courseidsjson', json_encode($courses), ['id' => $rec->id]);
    redirect($PAGE->url, get_string('enrollsuccess', 'local_customreg'), 2);
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('manageusers', 'local_customreg'));

// Action bar with CSV Download
echo '<div class="d-flex justify-content-between align-items-center mb-4">';
$downloadurl = new moodle_url($PAGE->url, ['action' => 'downloadcsv', 'search' => $search]);
echo html_writer::link($downloadurl, $OUTPUT->pix_icon('t/download', '') . ' ' . get_string('downloadcsv', 'local_customreg'), ['class' => 'btn btn-secondary']);

// Search Bar
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
        'Requested Courses',
        get_string('csv_timecreated', 'local_customreg'),
        get_string('action', 'local_customreg')
    ];

    foreach ($records as $rec) {
        $userlink = html_writer::link(new moodle_url('/user/profile.php', ['id' => $rec->userid]), fullname($rec));
        
        $statusstr = get_string($rec->status, 'local_customreg');
        $statuscolor = 'badge-warning';
        if ($rec->status === 'approved') {
            $statuscolor = 'badge-success';
        } else if ($rec->status === 'denied' || $rec->status === 'rejected') {
            $statuscolor = 'badge-danger';
        }
        $statusbadge = html_writer::tag('span', $statusstr, ['class' => 'badge ' . $statuscolor]);

        // ID Preview Logic
        $id_with_preview = s($rec->institutionid);
        if ($rec->documentuploaded) {
            $fs = get_file_storage();
            $files = $fs->get_area_files($context->id, 'local_customreg', 'govid', $rec->userid, 'id DESC', false);
            if ($files) {
                $file = reset($files);
                $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(), $file->get_itemid(), $file->get_filepath(), $file->get_filename());
                
                $id_with_preview .= ' ' . html_writer::link('#', $OUTPUT->pix_icon('t/preview', 'View ID'), [
                    'class' => 'view-id-trigger',
                    'data-url' => $url->out(false),
                    'title' => 'View ID'
                ]);
            }
        }

        // Course Selection Column Logic
        $coursesjson = json_decode($rec->courseidsjson, true) ?: [];
        $courselist = [];
        $haspending = false;

        foreach ($coursesjson as $cinfo) {
            $course = $DB->get_record('course', ['id' => $cinfo['id']]);
            if (!$course) continue;
            
            if ($cinfo['status'] === 'pending') {
                $haspending = true;
                $statusclass = 'badge-warning';
                
                // Individual course buttons
                $approvecurl = new moodle_url($PAGE->url, [
                    'action' => 'approvecourse', 
                    'userid' => $rec->userid, 
                    'courseid' => $cinfo['id'], 
                    'sesskey' => sesskey()
                ]);
                $denycurl = new moodle_url($PAGE->url, [
                    'action' => 'denycourse', 
                    'userid' => $rec->userid, 
                    'courseid' => $cinfo['id'], 
                    'sesskey' => sesskey()
                ]);
                
                $citem = html_writer::tag('div', 
                    $course->fullname . ' ' .
                    html_writer::tag('span', 'Pending', ['class' => 'badge ' . $statusclass]) . ' ' .
                    $OUTPUT->action_icon('#', new pix_icon('t/check', 'Approve'), null, ['class' => 'action-with-comment', 'data-action' => 'approvecourse', 'data-userid' => $rec->userid, 'data-courseid' => $cinfo['id']]) .
                    $OUTPUT->action_icon('#', new pix_icon('t/delete', 'Deny'), null, ['class' => 'action-with-comment', 'data-action' => 'denycourse', 'data-userid' => $rec->userid, 'data-courseid' => $cinfo['id']]),
                    ['class' => 'mb-1 border-bottom pb-1']
                );
            } else {
                $statusclass = ($cinfo['status'] === 'approved') ? 'badge-success' : 'badge-danger';
                $citem = html_writer::tag('div', 
                    $course->fullname . ' ' .
                    html_writer::tag('span', ucfirst($cinfo['status']), ['class' => 'badge ' . $statusclass]),
                    ['class' => 'mb-1 border-bottom pb-1']
                );
            }
            $courselist[] = $citem;
        }

        $courseinfo = implode('', $courselist);
        if ($haspending) {
            $courseinfo .= html_writer::link('#', 'Approve All Courses', ['class' => 'btn btn-outline-success btn-sm mt-1 action-with-comment', 'data-action' => 'approveallcourses', 'data-userid' => $rec->userid]);
        }

        if (empty($courseinfo)) {
            $courseinfo = '<small>No courses selected</small>';
        }

        $actions = [];

        if ($rec->status !== 'approved') {
            $actions[] = $OUTPUT->action_icon('#', new pix_icon('t/check', get_string('approve', 'local_customreg')), null, ['class' => 'action-with-comment', 'data-action' => 'approve', 'data-userid' => $rec->userid]);
            $actions[] = $OUTPUT->action_icon('#', new pix_icon('i/invalid', get_string('deny', 'local_customreg')), null, ['class' => 'action-with-comment', 'data-action' => 'deny', 'data-userid' => $rec->userid]);
        }
        
        // Addition: Log History Icon
        $actions[] = html_writer::link('#', $OUTPUT->pix_icon('i/menu', 'View Timeline'), [
            'class' => 'view-log-trigger',
            'data-userid' => $rec->userid,
            'title' => 'Registration Timeline'
        ]);

        $defaultcomments = [
            'approve' => get_string('default_approve_comment', 'local_customreg'),
            'deny' => get_string('default_deny_comment', 'local_customreg'),
            'approvecourse' => get_string('default_approve_course_comment', 'local_customreg'),
            'denycourse' => get_string('default_deny_course_comment', 'local_customreg'),
            'approveallcourses' => get_string('default_approve_course_comment', 'local_customreg'),
        ];

        $table->data[] = [
            $userlink . '<br><small>' . s($rec->email) . '</small>',
            $id_with_preview,
            $statusbadge,
            $courseinfo,
            userdate($rec->timecreated, get_string('strftimedatetimeshort', 'langconfig')),
            implode(' ', $actions)
        ];
    }

    echo html_writer::table($table);

    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $PAGE->url);
}

// JavaScript to handle the click and update the modal using Moodle standard AMD
$defaultcommentsjson = json_encode($defaultcomments);
$PAGE->requires->js_amd_inline("
require(['jquery', 'core/modal_factory', 'core/modal_events'], function($, ModalFactory, ModalEvents) {
    var zoomLevel = 1;
    var timelineModal = null;
    var previewModal = null;
    var commentModal = null;
    var defaultComments = {$defaultcommentsjson};

    // --- Action with Comment Modal ---
    ModalFactory.create({
        title: 'Add Admin Comment',
        type: ModalFactory.types.SAVE_CANCEL,
        body: '<div class=\"form-group\">' +
              '<label for=\"admin-comment-input\">Comments / Reason</label>' +
              '<textarea id=\"admin-comment-input\" class=\"form-control\" rows=\"3\"></textarea>' +
              '</div>'
    }).then(function(modal) {
        commentModal = modal;
        modal.setSaveButtonText('Submit Action');

        $(document).on('click', '.action-with-comment', function(e) {
            e.preventDefault();
            var btn = $(this);
            var data = btn.data();
            
            // Set default comment based on action
            var defaultText = defaultComments[data.action] || '';
            
            modal.show();
            // Wait for modal to be fully shown and DOM to be ready
            modal.getRoot().find('#admin-comment-input').val(defaultText);

            modal.getRoot().off(ModalEvents.save).on(ModalEvents.save, function() {
                var comment = modal.getRoot().find('#admin-comment-input').val();
                var url = new URL(window.location.href);
                url.searchParams.set('action', data.action);
                url.searchParams.set('userid', data.userid);
                if (data.courseid) url.searchParams.set('courseid', data.courseid);
                url.searchParams.set('comments', comment);
                url.searchParams.set('sesskey', M.cfg.sesskey);
                window.location.href = url.href;
            });
        });
    });

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
