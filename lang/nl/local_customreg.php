<?php

$string['pluginname'] = 'Instellingen voor aangepaste registratiecursussen';
$string['pendingapproval'] = 'Uw registratie is in afwachting van goedkeuring.';

$string['areyouexisting'] = 'Bent u een bestaand lid?';
$string['existingmember'] = "Ik ben een bestaande medewerker/student";
$string['newmember'] = "Nee, dat ben ik niet";
$string['institutionid'] = 'Instellings-ID';

$string['manageusers'] = 'Aangepaste registraties beheren';
$string['approve'] = 'Goedkeuren';
$string['deny'] = 'Afwijzen';
$string['identitytype'] = 'Type identiteit';
$string['documentstatus'] = 'Documentstatus';
$string['action'] = 'Actie';
$string['uploaded'] = 'Geüpload';
$string['notuploaded'] = 'Niet geüpload';
$string['approved'] = 'Goedgekeurd';
$string['pending'] = 'In afwachting';
$string['denied'] = 'Afgewezen';
$string['customreg:manage'] = 'Beheer aangepaste registraties';
$string['userapproved'] = 'Gebruiker is goedgekeurd.';
$string['userdenied'] = 'Gebruikersregistratie is afgewezen. De gebruiker kan nu zijn identiteitsbewijs opnieuw uploaden.';
$string['searchusers'] = 'Zoek naar gebruikers of e-mail';
$string['uploadagain'] = 'Uw vorige document werd niet geaccepteerd. Upload opnieuw een duidelijke kopie van uw identiteitsbewijs.';
$string['availablecourses'] = 'Beschikbare cursussen voor registratie';
$string['availablecourses_desc'] = 'Selecteer de cursussen die gebruikers kunnen kiezen tijdens het aanmeldingsproces (max. 5).';
$string['selectcourses'] = 'Selecteer cursussen';
$string['maxcoursesreached'] = 'U kunt maximaal 5 cursussen selecteren.';
$string['atleastonecourse'] = 'U moet ten minste één cursus selecteren.';
$string['approvecourse'] = 'Cursus goedkeuren';
$string['denycourse'] = 'Cursus afwijzen';
$string['approveallcourses'] = 'Alle cursussen goedkeuren';
$string['enrollmentstatus'] = 'Inschrijvingsstatus';
$string['coursetitle'] = 'Cursustitel';
$string['enrollsuccess'] = 'Gebruiker is succesvol ingeschreven voor de cursus.';
$string['alreadyenrolled'] = 'Gebruiker is al ingeschreven voor deze cursus.';

$string['course1'] = 'Eerste cursus';
$string['course2'] = 'Tweede cursus';
$string['course3'] = 'Derde cursus';
$string['course4'] = 'Vierde cursus';
$string['course5'] = 'Vijfde cursus';
$string['selectacourse'] = 'Selecteer een cursus';

$string['admin_comments'] = 'Opmerkingen beheerder';
$string['deny_with_reason'] = 'Afwijzen met reden';
$string['email_admin_subject'] = 'Nieuwe ID-upload: {$a->username}';
$string['email_admin_body'] = 'Gebruiker {$a->username} heeft zijn/haar ID geüpload ter beoordeling.
$string['email_approved_subject'] = 'Cursusinschrijving goedgekeurd';
$string['email_approved_body'] = 'Hallo {$a->firstname},



$string['email_rejected_subject'] = 'Registratie afgewezen';
$string['email_rejected_body'] = 'Hallo {$a->firstname},



$string['email_course_approved_subject'] = 'Cursusinschrijving goedgekeurd: {$a->coursename}';
$string['email_course_approved_body'] = 'Hallo {$a->firstname},



$string['notapplicable'] = 'N.v.t.';
$string['default_approve_comment'] = 'Uw registratie is goedgekeurd na onze handmatige controle.';
$string['default_deny_comment'] = 'Uw documentatie was onvoldoende of voldeed niet aan onze vereisten.';
$string['default_approve_course_comment'] = 'Uw inschrijving voor deze cursus is goedgekeurd.';
$string['default_deny_course_comment'] = 'U voldoet niet aan de instapeisen of vereiste criteria voor deze specifieke cursus.';

$string['email_admin_body'] = 'Gebruiker {$a->username} heeft zijn/haar ID geüpload ter beoordeling.
$string['email_approved_body'] = 'Hallo {$a->firstname},



$string['email_rejected_body'] = 'Hallo {$a->firstname},



$string['email_course_approved_body'] = 'Hallo {$a->firstname},




$string['email_admin_body'] = 'Gebruiker {$a->username} heeft zijn/haar ID geüpload ter beoordeling.
U kunt het verzoek hier bekijken: {$a->url}';
$string['email_approved_body'] = 'Hallo {$a->firstname},

Uw registratie is goedgekeurd. U bent ingeschreven voor de volgende cursussen:
{$a->courses}

Opmerkingen beheerder: {$a->comments}

U kunt nu inloggen en uw cursussen bekijken.: {$a->sitelink}';
$string['email_rejected_body'] = 'Hallo {$a->firstname},

Helaas is uw registratie afgewezen.

Opmerkingen beheerder: {$a->comments}

Log in en volg de instructies om uw documentatie opnieuw te uploaden.: {$a->uploadurl}';
$string['email_course_approved_body'] = 'Hallo {$a->firstname},

Uw verzoek om deel te nemen aan de cursus \"{$a->coursename}\" is goedgekeurd.

Opmerkingen beheerder: {$a->comments}

U hebt nu toegang tot de cursus.: {$a->courseurl}';