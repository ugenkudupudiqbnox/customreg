<?php

$string['pluginname'] = '사용자 정의 등록을 위한 코스 설정';
$string['pendingapproval'] = '귀하의 등록이 승인 대기 중입니다.';

$string['areyouexisting'] = '기존 회원이십니까?';
$string['existingmember'] = "기존 직원/학생입니다";
$string['newmember'] = "회원이 아닙니다";
$string['institutionid'] = '기관 ID';

$string['manageusers'] = '사용자 정의 등록 관리';
$string['approve'] = '승인';
$string['deny'] = '거부';
$string['identitytype'] = '신원 유형';
$string['documentstatus'] = '문서 상태';
$string['action'] = '작업';
$string['uploaded'] = '업로드됨';
$string['notuploaded'] = '업로드되지 않음';
$string['approved'] = '승인됨';
$string['pending'] = '대기 중';
$string['denied'] = '거부됨';
$string['customreg:manage'] = '사용자 정의 등록 관리';
$string['userapproved'] = '사용자가 승인되었습니다.';
$string['userdenied'] = '사용자 등록이 거부되었습니다. 사용자는 이제 신분증을 다시 업로드할 수 있습니다.';
$string['searchusers'] = '사용자 또는 이메일 검색';
$string['uploadagain'] = '이전 문서가 수락되지 않았습니다. 정부 발급 신분증의 선명한 사본을 다시 업로드해 주세요.';
$string['availablecourses'] = '등록 가능한 코스';
$string['availablecourses_desc'] = '사용자가 가입 과정에서 선택할 수 있는 코스를 선택하세요(최대 5개).';
$string['selectcourses'] = '코스 선택';
$string['maxcoursesreached'] = '최대 5개의 코스를 선택할 수 있습니다.';
$string['atleastonecourse'] = '최소 한 개의 코스를 선택해야 합니다.';
$string['approvecourse'] = '코스 승인';
$string['denycourse'] = '코스 거부';
$string['approveallcourses'] = '모든 코스 승인';
$string['enrollmentstatus'] = '수강 등록 상태';
$string['coursetitle'] = '코스명';
$string['enrollsuccess'] = '사용자가 코스에 성공적으로 등록되었습니다.';
$string['alreadyenrolled'] = '사용자가 이미 이 코스에 등록되어 있습니다.';

$string['course1'] = '첫 번째 코스';
$string['course2'] = '두 번째 코스';
$string['course3'] = '세 번째 코스';
$string['course4'] = '네 번째 코스';
$string['course5'] = '다섯 번째 코스';
$string['selectacourse'] = '코스 선택';

$string['admin_comments'] = '관리자 코멘트';
$string['deny_with_reason'] = '사유와 함께 거절';
$string['email_admin_subject'] = '새 ID 업로드: {$a->username}';
$string['email_admin_body'] = '사용자 {$a->username}님이 검토를 위해 ID를 업로드했습니다.
$string['email_approved_subject'] = '강좌 등록 승인됨';
$string['email_approved_body'] = '안녕하세요 {$a->firstname}님,



$string['email_rejected_subject'] = '등록 거절됨';
$string['email_rejected_body'] = '안녕하세요 {$a->firstname}님,



$string['email_course_approved_subject'] = '강좌 등록 승인됨: {$a->coursename}';
$string['email_course_approved_body'] = '안녕하세요 {$a->firstname}님,



$string['notapplicable'] = '해당 없음';
$string['default_approve_comment'] = '수동 검토 결과 등록이 승인되었습니다.';
$string['default_deny_comment'] = '서류가 불충분하거나 요구 사항과 일치하지 않습니다.';
$string['default_approve_course_comment'] = '이 강좌로의 등록이 승인되었습니다.';
$string['default_deny_course_comment'] = '이 특정 강좌에 필요한 선수 과목이나 기준을 충족하지 못했습니다.';

$string['email_admin_body'] = '사용자 {$a->username}님이 검토를 위해 ID를 업로드했습니다.
$string['email_approved_body'] = '안녕하세요 {$a->firstname}님,



$string['email_rejected_body'] = '안녕하세요 {$a->firstname}님,



$string['email_course_approved_body'] = '안녕하세요 {$a->firstname}님,




$string['email_admin_body'] = '사용자 {$a->username}님이 검토를 위해 ID를 업로드했습니다.
여기에서 요청을 검토할 수 있습니다: {$a->url}';
$string['email_approved_body'] = '안녕하세요 {$a->firstname}님,

등록이 승인되었습니다. 다음 강좌에 등록되었습니다:
{$a->courses}

관리자 코멘트: {$a->comments}

이제 로그인하여 강좌에 접속할 수 있습니다.: {$a->sitelink}';
$string['email_rejected_body'] = '안녕하세요 {$a->firstname}님,

안타깝게도 등록이 거절되었습니다.

관리자 코멘트: {$a->comments}

로그인하여 안내에 따라 서류를 다시 업로드해 주세요.: {$a->uploadurl}';
$string['email_course_approved_body'] = '안녕하세요 {$a->firstname}님,

\"{$a->coursename}\" 강좌 참여 요청이 승인되었습니다.

관리자 코멘트: {$a->comments}

이제 강좌에 접속할 수 있습니다.: {$a->courseurl}';