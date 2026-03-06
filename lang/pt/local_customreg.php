<?php

$string['pluginname'] = 'Configurações de curso para Registro Personalizado';
$string['pendingapproval'] = 'O seu registro está pendente de aprovação.';

$string['areyouexisting'] = 'Você já é um membro existente?';
$string['existingmember'] = "Sou funcionário/aluno existente";
$string['newmember'] = "Não sou";
$string['institutionid'] = 'ID da instituição';

$string['manageusers'] = 'Gerenciar Registros Personalizados';
$string['approve'] = 'Aprovar';
$string['deny'] = 'Negar';
$string['identitytype'] = 'Tipo de Identidade';
$string['documentstatus'] = 'Estado do documento';
$string['action'] = 'Ação';
$string['uploaded'] = 'Enviado';
$string['notuploaded'] = 'Não enviado';
$string['approved'] = 'Aprovado';
$string['pending'] = 'Pendente';
$string['denied'] = 'Negado';
$string['customreg:manage'] = 'Gerenciar registros personalizados';
$string['userapproved'] = 'O usuário foi aprovado.';
$string['userdenied'] = 'O registro do usuário foi negado. O usuário pode agora enviar a sua identidade novamente.';
$string['searchusers'] = 'Procurar usuários ou e-mail';
$string['uploadagain'] = 'Seu documento anterior não foi aceito. Por favor, envie uma cópia clara de sua identidade oficial novamente.';
$string['availablecourses'] = 'Cursos disponíveis para registro';
$string['availablecourses_desc'] = 'Selecione os cursos que os usuários podem escolher durante o processo de inscrição (máx. 5).';
$string['selectcourses'] = 'Selecionar Cursos';
$string['maxcoursesreached'] = 'Pode selecionar no máximo 5 cursos.';
$string['atleastonecourse'] = 'Deve selecionar pelo menos um curso.';
$string['approvecourse'] = 'Aprovar Curso';
$string['denycourse'] = 'Negar Curso';
$string['approveallcourses'] = 'Aprovar todos os cursos';
$string['enrollmentstatus'] = 'Estado da inscrição';
$string['coursetitle'] = 'Título do curso';
$string['enrollsuccess'] = 'Usuário inscrito no curso com sucesso.';
$string['alreadyenrolled'] = 'O usuário já está inscrito neste curso.';

$string['course1'] = 'Primeiro curso';
$string['course2'] = 'Segundo curso';
$string['course3'] = 'Terceiro curso';
$string['course4'] = 'Quarto curso';
$string['course5'] = 'Quinto curso';
$string['selectacourse'] = 'Selecione um curso';

$string['admin_comments'] = 'Comentários do administrador';
$string['deny_with_reason'] = 'Rejeitar com motivo';
$string['email_admin_subject'] = 'Novo carregamento de ID: {$a->username}';
$string['email_admin_body'] = 'O utilizador {$a->username} carregou a sua identificação para revisão.
$string['email_approved_subject'] = 'Inscrição no curso aprovada';
$string['email_approved_body'] = 'Olá {$a->firstname},



$string['email_rejected_subject'] = 'Registo rejeitado';
$string['email_rejected_body'] = 'Olá {$a->firstname},



$string['email_course_approved_subject'] = 'Inscrição no curso aprovada: {$a->coursename}';
$string['email_course_approved_body'] = 'Olá {$a->firstname},



$string['notapplicable'] = 'N/A';
$string['default_approve_comment'] = 'O seu registo foi aprovado após a nossa revisão manual.';
$string['default_deny_comment'] = 'A sua documentação foi insuficiente ou não correspondeu aos nossos requisitos.';
$string['default_approve_course_comment'] = 'A sua inscrição neste curso foi aprovada.';
$string['default_deny_course_comment'] = 'Não cumpre os pré-requisitos ou critérios necessários para este curso específico.';

$string['email_admin_body'] = 'O utilizador {$a->username} carregou a sua identificação para revisão.
$string['email_approved_body'] = 'Olá {$a->firstname},



$string['email_rejected_body'] = 'Olá {$a->firstname},



$string['email_course_approved_body'] = 'Olá {$a->firstname},




$string['email_admin_body'] = 'O utilizador {$a->username} carregou a sua identificação para revisão.
Pode rever o pedido aqui: {$a->url}';
$string['email_approved_body'] = 'Olá {$a->firstname},

O seu registo foi aprovado. Foi inscrito nos seguintes cursos:
{$a->courses}

Comentários do administrador: {$a->comments}

Pode agora iniciar sessão e aceder aos seus cursos.: {$a->sitelink}';
$string['email_rejected_body'] = 'Olá {$a->firstname},

Infelizmente, o seu registo foi rejeitado.

Comentários do administrador: {$a->comments}

Inicie sessão e siga as instruções para voltar a carregar a sua documentação.: {$a->uploadurl}';
$string['email_course_approved_body'] = 'Olá {$a->firstname},

O seu pedido para aderir ao curso \"{$a->coursename}\" foi aprovado.

Comentários do administrador: {$a->comments}

Pode agora aceder ao curso.: {$a->courseurl}';