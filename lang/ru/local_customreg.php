<?php

$string['pluginname'] = 'Настройки курса для специальной регистрации';
$string['pendingapproval'] = 'Ваша регистрация ожидает одобрения.';

$string['areyouexisting'] = 'Вы уже являетесь участником?';
$string['existingmember'] = "Я действующий сотрудник/студент";
$string['newmember'] = "Нет, не являюсь";
$string['institutionid'] = 'ID учреждения';

$string['manageusers'] = 'Управление специальными регистрациями';
$string['approve'] = 'Одобрить';
$string['deny'] = 'Отклонить';
$string['identitytype'] = 'Тип идентификации';
$string['documentstatus'] = 'Статус документа';
$string['action'] = 'Действие';
$string['uploaded'] = 'Загружено';
$string['notuploaded'] = 'Не загружено';
$string['approved'] = 'Одобрено';
$string['pending'] = 'Ожидает';
$string['denied'] = 'Отклонено';
$string['customreg:manage'] = 'Управление специальными регистрациями';
$string['userapproved'] = 'Пользователь одобрен.';
$string['userdenied'] = 'Регистрация пользователя отклонена. Теперь пользователь может повторно загрузить свое удостоверение личности.';
$string['searchusers'] = 'Поиск пользователей или электронной почты';
$string['uploadagain'] = 'Ваш предыдущий документ не был принят. Пожалуйста, загрузите четкую копию государственного удостоверения личности еще раз.';
$string['availablecourses'] = 'Доступные курсы для регистрации';
$string['availablecourses_desc'] = 'Выберите курсы, которые пользователи могут выбрать в процессе регистрации (макс. 5).';
$string['selectcourses'] = 'Выбрать курсы';
$string['maxcoursesreached'] = 'Вы можете выбрать не более 5 курсов.';
$string['atleastonecourse'] = 'Вы должны выбрать как минимум один курс.';
$string['approvecourse'] = 'Одобрить курс';
$string['denycourse'] = 'Отклонить курс';
$string['approveallcourses'] = 'Одобрить все курсы';
$string['enrollmentstatus'] = 'Статус зачисления';
$string['coursetitle'] = 'Название курса';
$string['enrollsuccess'] = 'Пользователь успешно зачислен на курс.';
$string['alreadyenrolled'] = 'Пользователь уже зачислен на этот курс.';

$string['course1'] = 'Первый курс';
$string['course2'] = 'Второй курс';
$string['course3'] = 'Третий курс';
$string['course4'] = 'Четвертый курс';
$string['course5'] = 'Пятый курс';
$string['selectacourse'] = 'Выберите курс';

$string['admin_comments'] = 'Комментарии администратора';
$string['deny_with_reason'] = 'Отклонить с указанием причины';
$string['email_admin_subject'] = 'Загружено новое удостоверение: {$a->username}';
$string['email_admin_body'] = 'Пользователь {$a->username} загрузил свое удостоверение для проверки.
$string['email_approved_subject'] = 'Зачисление на курс одобрено';
$string['email_approved_body'] = 'Здравствуйте, {$a->firstname}!



$string['email_rejected_subject'] = 'Регистрация отклонена';
$string['email_rejected_body'] = 'Здравствуйте, {$a->firstname}!



$string['email_course_approved_subject'] = 'Зачисление на курс одобрено: {$a->coursename}';
$string['email_course_approved_body'] = 'Здравствуйте, {$a->firstname}!



$string['notapplicable'] = 'Н/Д';
$string['default_approve_comment'] = 'Ваша регистрация была одобрена после нашей проверки вручную.';
$string['default_deny_comment'] = 'Ваши документы были недостаточными или не соответствовали нашим требованиям.';
$string['default_approve_course_comment'] = 'Ваше зачисление на этот курс одобрено.';
$string['default_deny_course_comment'] = 'Вы не соответствуете предварительным условиям или предъявляемым критериям для данного конкретного курса.';

$string['email_admin_body'] = 'Пользователь {$a->username} загрузил свое удостоверение для проверки.
$string['email_approved_body'] = 'Здравствуйте, {$a->firstname}!



$string['email_rejected_body'] = 'Здравствуйте, {$a->firstname}!



$string['email_course_approved_body'] = 'Здравствуйте, {$a->firstname}!




$string['email_admin_body'] = 'Пользователь {$a->username} загрузил свое удостоверение для проверки.
Вы можете просмотреть запрос здесь: {$a->url}';
$string['email_approved_body'] = 'Здравствуйте, {$a->firstname}!

Ваша регистрация одобрена. Вы были зачислены на следующие курсы:
{$a->courses}

Комментарии администратора: {$a->comments}

Теперь вы можете войти в систему и получить доступ к своим курсам.: {$a->sitelink}';
$string['email_rejected_body'] = 'Здравствуйте, {$a->firstname}!

К сожалению, ваша регистрация была отклонена.

Комментарии администратора: {$a->comments}

Пожалуйста, войдите в систему и следуйте инструкциям для повторной загрузки документов.: {$a->uploadurl}';
$string['email_course_approved_body'] = 'Здравствуйте, {$a->firstname}!

Запрос на ваше участие в курсе «{$a->coursename}» был одобрен.

Комментарии администратора: {$a->comments}

Теперь вы можете получить доступ к курсу.: {$a->courseurl}';