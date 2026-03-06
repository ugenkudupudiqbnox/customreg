<?php

$string['pluginname'] = 'Özel Kayıt için Kurs Ayarları';
$string['pendingapproval'] = 'Kaydınız onay bekliyor.';

$string['areyouexisting'] = 'Mevcut bir üye misiniz?';
$string['existingmember'] = "Mevcut Çalışan/Öğrenciyim";
$string['newmember'] = "Değilim";
$string['institutionid'] = 'Kurum ID';

$string['manageusers'] = 'Özel Kayıtları Yönet';
$string['approve'] = 'Onayla';
$string['deny'] = 'Reddet';
$string['identitytype'] = 'Kimlik Türü';
$string['documentstatus'] = 'Belge Durumu';
$string['action'] = 'İşlem';
$string['uploaded'] = 'Yüklendi';
$string['notuploaded'] = 'Yüklenmedi';
$string['approved'] = 'Onaylandı';
$string['pending'] = 'Beklemede';
$string['denied'] = 'Reddedildi';
$string['customreg:manage'] = 'Özel kayıtları yönetin';
$string['userapproved'] = 'Kullanıcı onaylandı.';
$string['userdenied'] = 'Kullanıcı kaydı reddedildi. Kullanıcı şimdi kimliğini tekrar yükleyebilir.';
$string['searchusers'] = 'Kullanıcıları veya e-postaları ara';
$string['uploadagain'] = 'Önceki belgeniz kabul edilmedi. Lütfen resmi kimliğinizin net bir kopyasını tekrar yükleyin.';
$string['availablecourses'] = 'Kayıt için Mevcut Kurslar';
$string['availablecourses_desc'] = 'Kullanıcıların kayıt işlemi sırasında seçebileceği kursları seçin (en fazla 5).';
$string['selectcourses'] = 'Kursları Seçin';
$string['maxcoursesreached'] = 'En fazla 5 kurs seçebilirsiniz.';
$string['atleastonecourse'] = 'En az bir kurs seçmelisiniz.';
$string['approvecourse'] = 'Kursu Onayla';
$string['denycourse'] = 'Kursu Reddet';
$string['approveallcourses'] = 'Tüm Kursları Onayla';
$string['enrollmentstatus'] = 'Kayıt Durumu';
$string['coursetitle'] = 'Kurs Adı';
$string['enrollsuccess'] = 'Kullanıcı kursa başarıyla kaydedildi.';
$string['alreadyenrolled'] = 'Kullanıcı zaten bu kursa kayıtlı.';

$string['course1'] = 'Birinci Kurs';
$string['course2'] = 'İkinci Kurs';
$string['course3'] = 'Üçüncü Kurs';
$string['course4'] = 'Dördüncü Kurs';
$string['course5'] = 'Beşinci Kurs';
$string['selectacourse'] = 'Bir kurs seçin';

$string['admin_comments'] = 'Yönetici Yorumları';
$string['deny_with_reason'] = 'Nedeniyle Reddet';
$string['email_admin_subject'] = 'Yeni Kimlik Yükleme: {$a->username}';
$string['email_admin_body'] = 'Kullanıcı {$a->username}, inceleme için kimliğini yükledi.
$string['email_approved_subject'] = 'Kurs Kaydı Onaylandı';
$string['email_approved_body'] = 'Merhaba {$a->firstname},



$string['email_rejected_subject'] = 'Kayıt Reddedildi';
$string['email_rejected_body'] = 'Merhaba {$a->firstname},



$string['email_course_approved_subject'] = 'Kurs Kaydı Onaylandı: {$a->coursename}';
$string['email_course_approved_body'] = 'Merhaba {$a->firstname},



$string['notapplicable'] = 'N/A';
$string['default_approve_comment'] = 'Manuel incelememizin ardından kaydınız onaylandı.';
$string['default_deny_comment'] = 'Belgeleriniz yetersizdi veya gereksinimlerimize uymuyordu.';
$string['default_approve_course_comment'] = 'Bu kursa kaydınız onaylandı.';
$string['default_deny_course_comment'] = 'Bu özel kurs için gereken ön koşulları veya kriterleri karşılamıyorsunuz.';

$string['email_admin_body'] = 'Kullanıcı {$a->username}, inceleme için kimliğini yükledi.
$string['email_approved_body'] = 'Merhaba {$a->firstname},



$string['email_rejected_body'] = 'Merhaba {$a->firstname},



$string['email_course_approved_body'] = 'Merhaba {$a->firstname},




$string['email_admin_body'] = 'Kullanıcı {$a->username}, inceleme için kimliğini yükledi.
Talebi buradan inceleyebilirsiniz: {$a->url}';
$string['email_approved_body'] = 'Merhaba {$a->firstname},

Kaydınız onaylandı. Aşağıdaki kurslara kaydedildiniz:
{$a->courses}

Yönetici Yorumları: {$a->comments}

Artık giriş yapabilir ve kurslarınıza erişebilirsiniz.: {$a->sitelink}';
$string['email_rejected_body'] = 'Merhaba {$a->firstname},

Maalesef kaydınız reddedildi.

Yönetici Yorumları: {$a->comments}

Lütfen giriş yapın ve belgelerinizi yeniden yüklemek için talimatları izleyin.: {$a->uploadurl}';
$string['email_course_approved_body'] = 'Merhaba {$a->firstname},

\"{$a->coursename}\" kursuna katılma talebiniz onaylandı.

Yönetici Yorumları: {$a->comments}

Artık kursa erişebilirsiniz.: {$a->courseurl}';