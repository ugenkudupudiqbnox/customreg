<?php

$string['pluginname'] = 'Paramètres de cours pour inscription personnalisée';
$string['pendingapproval'] = 'Votre inscription est en attente d\'approbation.';

$string['areyouexisting'] = 'Êtes-vous déjà membre ?';
$string['existingmember'] = "Je suis un employé/étudiant existant";
$string['newmember'] = "Je ne le suis pas";
$string['institutionid'] = 'ID de l\'institution';

$string['manageusers'] = 'Gérer les inscriptions personnalisées';
$string['approve'] = 'Approuver';
$string['deny'] = 'Refuser';
$string['identitytype'] = 'Type d\'identité';
$string['documentstatus'] = 'Statut du document';
$string['action'] = 'Action';
$string['uploaded'] = 'Téléversé';
$string['notuploaded'] = 'Non téléversé';
$string['approved'] = 'Approuvé';
$string['pending'] = 'En attente';
$string['denied'] = 'Refusé';
$string['customreg:manage'] = 'Gérer les inscriptions personnalisées';
$string['userapproved'] = 'L\'utilisateur a été approuvé.';
$string['userdenied'] = 'L\'inscription de l\'utilisateur a été refusée. L\'utilisateur peut maintenant à nouveau téléverser sa pièce d\'identité.';
$string['searchusers'] = 'Rechercher des utilisateurs ou des courriels';
$string['uploadagain'] = 'Votre document précédent n\'a pas été accepté. Veuillez recommencer le téléversement d\'une copie claire de votre pièce d\'identité officielle.';
$string['availablecourses'] = 'Cursos disponibles para inscripción';
$string['availablecourses_desc'] = 'Sélectionnez les cours que les utilisateurs peuvent choisir lors du processus d\'inscription (max 5).';
$string['selectcourses'] = 'Sélectionner les cours';
$string['maxcoursesreached'] = 'Vous pouvez sélectionner au maximum 5 cours.';
$string['atleastonecourse'] = 'Vous devez sélectionner au moins un cours.';
$string['approvecourse'] = 'Approuver le cours';
$string['denycourse'] = 'Refuser le cours';
$string['approveallcourses'] = 'Approuver tous les cours';
$string['enrollmentstatus'] = 'Statut d\'inscription';
$string['coursetitle'] = 'Titre du cours';
$string['enrollsuccess'] = 'L\'utilisateur a été inscrit au cours avec succès.';
$string['alreadyenrolled'] = 'L\'utilisateur est déjà inscrit à ce cours.';

$string['course1'] = 'Premier cours';
$string['course2'] = 'Deuxième cours';
$string['course3'] = 'Troisième cours';
$string['course4'] = 'Quatrième cours';
$string['course5'] = 'Cinquième cours';
$string['selectacourse'] = 'Sélectionner un cours';

$string['admin_comments'] = 'Commentaires de l\'administrateur';
$string['deny_with_reason'] = 'Refuser avec motif';
$string['email_admin_subject'] = 'Nouveau téléchargement de pièce d\'identité : {$a->username}';
$string['email_admin_body'] = 'L\'utilisateur {$a->username} a téléchargé sa pièce d\'identité pour examen.
$string['email_approved_subject'] = 'Inscription au cours approuvée';
$string['email_approved_body'] = 'Bonjour {$a->firstname},



$string['email_rejected_subject'] = 'Inscription rejetée';
$string['email_rejected_body'] = 'Bonjour {$a->firstname},



$string['email_course_approved_subject'] = 'Inscription au cours approuvée : {$a->coursename}';
$string['email_course_approved_body'] = 'Bonjour {$a->firstname},



$string['notapplicable'] = 'N/A';
$string['default_approve_comment'] = 'Votre inscription a été approuvée suite à notre examen manuel.';
$string['default_deny_comment'] = 'Votre documentation était insuffisante ou ne correspondait pas à nos exigences.';
$string['default_approve_course_comment'] = 'Votre inscription à ce cours a été approuvée.';
$string['default_deny_course_comment'] = 'Vous ne remplissez pas les conditions préalables ou les critères requis pour ce cours spécifique.';

$string['email_admin_body'] = 'L\'utilisateur {$a->username} a téléchargé sa pièce d\'identité pour examen.
$string['email_approved_body'] = 'Bonjour {$a->firstname},



$string['email_rejected_body'] = 'Bonjour {$a->firstname},



$string['email_course_approved_body'] = 'Bonjour {$a->firstname},




$string['email_admin_body'] = 'L\'utilisateur {$a->username} a téléchargé sa pièce d\'identité pour examen.
Vous pouvez consulter la demande ici : {$a->url}';
$string['email_approved_body'] = 'Bonjour {$a->firstname},

Votre inscription a été approuvée. Vous avez été inscrit aux cours suivants :
{$a->courses}

Commentaires de l\'administrateur : {$a->comments}

Vous pouvez maintenant vous connecter et acceder a vos cours.: {$a->sitelink}';
$string['email_rejected_body'] = 'Bonjour {$a->firstname},

Malheureusement, votre inscription a été rejetée.

Commentaires de l\'administrateur : {$a->comments}

Veuillez vous connecter et suivre les instructions pour télécharger à nouveau vos documents.: {$a->uploadurl}';
$string['email_course_approved_body'] = 'Bonjour {$a->firstname},

Votre demande d\'inscription au cours \"{$a->coursename}\" a été approuvée.

Commentaires de l\'administrateur : {$a->comments}

Vous pouvez maintenant accéder au cours.: {$a->courseurl}';