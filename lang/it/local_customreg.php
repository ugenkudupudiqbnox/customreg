<?php

$string['pluginname'] = 'Impostazioni del corso per la Registrazione Personalizzata';
$string['pendingapproval'] = 'La tua registrazione è in attesa di approvazione.';

$string['areyouexisting'] = 'Sei un membro esistente?';
$string['existingmember'] = "Sono un dipendente/studente esistente";
$string['newmember'] = "No, non lo sono";
$string['institutionid'] = 'ID Istituto';

$string['manageusers'] = 'Gestisci Registrazioni Personalizzate';
$string['approve'] = 'Approva';
$string['deny'] = 'Rifiuta';
$string['identitytype'] = 'Tipo di Identità';
$string['documentstatus'] = 'Stato del Documento';
$string['action'] = 'Azione';
$string['uploaded'] = 'Caricato';
$string['notuploaded'] = 'Non caricato';
$string['approved'] = 'Approvato';
$string['pending'] = 'In attesa';
$string['denied'] = 'Rifiutato';
$string['customreg:manage'] = 'Gestisci le registrazioni personalizzate';
$string['userapproved'] = 'L\'utente è stato approvato.';
$string['userdenied'] = 'La registrazione dell\'utente è stata rifiutata. L\'utente può ora caricare nuovamente il proprio documento d\'identità.';
$string['searchusers'] = 'Cerca utenti o email';
$string['uploadagain'] = 'Il tuo documento precedente non è stato accettato. Carica di nuovo una copia nitida del tuo documento d\'identità governativo.';
$string['availablecourses'] = 'Corsi disponibili per la registrazione';
$string['availablecourses_desc'] = 'Seleziona i corsi che gli utenti possono scegliere durante il processo di registrazione (max 5).';
$string['selectcourses'] = 'Seleziona Corsi';
$string['maxcoursesreached'] = 'Puoi selezionare un massimo di 5 corsi.';
$string['atleastonecourse'] = 'Devi selezionare almeno un corso.';
$string['approvecourse'] = 'Approva Corso';
$string['denycourse'] = 'Rifiuta Corso';
$string['approveallcourses'] = 'Approva tutti i corsi';
$string['enrollmentstatus'] = 'Stato dell\'iscrizione';
$string['coursetitle'] = 'Titolo del corso';
$string['enrollsuccess'] = 'Utente iscritto al corso con successo.';
$string['alreadyenrolled'] = 'L\'utente è già iscritto a questo corso.';

$string['course1'] = 'Primo corso';
$string['course2'] = 'Secondo corso';
$string['course3'] = 'Terzo corso';
$string['course4'] = 'Quarto corso';
$string['course5'] = 'Quinto corso';
$string['selectacourse'] = 'Seleziona un corso';

$string['admin_comments'] = 'Commenti dell\'amministratore';
$string['deny_with_reason'] = 'Rifiuta con motivo';
$string['email_admin_subject'] = 'Nuovo caricamento ID: {$a->username}';
$string['email_admin_body'] = 'L\'utente {$a->username} ha caricato il proprio documento d\'identità per la revisione.
$string['email_approved_subject'] = 'Iscrizione al corso approvata';
$string['email_approved_body'] = 'Buongiorno {$a->firstname},



$string['email_rejected_subject'] = 'Registrazione rifiutata';
$string['email_rejected_body'] = 'Buongiorno {$a->firstname},



$string['email_course_approved_subject'] = 'Iscrizione al corso approvata: {$a->coursename}';
$string['email_course_approved_body'] = 'Buongiorno {$a->firstname},



$string['notapplicable'] = 'N/D';
$string['default_approve_comment'] = 'La tua registrazione è stata approvata a seguito della nostra revisione manuale.';
$string['default_deny_comment'] = 'La tua documentazione era insufficiente o non corrispondeva ai nostri requisiti.';
$string['default_approve_course_comment'] = 'La tua iscrizione a questo corso è stata approvata.';
$string['default_deny_course_comment'] = 'Non soddisfi i prerequisiti o i criteri richiesti per questo corso specifico.';

$string['email_admin_body'] = 'L\'utente {$a->username} ha caricato il proprio documento d\'identità per la revisione.
$string['email_approved_body'] = 'Buongiorno {$a->firstname},



$string['email_rejected_body'] = 'Buongiorno {$a->firstname},



$string['email_course_approved_body'] = 'Buongiorno {$a->firstname},




$string['email_admin_body'] = 'L\'utente {$a->username} ha caricato il proprio documento d\'identità per la revisione.
Puoi rivedere la richiesta aqui: {$a->url}';
$string['email_approved_body'] = 'Buongiorno {$a->firstname},

la tua registrazione è stata approvata. Sei stato iscritto ai seguenti corsi:
{$a->courses}

Commenti dell\'amministratore: {$a->comments}

Ora puoi accedere e visualizzare i tuoi corsi.: {$a->sitelink}';
$string['email_rejected_body'] = 'Buongiorno {$a->firstname},

purtroppo la tua registrazione è stata rifiutata.

Commenti dell\'amministratore: {$a->comments}

Accedi e segui le istruzioni per ricaricare la tua documentazione.: {$a->uploadurl}';
$string['email_course_approved_body'] = 'Buongiorno {$a->firstname},

la tua richiesta di iscrizione al corso \"{$a->coursename}\" è stata approvata.

Commenti dell\'amministratore: {$a->comments}

Ora puoi accedere al corso.: {$a->courseurl}';