<?php
require('../../config.php');
require_login();

$context = context_system::instance();
$PAGE->set_url('/local/customreg/upload.php');

echo $OUTPUT->header();
echo $OUTPUT->heading('Upload Government ID');

$mform = new MoodleQuickForm('uploadform','post','');

$mform->addElement('filepicker','govid','Government ID',
    null,['accepted_types'=>['.pdf','.jpg','.png']]);

$mform->addElement('submit','submitbutton','Upload');

if($data=$mform->get_data()){

    $draftid = file_get_submitted_draft_itemid('govid');
    file_save_draft_area_files($draftid,$context->id,
        'local_customreg','govid',$USER->id);

    $DB->set_field('local_customreg','documentuploaded',1,
        ['userid'=>$USER->id]);

    echo $OUTPUT->notification('Document uploaded. Awaiting approval.');
}

$mform->display();
echo $OUTPUT->footer();
