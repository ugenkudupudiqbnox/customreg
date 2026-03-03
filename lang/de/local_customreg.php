<?php

$string['pluginname'] = 'Kurseinstellungen für benutzerdefinierte Registrierung';
$string['pendingapproval'] = 'Deine Registrierung wartet auf Genehmigung.';

$string['areyouexisting'] = 'Bist du bereits Mitglied?';
$string['existingmember'] = "Ich bin ein bestehender Mitarbeiter/Student";
$string['newmember'] = "Nein, bin ich nicht";
$string['institutionid'] = 'Einrichtungs-ID';

$string['manageusers'] = 'Benutzerdefinierte Registrierungen verwalten';
$string['approve'] = 'Genehmigen';
$string['deny'] = 'Ablehnen';
$string['identitytype'] = 'Identitätstyp';
$string['documentstatus'] = 'Dokumentenstatus';
$string['action'] = 'Aktion';
$string['uploaded'] = 'Hochgeladen';
$string['notuploaded'] = 'Nicht hochgeladen';
$string['approved'] = 'Genehmigt';
$string['pending'] = 'Ausstehend';
$string['denied'] = 'Abgelehnt';
$string['customreg:manage'] = 'Benutzerdefinierte Registrierungen verwalten';
$string['userapproved'] = 'Benutzer wurde genehmigt.';
$string['userdenied'] = 'Benutzerregistrierung wurde abgelehnt. Der Benutzer kann jetzt seine ID erneut hochladen.';
$string['searchusers'] = 'Benutzer oder E-Mail suchen';
$string['uploadagain'] = 'Dein vorheriges Dokument wurde nicht akzeptiert. Bitte lade erneut eine klare Kopie deines amtlichen Lichtbildausweises hoch.';
$string['availablecourses'] = 'Verfügbare Kurse für die Registrierung';
$string['availablecourses_desc'] = 'Wähle die Kurse aus, die Benutzer während des Anmeldevorgangs wählen können (max. 5).';
$string['selectcourses'] = 'Kurse auswählen';
$string['maxcoursesreached'] = 'Du kannst maximal 5 Kurse auswählen.';
$string['atleastonecourse'] = 'Du musst mindestens einen Kurs auswählen.';
$string['approvecourse'] = 'Kurs genehmigen';
$string['denycourse'] = 'Kurs ablehnen';
$string['approveallcourses'] = 'Alle Kurse genehmigen';
$string['enrollmentstatus'] = 'Einschreibestatus';
$string['coursetitle'] = 'Kurstitel';
$string['enrollsuccess'] = 'Benutzer wurde erfolgreich in den Kurs eingeschrieben.';
$string['alreadyenrolled'] = 'Benutzer ist bereits in diesem Kurs eingeschrieben.';

$string['course1'] = 'Erster Kurs';
$string['course2'] = 'Zweiter Kurs';
$string['course3'] = 'Dritter Kurs';
$string['course4'] = 'Vierter Kurs';
$string['course5'] = 'Fünfter Kurs';
$string['selectacourse'] = 'Kurs auswählen';

$string['admin_comments'] = 'Administrator-Kommentare';
$string['deny_with_reason'] = 'Mit Begründung ablehnen';
$string['email_admin_subject'] = 'Neuer ID-Upload: {$a->username}';
$string['email_admin_body'] = 'Der Benutzer {$a->username} hat seine ID zur Überprüfung hochgeladen.\nSie können die Anfrage hier überprüfen: {$a->url}';
$string['email_approved_subject'] = 'Kurseinschreibung genehmigt';
$string['email_approved_body'] = 'Hallo {$a->firstname},\n\nIhre Registrierung wurde genehmigt. Sie wurden für die folgenden Kurse eingeschrieben:\n{$a->courses}\n\nAdministrator-Kommentare: {$a->comments}\n\nSie können sich nun einloggen und auf Ihre Kurse zugreifen.';
$string['email_rejected_subject'] = 'Registrierung abgelehnt';
$string['email_rejected_body'] = 'Hallo {$a->firstname},\n\nleider wurde Ihre Registrierung abgelehnt.\n\nAdministrator-Kommentare: {$a->comments}\n\nBitte loggen Sie sich ein und folgen Sie den Anweisungen, um Ihre Unterlagen erneut hochzuladen.';
$string['email_course_approved_subject'] = 'Kurseinschreibung genehmigt: {$a->coursename}';
$string['email_course_approved_body'] = 'Hallo {$a->firstname},\n\nIhre Anfrage zur Teilnahme am Kurs \"{$a->coursename}\" wurde genehmigt.\n\nAdministrator-Kommentare: {$a->comments}\n\nSie können nun auf den Kurs zugreifen.';
$string['notapplicable'] = 'N/A';
$string['default_approve_comment'] = 'Ihre Registrierung wurde nach unserer manuellen Prüfung genehmigt.';
$string['default_deny_comment'] = 'Ihre Dokumentation war unzureichend oder entsprach nicht unseren Anforderungen.';
$string['default_approve_course_comment'] = 'Ihre Einschreibung in diesen Kurs wurde genehmigt.';
$string['default_deny_course_comment'] = 'Sie erfüllen nicht die Voraussetzungen oder erforderlichen Kriterien für diesen spezifischen Kurs.';
