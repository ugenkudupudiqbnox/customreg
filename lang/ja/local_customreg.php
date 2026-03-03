<?php

$string['pluginname'] = 'カスタム登録のためのコース設定';
$string['pendingapproval'] = 'あなたの登録は承認待ちです。';

$string['areyouexisting'] = '既存のメンバーですか？';
$string['existingmember'] = "既存の従業員/学生です";
$string['newmember'] = "いいえ、違います";
$string['institutionid'] = '施設ID';

$string['manageusers'] = 'カスタム登録の管理';
$string['approve'] = '承認';
$string['deny'] = '拒否';
$string['identitytype'] = '身元タイプ';
$string['documentstatus'] = 'ドキュメントの状態';
$string['action'] = 'アクション';
$string['uploaded'] = 'アップロード済み';
$string['notuploaded'] = 'アップロードされていません';
$string['approved'] = '承認済み';
$string['pending'] = '保留中';
$string['denied'] = '拒否されました';
$string['customreg:manage'] = 'カスタム登録を管理する';
$string['userapproved'] = 'ユーザーが承認されました。';
$string['userdenied'] = 'ユーザー登録が拒否されました。ユーザーはIDを再アップロードできます。';
$string['searchusers'] = 'ユーザーまたはメールを検索';
$string['uploadagain'] = '以前のドキュメントは受け入れられませんでした。公的な身分証明書の鮮明なコピーを再度アップロードしてください。';
$string['availablecourses'] = '登録可能なコース';
$string['availablecourses_desc'] = 'サインアッププロセス中にユーザーが選択できるコースを選択します（最大5）。';
$string['selectcourses'] = 'コースを選択';
$string['maxcoursesreached'] = '最大5つのコースを選択できます。';
$string['atleastonecourse'] = '少なくとも1つのコースを選択する必要があります。';
$string['approvecourse'] = 'コースを承認';
$string['denycourse'] = 'コースを拒否';
$string['approveallcourses'] = 'すべてのコースを承認';
$string['enrollmentstatus'] = '登録状況';
$string['coursetitle'] = 'コース名';
$string['enrollsuccess'] = 'ユーザーがコースに正常に登録されました。';
$string['alreadyenrolled'] = 'ユーザーは既にこのコースに登録されています。';

$string['course1'] = '第1コース';
$string['course2'] = '第2コース';
$string['course3'] = '第3コース';
$string['course4'] = '第4コース';
$string['course5'] = '第5コース';
$string['selectacourse'] = 'コースを選択';

$string['admin_comments'] = '管理者コメント';
$string['deny_with_reason'] = '理由を添えて拒否';
$string['email_admin_subject'] = '新しいIDのアップロード: {$a->username}';
$string['email_admin_body'] = 'ユーザー {$a->username} が確認のためにIDをアップロードしました。\nこちらでリクエストを確認できます: {$a->url}';
$string['email_approved_subject'] = 'コースへの登録が承認されました';
$string['email_approved_body'] = '{$a->firstname} さん、こんにちは。\n\n登録が承認されました。以下のコースに登録されました：\n{$a->courses}\n\n管理者コメント: {$a->comments}\n\nログインしてコースにアクセスできるようになりました。';
$string['email_rejected_subject'] = '登録が拒否されました';
$string['email_rejected_body'] = '{$a->firstname} さん、こんにちは。\n\n残念ながら、登録は拒否されました。\n\n管理者コメント: {$a->comments}\n\nログインして、指示に従って書類を再アップロードしてください。';
$string['email_course_approved_subject'] = 'コースへの登録が承認されました: {$a->coursename}';
$string['email_course_approved_body'] = '{$a->firstname} さん、こんにちは。\n\nコース「{$a->coursename}」への参加リクエストが承認されました。\n\n管理者コメント: {$a->comments}\n\nコースにアクセスできるようになりました。';
$string['notapplicable'] = '該当なし';
$string['default_approve_comment'] = '手動による確認の結果、登録が承認されました。';
$string['default_deny_comment'] = '書類が不十分であるか、要件を満たしていませんでした。';
$string['default_approve_course_comment'] = 'このコースへの登録が承認されました。';
$string['default_deny_course_comment'] = 'この特定のコースの前提条件または必要な基準を満たしていません。';
