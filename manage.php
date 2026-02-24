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

// Handle Approval Action
if ($action === 'approve' && $userid > 0 && confirm_sesskey()) {
    $DB->set_field('local_customreg', 'status', 'approved', ['userid' => $userid]);
    $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $userid]);
    redirect($PAGE->url, get_string('userapproved', 'local_customreg'), 2);
}

// Handle Deny Action
if ($action === 'deny' && $userid > 0 && confirm_sesskey()) {
    $DB->set_field('local_customreg', 'status', 'denied', ['userid' => $userid]);
    $DB->set_field('local_customreg', 'documentuploaded', 0, ['userid' => $userid]);
    $DB->set_field('local_customreg', 'timemodified', time(), ['userid' => $userid]);
    
    // Optional: Delete existing files to save space and avoid confusion
    $fs = get_file_storage();
    $fs->delete_area_files($context->id, 'local_customreg', 'govid', $userid);
    
    redirect($PAGE->url, get_string('userdenied', 'local_customreg'), 2);
}

echo $OUTPUT->header();

echo $OUTPUT->heading(get_string('manageusers', 'local_customreg'));

// Search Bar
echo '<div class="mb-4">';
echo '<form action="'.$PAGE->url.'" method="get" class="form-inline">';
echo '<input type="text" name="search" class="form-control mr-sm-2" placeholder="Search by name or email" value="'.s($search).'">';
echo '<button type="submit" class="btn btn-primary">'.get_string('searchusers', 'local_customreg').'</button>';
if ($search) {
    echo ' <a href="'.$PAGE->url.'" class="btn btn-secondary ml-2">Clear</a>';
}
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
        get_string('identitytype', 'local_customreg'),
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

        $table->data[] = [
            $userlink . '<br><small>' . s($rec->email) . '</small>',
            s($rec->identitytype),
            s($rec->institutionid),
            $statusbadge,
            implode(' ', $actions)
        ];
    }

    echo html_writer::table($table);

    echo $OUTPUT->paging_bar($totalcount, $page, $perpage, $PAGE->url);
}

// Modal HTML for in-page preview
echo '
<div class="modal fade" id="idPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between align-items-center">
        <h5 class="modal-title">Identity Document Preview</h5>
        <div id="imageControls" style="display:none;">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="zoomOut"><i class="fa fa-search-minus"></i></button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="zoomIn"><i class="fa fa-search-plus"></i></button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="resetZoom">Reset</button>
        </div>
        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0" style="overflow: auto; height: 70vh; background: #f8f9fa; position: relative;">
        <iframe id="previewIframe" src="" style="width:100%; height:100%; border:none; display:none;"></iframe>
        <div id="imageWrap" style="display:none; text-align:center; height:100%;">
            <img id="previewImage" src="" style="max-width:100%; transform-origin: top center; transition: transform 0.2s;">
        </div>
      </div>
    </div>
  </div>
</div>';

// JavaScript to handle the click and update the modal using Moodle standard AMD
$PAGE->requires->js_amd_inline("
require(['jquery'], function($) {
    var zoomLevel = 1;

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
