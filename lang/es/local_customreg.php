<?php

$string['pluginname'] = 'Configuración de cursos para Registro Personalizado';
$string['pendingapproval'] = 'Su registro está pendiente de aprobación.';

$string['areyouexisting'] = '¿Es usted un miembro existente?';
$string['existingmember'] = "Soy un Empleado/Estudiante existente";
$string['newmember'] = "No lo soy";
$string['institutionid'] = 'ID de la institución';

$string['manageusers'] = 'Gestionar Registros Personalizados';
$string['approve'] = 'Aprobar';
$string['deny'] = 'Denegar';
$string['identitytype'] = 'Tipo de Identidad';
$string['documentstatus'] = 'Estado del documento';
$string['action'] = 'Acción';
$string['uploaded'] = 'Subido';
$string['notuploaded'] = 'No subido';
$string['approved'] = 'Aprobado';
$string['pending'] = 'Pendiente';
$string['denied'] = 'Denegado';
$string['customreg:manage'] = 'Gestionar registros personalizados';
$string['userapproved'] = 'El usuario ha sido aprobado.';
$string['userdenied'] = 'El registro de usuario ha sido denegado. El usuario ahora puede volver a subir su identificación.';
$string['searchusers'] = 'Buscar usuarios o correo electrónico';
$string['uploadagain'] = 'Su documento anterior no fue aceptado. Por favor, vuelva a subir una copia clara de su identificación gubernamental.';
$string['availablecourses'] = 'Cursos disponibles para registro';
$string['availablecourses_desc'] = 'Seleccione los cursos que los usuarios pueden elegir durante el proceso de registro (máximo 5).';
$string['selectcourses'] = 'Seleccionar cursos';
$string['maxcoursesreached'] = 'Puede seleccionar un máximo de 5 cursos.';
$string['atleastonecourse'] = 'Debe seleccionar al menos un curso.';
$string['approvecourse'] = 'Aprobar curso';
$string['denycourse'] = 'Denegar curso';
$string['approveallcourses'] = 'Aprobar todos los cursos';
$string['enrollmentstatus'] = 'Estado de inscripción';
$string['coursetitle'] = 'Título del curso';
$string['enrollsuccess'] = 'Usuario inscrito en el curso con éxito.';
$string['alreadyenrolled'] = 'El usuario ya está inscrito en este curso.';

$string['course1'] = 'Primer curso';
$string['course2'] = 'Segundo curso';
$string['course3'] = 'Tercer curso';
$string['course4'] = 'Cuarto curso';
$string['course5'] = 'Quinto curso';
$string['selectacourse'] = 'Seleccionar un curso';




$string['admin_comments'] = 'Comentarios del administrador';
$string['deny_with_reason'] = 'Rechazar con motivo';
$string['email_admin_subject'] = 'Nueva carga de identificación: {$a->username}';
$string['email_admin_body'] = 'El usuario {$a->username} ha subido su identificación para su revisión.\nPuede revisar la solicitud aquí: {$a->url}';
$string['email_approved_subject'] = 'Inscripción al curso aprobada';
$string['email_approved_body'] = 'Hola {$a->firstname},\n\nSu registro ha sido aprobado. Ha sido inscrito en los siguientes cursos:\n{$a->courses}\n\nComentarios del administrador: {$a->comments}\n\nAhora puede iniciar sesión y acceder a sus cursos.';
$string['email_rejected_subject'] = 'Registro rechazado';
$string['email_rejected_body'] = 'Hola {$a->firstname},\n\nLamentablemente, su registro ha sido rechazado.\n\nComentarios del administrador: {$a->comments}\n\nInicie sesión y siga las instrucciones para volver a cargar su documentación.';
$string['email_course_approved_subject'] = 'Inscripción al curso aprobada: {$a->coursename}';
$string['email_course_approved_body'] = 'Hola {$a->firstname},\n\nSu solicitud para unirse al curso \"{$a->coursename}\" ha sido aprobada.\n\nComentarios del administrador: {$a->comments}\n\nPuede acceder al curso ahora.';
$string['notapplicable'] = 'N/A';
$string['default_approve_comment'] = 'Su registro ha sido aprobado después de nuestra revisión manual.';
$string['default_deny_comment'] = 'Su documentación fue insuficiente o no coincidió con nuestros requisitos.';
$string['default_approve_course_comment'] = 'Su inscripción en este curso ha sido aprobada.';
$string['default_deny_course_comment'] = 'No cumple con los prerrequisitos o criterios requeridos para este curso específico.';
