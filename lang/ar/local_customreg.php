<?php

$string['pluginname'] = 'إعدادات المقرر لطلب التسجيل المخصص';
$string['pendingapproval'] = 'طلب تسجيلك قيد المراجعة حالياً.';

$string['areyouexisting'] = 'هل أنت عضو حالي؟';
$string['existingmember'] = "أنا موظف/طالب حالي";
$string['newmember'] = "أنا لست كذلك";
$string['institutionid'] = 'معرف المؤسسة';

$string['manageusers'] = 'إدارة طلبات التسجيل المخصصة';
$string['approve'] = 'موافقة';
$string['deny'] = 'رفض';
$string['identitytype'] = 'نوع الهوية';
$string['documentstatus'] = 'حالة المستند';
$string['action'] = 'الإجراء';
$string['uploaded'] = 'تم الرفع';
$string['notuploaded'] = 'لم يتم الرفع';
$string['approved'] = 'تمت الموافقة';
$string['pending'] = 'قيد الانتظار';
$string['denied'] = 'تم الرفض';
$string['customreg:manage'] = 'إدارة طلبات التسجيل المخصصة';
$string['userapproved'] = 'تمت الموافقة على المستخدم.';
$string['userdenied'] = 'تم رفض طلب تسجيل المستخدم. يمكن للمستخدم الآن إعادة رفع هويته.';
$string['searchusers'] = 'البحث عن المستخدمين أو البريد الإلكتروني';
$string['uploadagain'] = 'لم يتم قبول مستندك السابق. يرجى إعادة رفع نسخة واضحة من بطاقة هويتك الحكومية.';
$string['availablecourses'] = 'المقررات المتاحة للتسجيل';
$string['availablecourses_desc'] = 'اختر المقررات التي يمكن للمستخدمين اختيارها أثناء عملية التسجيل (بحد أقصى 5).';
$string['selectcourses'] = 'اختر المقررات';
$string['maxcoursesreached'] = 'يمكنك اختيار 5 مقررات كحد أقصى.';
$string['atleastonecourse'] = 'يجب عليك اختيار مقرر واحد على الأقل.';
$string['approvecourse'] = 'الموافقة على المقرر';
$string['denycourse'] = 'رفض المقرر';
$string['approveallcourses'] = 'الموافقة على جميع المقررات';
$string['enrollmentstatus'] = 'حالة التسجيل';
$string['coursetitle'] = 'عنوان المقرر';
$string['enrollsuccess'] = 'تم تسجيل المستخدم في المقرر بنجاح.';
$string['alreadyenrolled'] = 'المستخدم مسجل بالفعل في هذا المقرر.';

$string['course1'] = 'المقرر الأول';
$string['course2'] = 'المقرر الثاني';
$string['course3'] = 'المقرر الثالث';
$string['course4'] = 'المقرر الرابع';
$string['course5'] = 'المقرر الخامس';
$string['selectacourse'] = 'اختر مقرراً';

$string['admin_comments'] = 'تعليقات المسؤول';
$string['deny_with_reason'] = 'الرفض مع السبب';
$string['email_admin_subject'] = 'تحميل هوية جديد: {$a->username}';
$string['email_admin_body'] = 'قام المستخدم {$a->username} تحميل هويته للمراجعة.\nيمكنك مراجعة الطلب هنا: {$a->url}';
$string['email_approved_subject'] = 'تمت الموافقة على التسجيل في الدورة';
$string['email_approved_body'] = 'مرحباً {$a->firstname}،\n\nلقد تمت الموافقة على تسجيلك. لقد تم تسجيلك في الدورات التالية:\n{$a->courses}\n\nتعليقات المسؤول: {$a->comments}\n\nيمكنك الآن تسجيل الدخول والوصول إلى دوراتك.';
$string['email_rejected_subject'] = 'تم رفض التسجيل';
$string['email_rejected_body'] = 'مرحباً {$a->firstname}،\n\nلسوء الحظ، تم رفض تسجيلك.\n\nتعليقات المسؤول: {$a->comments}\n\nيرجى تسجيل الدخول واتباع التعليمات لإعادة تحميل وثائقك.';
$string['email_course_approved_subject'] = 'تمت الموافقة على التسجيل في الدورة: {$a->coursename}';
$string['email_course_approved_body'] = 'مرحباً {$a->firstname}،\n\nلقد تمت الموافقة على طلبك للانضمام إلى الدورة \"{$a->coursename}\".\n\nتعليقات المسؤول: {$a->comments}\n\nيمكنك الآن الوصول إلى الدورة.';
$string['notapplicable'] = 'غير منطبق';
$string['default_approve_comment'] = 'تمت الموافقة على تسجيلك بعد مراجعتنا اليدوية.';
$string['default_deny_comment'] = 'كانت وثائقك غير كافية أو لم تتطابق مع متطلباتنا.';
$string['default_approve_course_comment'] = 'لقد تمت الموافقة على تسجيلك في هذه الدورة.';
$string['default_deny_course_comment'] = 'أنت لا تستوفي الشروط مسبقة أو المعايير المطلوبة لهذه الدورة التدريبية المحددة.';
