<?php

$string['pluginname'] = '自定义注册课程设置';
$string['pendingapproval'] = '您的注册正在等待审批。';

$string['areyouexisting'] = '您是现有成员吗？';
$string['existingmember'] = "我是现有的员工/学生";
$string['newmember'] = "我不是";
$string['institutionid'] = '机构ID';

$string['manageusers'] = '管理自定义注册';
$string['approve'] = '批准';
$string['deny'] = '拒绝';
$string['identitytype'] = '身份类型';
$string['documentstatus'] = '文档状态';
$string['action'] = '操作';
$string['uploaded'] = '已上传';
$string['notuploaded'] = '未上传';
$string['approved'] = '已批准';
$string['pending'] = '等待中';
$string['denied'] = '已拒绝';
$string['customreg:manage'] = '管理自定义注册';
$string['userapproved'] = '用户已获批准。';
$string['userdenied'] = '用户注册已被拒绝。用户现在可以重新上传其身份证件。';
$string['searchusers'] = '搜索用户或电子邮件';
$string['uploadagain'] = '您之前的文档未被接受。请重新上传一份清晰的政府身份证件副本。';
$string['availablecourses'] = '可供注册的课程';
$string['availablecourses_desc'] = '选择用户在注册过程中可以选择的课程（最多5个）。';
$string['selectcourses'] = '选择课程';
$string['maxcoursesreached'] = '您最多只能选择5门课程。';
$string['atleastonecourse'] = '您必须至少选择一门课程。';
$string['approvecourse'] = '批准课程';
$string['denycourse'] = '拒绝课程';
$string['approveallcourses'] = '批准所有课程';
$string['enrollmentstatus'] = '选课状态';
$string['coursetitle'] = '课程名称';
$string['enrollsuccess'] = '用户成功选修了课程。';
$string['alreadyenrolled'] = '用户已经选修了这门课程。';

$string['course1'] = '第一门课程';
$string['course2'] = '第二门课程';
$string['course3'] = '第三门课程';
$string['course4'] = '第四门课程';
$string['course5'] = '第五门课程';
$string['selectacourse'] = '选择一门课程';

$string['admin_comments'] = '管理员说明';
$string['deny_with_reason'] = '拒绝并说明理由';
$string['email_admin_subject'] = '新身份证明上传：{$a->username}';
$string['email_admin_body'] = '用户 {$a->username} 已上传身份证明供审核。
$string['email_approved_subject'] = '课程注册已通过';
$string['email_approved_body'] = '您好 {$a->firstname}：



$string['email_rejected_subject'] = '注册被拒绝';
$string['email_rejected_body'] = '您好 {$a->firstname}：



$string['email_course_approved_subject'] = '课程注册已通过：{$a->coursename}';
$string['email_course_approved_body'] = '您好 {$a->firstname}：



$string['notapplicable'] = '不适用';
$string['default_approve_comment'] = '经过我们人工审核，您的注册已获批准。';
$string['default_deny_comment'] = '您的材料不足或不符合我们的要求。';
$string['default_approve_course_comment'] = '您在该课程的注册已获批准。';
$string['default_deny_course_comment'] = '您不符合此特定课程的先决条件或要求。';

$string['email_admin_body'] = '用户 {$a->username} 已上传身份证明供审核。
$string['email_approved_body'] = '您好 {$a->firstname}：



$string['email_rejected_body'] = '您好 {$a->firstname}：



$string['email_course_approved_body'] = '您好 {$a->firstname}：




$string['email_admin_body'] = '用户 {$a->username} 已上传身份证明供审核。
您可以在此处审核请求：{$a->url}';
$string['email_approved_body'] = '您好 {$a->firstname}：

您的注册已获批准。您已加入以下课程：
{$a->courses}

管理员说明：{$a->comments}

您现在可以登录并访问您的课程。: {$a->sitelink}';
$string['email_rejected_body'] = '您好 {$a->firstname}：

很抱歉，您的注册已被拒绝。

管理员说明：{$a->comments}

请登录并按照说明重新上传您的材料。: {$a->uploadurl}';
$string['email_course_approved_body'] = '您好 {$a->firstname}：

您加入课程“{$a->coursename}”的请求已获批准。

管理员说明：{$a->comments}

您现在可以访问该课程。: {$a->courseurl}';