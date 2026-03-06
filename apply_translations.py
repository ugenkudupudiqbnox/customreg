import os
import re

languages = ["ar", "bn", "de", "es", "fr", "gu", "hi", "id", "it", "ja", "kn", "ko", "ml", "mr", "nl", "or", "pa", "pt", "ru", "ta", "te", "tr", "ur", "zh_cn"]

translations = {
    "ar": {
        "admin_comments": "تعليقات المسؤول",
        "deny_with_reason": "الرفض مع السبب",
        "email_admin_subject": "تحميل هوية جديد: {$a->username}",
        "email_admin_body": "قام المستخدم {$a->username} تحميل هويته للمراجعة.\\nيمكنك مراجعة الطلب هنا: {$a->url}",
        "email_approved_subject": "تمت الموافقة على التسجيل في الدورة",
        "email_approved_body": "مرحباً {$a->firstname}،\\n\\nلقد تمت الموافقة على تسجيلك. لقد تم تسجيلك في الدورات التالية:\\n{$a->courses}\\n\\nتعليقات المسؤول: {$a->comments}\\n\\nيمكنك الآن تسجيل الدخول والوصول إلى دوراتك.: {$a->sitelink}",
        "email_rejected_subject": "تم رفض التسجيل",
        "email_rejected_body": "مرحباً {$a->firstname}،\\n\\nلسوء الحظ، تم رفض تسجيلك.\\n\\nتعليقات المسؤول: {$a->comments}\\n\\nيرجى تسجيل الدخول واتباع التعليمات لإعادة تحميل وثائقك.: {$a->uploadurl}",
        "email_course_approved_subject": "تمت الموافقة على التسجيل في الدورة: {$a->coursename}",
        "email_course_approved_body": "مرحباً {$a->firstname}،\\n\\nلقد تمت الموافقة على طلبك للانضمام إلى الدورة \\\"{$a->coursename}\\\".\\n\\nتعليقات المسؤول: {$a->comments}\\n\\nيمكنك الآن الوصول إلى الدورة.: {$a->courseurl}",
        "notapplicable": "غير منطبق",
        "default_approve_comment": "تمت الموافقة على تسجيلك بعد مراجعتنا اليدوية.",
        "default_deny_comment": "كانت وثائقك غير كافية أو لم تتطابق مع متطلباتنا.",
        "default_approve_course_comment": "لقد تمت الموافقة على تسجيلك في هذه الدورة.",
        "default_deny_course_comment": "أنت لا تستوفي الشروط مسبقة أو المعايير المطلوبة لهذه الدورة التدريبية المحددة."
    },
    "bn": {
        "admin_comments": "অ্যাডমিন মন্তব্য",
        "deny_with_reason": "কারণসহ প্রত্যাখ্যান করুন",
        "email_admin_subject": "নতুন আইডি আপলোড: {$a->username}",
        "email_admin_body": "ব্যবহারকারী {$a->username} পর্যালোচনার জন্য তাদের আইডি আপলোড করেছেন।\\nআপনি এখানে অনুরোধটি পর্যালোচনা করতে পারেন: {$a->url}",
        "email_approved_subject": "কোর্স এনরোলমেন্ট অনুমোদিত",
        "email_approved_body": "হ্যালো {$a->firstname},\\n\\nআপনার নিবন্ধন অনুমোদিত হয়েছে। আপনি নিম্নলিখিত কোর্সগুলিতে নথিভুক্ত হয়েছেন:\\n{$a->courses}\\n\\nঅ্যাডমিন মন্তব্য: {$a->comments}\\n\\nআপনি এখন লগ ইন করতে এবং আপনার কোর্সগুলি অ্যাক্সেস করতে পারেন।: {$a->sitelink}",
        "email_rejected_subject": "নিবন্ধন প্রত্যাখ্যাত",
        "email_rejected_body": "হ্যালো {$a->firstname},\\n\\nদুর্भाग्यবশত, আপনার নিবন্ধন প্রত্যাখ্যাত হয়েছে।\\n\\nঅ্যাডমিন মন্তব্য: {$a->comments}\\n\\nঅনুগ্রহ করে লগ ইন করুন এবং আপনার ডকুমেন্টেশন পুনরায় আপলোড করার নির্দেশাবলী অনুসরণ করুন।: {$a->uploadurl}",
        "email_course_approved_subject": "কোর্স এনরোলমেন্ট অনুমোদিত: {$a->coursename}",
        "email_course_approved_body": "হ্যালো {$a->firstname},\\n\\n\\\"{$a->coursename}\\\" কোর্সে যোগদানের আপনার অনুরোধ অনুমোদিত হয়েছে।\\n\\nঅ্যাডমিন মন্তব্য: {$a->comments}\\n\\nআপনি এখন কোর্সটি অ্যাক্সেস করতে পারেন।: {$a->courseurl}",
        "notapplicable": "প্রযোজ্য নয়",
        "default_approve_comment": "আমাদের ম্যানুয়াল পর্যালোচনার পরে আপনার নিবন্ধন অনুমোদিত হয়েছে।",
        "default_deny_comment": "আপনার ডকুমেন্টেশন অপর্যাপ্ত ছিল বা আমাদের প্রয়োজনীয়তার সাথে মেলেনি।",
        "default_approve_course_comment": "এই কোর্সে আপনার এনরোলমেন্ট অনুমোদিত হয়েছে।",
        "default_deny_course_comment": "আপনি এই নির্দিষ্ট কোর্সের জন্য পূর্বশর্ত বা প্রয়োজনীয় মানদণ্ড পূরণ করেন না। "
    },
    "de": {
        "admin_comments": "Administrator-Kommentare",
        "deny_with_reason": "Mit Begründung ablehnen",
        "email_admin_subject": "Neuer ID-Upload: {$a->username}",
        "email_admin_body": "Der Benutzer {$a->username} hat seine ID zur Überprüfung hochgeladen.\\nSie können die Anfrage hier überprüfen: {$a->url}",
        "email_approved_subject": "Kurseinschreibung genehmigt",
        "email_approved_body": "Hallo {$a->firstname},\\n\\nIhre Registrierung wurde genehmigt. Sie wurden für die folgenden Kurse eingeschrieben:\\n{$a->courses}\\n\\nAdministrator-Kommentare: {$a->comments}\\n\\nSie können sich nun einloggen und auf Ihre Kurse zugreifen.: {$a->sitelink}",
        "email_rejected_subject": "Registrierung abgelehnt",
        "email_rejected_body": "Hallo {$a->firstname},\\n\\nleider wurde Ihre Registrierung abgelehnt.\\n\\nAdministrator-Kommentare: {$a->comments}\\n\\nBitte loggen Sie sich ein und folgen Sie den Anweisungen, um Ihre Unterlagen erneut hochzuladen.: {$a->uploadurl}",
        "email_course_approved_subject": "Kurseinschreibung genehmigt: {$a->coursename}",
        "email_course_approved_body": "Hallo {$a->firstname},\\n\\nIhre Anfrage zur Teilnahme am Kurs \\\"{$a->coursename}\\\" wurde genehmigt.\\n\\nAdministrator-Kommentare: {$a->comments}\\n\\nSie können nun auf den Kurs zugreifen.: {$a->courseurl}",
        "notapplicable": "N/A",
        "default_approve_comment": "Ihre Registrierung wurde nach unserer manuellen Prüfung genehmigt.",
        "default_deny_comment": "Ihre Dokumentation war unzureichend oder entsprach nicht unseren Anforderungen.",
        "default_approve_course_comment": "Ihre Einschreibung in diesen Kurs wurde genehmigt.",
        "default_deny_course_comment": "Sie erfüllen nicht die Voraussetzungen oder erforderlichen Kriterien für diesen spezifischen Kurs."
    },
    "es": {
        "admin_comments": "Comentarios del administrador",
        "deny_with_reason": "Rechazar con motivo",
        "email_admin_subject": "Nueva carga de identificación: {$a->username}",
        "email_admin_body": "El usuario {$a->username} ha subido su identificación para su revisión.\\nPuede revisar la solicitud aquí: {$a->url}",
        "email_approved_subject": "Inscripción al curso aprobada",
        "email_approved_body": "Hola {$a->firstname},\\n\\nSu registro ha sido aprobado. Ha sido inscrito en los siguientes cursos:\\n{$a->courses}\\n\\nComentarios del administrador: {$a->comments}\\n\\nAhora puede iniciar sesión y acceder a sus cursos.: {$a->sitelink}",
        "email_rejected_subject": "Registro rechazado",
        "email_rejected_body": "Hola {$a->firstname},\\n\\nLamentablemente, su registro ha sido rechazado.\\n\\nComentarios del administrador: {$a->comments}\\n\\nInicie sesión y siga las instrucciones para volver a cargar su documentación.: {$a->uploadurl}",
        "email_course_approved_subject": "Inscripción al curso aprobada: {$a->coursename}",
        "email_course_approved_body": "Hola {$a->firstname},\\n\\nSu solicitud para unirse al curso \\\"{$a->coursename}\\\" ha sido aprobada.\\n\\nComentarios del administrador: {$a->comments}\\n\\nPuede acceder al curso ahora.: {$a->courseurl}",
        "notapplicable": "N/A",
        "default_approve_comment": "Su registro ha sido aprobado después de nuestra revisión manual.",
        "default_deny_comment": "Su documentación fue insuficiente o no coincidió con nuestros requisitos.",
        "default_approve_course_comment": "Su inscripción en este curso ha sido aprobada.",
        "default_deny_course_comment": "No cumple con los prerrequisitos o criterios requeridos para este curso específico."
    },
    "fr": {
        "admin_comments": "Commentaires de l'administrateur",
        "deny_with_reason": "Refuser avec motif",
        "email_admin_subject": "Nouveau téléchargement de pièce d'identité : {$a->username}",
        "email_admin_body": "L'utilisateur {$a->username} a téléchargé sa pièce d'identité pour examen.\\nVous pouvez consulter la demande ici : {$a->url}",
        "email_approved_subject": "Inscription au cours approuvée",
        "email_approved_body": "Bonjour {$a->firstname},\\n\\nVotre inscription a été approuvée. Vous avez été inscrit aux cours suivants :\\n{$a->courses}\\n\\nCommentaires de l'administrateur : {$a->comments}\\n\\nVous pouvez maintenant vous connecter et acceder a vos cours.: {$a->sitelink}",
        "email_rejected_subject": "Inscription rejetée",
        "email_rejected_body": "Bonjour {$a->firstname},\\n\\nMalheureusement, votre inscription a été rejetée.\\n\\nCommentaires de l'administrateur : {$a->comments}\\n\\nVeuillez vous connecter et suivre les instructions pour télécharger à nouveau vos documents.: {$a->uploadurl}",
        "email_course_approved_subject": "Inscription au cours approuvée : {$a->coursename}",
        "email_course_approved_body": "Bonjour {$a->firstname},\\n\\nVotre demande d'inscription au cours \\\"{$a->coursename}\\\" a été approuvée.\\n\\nCommentaires de l'administrateur : {$a->comments}\\n\\nVous pouvez maintenant accéder au cours.: {$a->courseurl}",
        "notapplicable": "N/A",
        "default_approve_comment": "Votre inscription a été approuvée suite à notre examen manuel.",
        "default_deny_comment": "Votre documentation était insuffisante ou ne correspondait pas à nos exigences.",
        "default_approve_course_comment": "Votre inscription à ce cours a été approuvée.",
        "default_deny_course_comment": "Vous ne remplissez pas les conditions préalables ou les critères requis pour ce cours spécifique."
    },
    "gu": {
        "admin_comments": "એડમિન ટિપ્પણીઓ",
        "deny_with_reason": "કારણ સાથે નકારો",
        "email_admin_subject": "નવી આઈડી અપલોડ: {$a->username}",
        "email_admin_body": "વપરાશકર્તા {$a->username} એ સમીક્ષા માટે તેમનું આઈડી અપલોડ કર્યું છે.\\nતમે અહીં વિનંતીની સમીક્ષા કરી શકો છો: {$a->url}",
        "email_approved_subject": "કોર્સ એનરોલમેન્ટ મંજૂર",
        "email_approved_body": "હેલો {$a->firstname},\\n\\nતમારી નોંધણી મંજૂર કરવામાં આવી છે. તમે નીચેના અભ્યાસક્રમોમાં નોંધણી કરાવી છે:\\n{$a->courses}\\n\\nએડમિન ટિપ્પણીઓ: {$a->comments}\\n\\nતમે હવે લોગ ઇન કરી શકો છો અને તમારા અભ્યાસક્રમો જોઈ શકો છો.: {$a->sitelink}",
        "email_rejected_subject": "નોંધણી નકારવામાં આવી",
        "email_rejected_body": "હેલો {$a->firstname},\\n\\nદુર્ભાગ્યવશ, તમારી નોંધણી નકારવામાં આવી છે.\\n\\nએડમિન ટિપ્પણીઓ: {$a->comments}\\n\\nકૃપા કરીને લોગ ઇન કરો અને તમારા દસ્તાવેજો ફરીથી અપલોડ કરવા માટે સૂચનાઓનું પાલન કરો.: {$a->uploadurl}",
        "email_course_approved_subject": "કોર્સ એનરોલમેન્ટ મંજૂર: {$a->coursename}",
        "email_course_approved_body": "હેલો {$a->firstname},\\n\\n\\\"{$a->coursename}\\\" કોર્સમાં જોડાવાની તમારી વિનંતી મંજૂર કરવામાં આવી છે.\\n\\nએડમિન ટિપ્પણીઓ: {$a->comments}\\n\\nતમે હવે કોર્સ જોઈ શકો છો.: {$a->courseurl}",
        "notapplicable": "લાગુ પડતું નથી",
        "default_approve_comment": "અમારી મેન્યુઅલ સમીક્ષા પછી તમારી નોંધણી મંજૂર કરવામાં આવી છે.",
        "default_deny_comment": "તમારા દસ્તાવેજો અપૂરતા હતા અથવા અમારી જરૂરિયાતો સાથે મેળ ખાતા ન હતા.",
        "default_approve_course_comment": "આ કોર્સમાં તમારી નોંધણી મંજૂર કરવામાં આવી છે.",
        "default_deny_course_comment": "તમે આ વિશિષ્ટ કોર્સ માટે આવશ્યક પૂર્વશરતો અથવા માપદંડોને પૂરા કરતા નથી."
    },
    "hi": {
        "admin_comments": "व्यवस्थापक टिप्पणियाँ",
        "deny_with_reason": "कारण सहित अस्वीकार करें",
        "email_admin_subject": "नया आईडी अपलोड: {$a->username}",
        "email_admin_body": "उपयोगकर्ता {$a->username} ने समीक्षा के लिए अपना आईडी अपलोड किया है।\\nआप यहां अनुरोध की समीक्षा कर सकते हैं: {$a->url}",
        "email_approved_subject": "पाठ्यक्रम नामांकन स्वीकृत",
        "email_approved_body": "नमस्ते {$a->firstname},\\n\\nआपका पंजीकरण स्वीकृत हो गया है। आपको निम्नलिखित पाठ्यक्रमों में नामांकित किया गया है:\\n{$a->courses}\\n\\nव्यवस्थापक टिप्पणियाँ: {$a->comments}\\n\\nअब आप लॉग इन कर सकते हैं और अपने पाठ्यक्रमों तक पहुँच सकते हैं।: {$a->sitelink}",
        "email_rejected_subject": "पंजीकरण अस्वीकृत",
        "email_rejected_body": "नमस्ते {$a->firstname},\\n\\nदुर्भाग्य से, आपका पंजीकरण अस्वीकार कर दिया गया है।\\n\\nव्यवस्थापक टिप्पणियाँ: {$a->comments}\\n\\nकृपया लॉग इन करें और अपने दस्तावेज़ों को फिर से अपलोड करने के लिए निर्देशों का पालन करें।: {$a->uploadurl}",
        "email_course_approved_subject": "पाठ्यक्रम नामांकन स्वीकृत: {$a->coursename}",
        "email_course_approved_body": "नमस्ते {$a->firstname},\\n\\nपाठ्यक्रम \\\"{$a->coursename}\\\" में शामिल होने का आपका अनुरोध स्वीकार कर लिया गया है।\\n\\nव्यवस्थापक टिप्पणियाँ: {$a->comments}\\n\\nअब आप पाठ्यक्रम तक पहुँच सकते हैं।: {$a->courseurl}",
        "notapplicable": "लागु नहीं",
        "default_approve_comment": "हमारी मैन्युअल समीक्षा के बाद आपका पंजीकरण स्वीकृत कर दिया गया है।",
        "default_deny_comment": "आपके दस्तावेज़ अपर्याप्त थे या हमारी आवश्यकताओं से मेल नहीं खाते थे।",
        "default_approve_course_comment": "इस पाठ्यक्रम में आपका नामांकन स्वीकृत कर दिया गया है।",
        "default_deny_course_comment": "आप इस विशिष्ट पाठ्यक्रम के लिए आवश्यक पूर्व-आवश्यकताओं या मानदंडों को पूरा नहीं करते हैं।"
    },
    "id": {
        "admin_comments": "Komentar Admin",
        "deny_with_reason": "Tolak dengan Alasan",
        "email_admin_subject": "Unggahan ID Baru: {$a->username}",
        "email_admin_body": "Pengguna {$a->username} telah ununggah ID mereka untuk ditinjau.\\nAnda dapat meninjau permintaan di sini: {$a->url}",
        "email_approved_subject": "Pendaftaran Kursus Disetujui",
        "email_approved_body": "Halo {$a->firstname},\\n\\nPendaftaran Anda telah disetujui. Anda telah terdaftar di kursus berikut:\\n{$a->courses}\\n\\nKomentar Admin: {$a->comments}\\n\\nSekarang Anda dapat masuk dan mengakses kursus Anda.: {$a->sitelink}",
        "email_rejected_subject": "Pendaftaran Ditolak",
        "email_rejected_body": "Halo {$a->firstname},\\n\\nSayangnya, pendaftaran Anda telah ditolak.\\n\\nKomentar Admin: {$a->comments}\\n\\nSilakan masuk dan ikuti instruksi untuk mengunggah ulang dokumentasi Anda.: {$a->uploadurl}",
        "email_course_approved_subject": "Pendaftaran Kursus Disetujui: {$a->coursename}",
        "email_course_approved_body": "Halo {$a->firstname},\\n\\nPermintaan Anda untuk bergabung dengan kursus \\\"{$a->coursename}\\\" telah disetujui.\\n\\nKomentar Admin: {$a->comments}\\n\\nSekarang Anda dapat mengakses kursus tersebut.: {$a->courseurl}",
        "notapplicable": "N/A",
        "default_approve_comment": "Pendaftaran Anda telah disetujui menyusul tinjauan manual kami.",
        "default_deny_comment": "Dokumentasi Anda tidak memadai atau tidak sesuai dengan persyaratan kami.",
        "default_approve_course_comment": "Pendaftaran Anda di kursus ini telah disetujui.",
        "default_deny_course_comment": "Anda tidak memenuhi prasyarat atau kriteria yang diperlukan untuk kursus khusus ini."
    },
    "it": {
        "admin_comments": "Commenti dell'amministratore",
        "deny_with_reason": "Rifiuta con motivo",
        "email_admin_subject": "Nuovo caricamento ID: {$a->username}",
        "email_admin_body": "L'utente {$a->username} ha caricato il proprio documento d'identità per la revisione.\\nPuoi rivedere la richiesta aqui: {$a->url}",
        "email_approved_subject": "Iscrizione al corso approvata",
        "email_approved_body": "Buongiorno {$a->firstname},\\n\\nla tua registrazione è stata approvata. Sei stato iscritto ai seguenti corsi:\\n{$a->courses}\\n\\nCommenti dell'amministratore: {$a->comments}\\n\\nOra puoi accedere e visualizzare i tuoi corsi.: {$a->sitelink}",
        "email_rejected_subject": "Registrazione rifiutata",
        "email_rejected_body": "Buongiorno {$a->firstname},\\n\\npurtroppo la tua registrazione è stata rifiutata.\\n\\nCommenti dell'amministratore: {$a->comments}\\n\\nAccedi e segui le istruzioni per ricaricare la tua documentazione.: {$a->uploadurl}",
        "email_course_approved_subject": "Iscrizione al corso approvata: {$a->coursename}",
        "email_course_approved_body": "Buongiorno {$a->firstname},\\n\\nla tua richiesta di iscrizione al corso \\\"{$a->coursename}\\\" è stata approvata.\\n\\nCommenti dell'amministratore: {$a->comments}\\n\\nOra puoi accedere al corso.: {$a->courseurl}",
        "notapplicable": "N/D",
        "default_approve_comment": "La tua registrazione è stata approvata a seguito della nostra revisione manuale.",
        "default_deny_comment": "La tua documentazione era insufficiente o non corrispondeva ai nostri requisiti.",
        "default_approve_course_comment": "La tua iscrizione a questo corso è stata approvata.",
        "default_deny_course_comment": "Non soddisfi i prerequisiti o i criteri richiesti per questo corso specifico."
    },
    "ja": {
        "admin_comments": "管理者コメント",
        "deny_with_reason": "理由を添えて拒否",
        "email_admin_subject": "新しいIDのアップロード: {$a->username}",
        "email_admin_body": "ユーザー {$a->username} が確認のためにIDをアップロードしました。\\nこちらでリクエストを確認できます: {$a->url}",
        "email_approved_subject": "コースへの登録が承認されました",
        "email_approved_body": "{$a->firstname} さん、こんにちは。\\n\\n登録が承認されました。以下のコースに登録されました：\\n{$a->courses}\\n\\n管理者コメント: {$a->comments}\\n\\nログインしてコースにアクセスできるようになりました。: {$a->sitelink}",
        "email_rejected_subject": "登録が拒否されました",
        "email_rejected_body": "{$a->firstname} さん、こんにちは。\\n\\n残念ながら、登録は拒否されました。\\n\\n管理者コメント: {$a->comments}\\n\\nログインして、指示に従って書類を再アップロードしてください。: {$a->uploadurl}",
        "email_course_approved_subject": "コースへの登録が承認されました: {$a->coursename}",
        "email_course_approved_body": "{$a->firstname} さん、こんにちは。\\n\\nコース「{$a->coursename}」への参加リクエストが承認されました。\\n\\n管理者コメント: {$a->comments}\\n\\nコースにアクセスできるようになりました。: {$a->courseurl}",
        "notapplicable": "該当なし",
        "default_approve_comment": "手動による確認の結果、登録が承認されました。",
        "default_deny_comment": "書類が不十分であるか、要件を満たしていませんでした。",
        "default_approve_course_comment": "このコースへの登録が承認されました。",
        "default_deny_course_comment": "この特定のコースの前提条件または必要な基準を満たしていません。"
    },
    "kn": {
        "admin_comments": "ನಿರ್ವಾಹಕರ ಕಾಮೆಂಟ್‌ಗಳು",
        "deny_with_reason": "ಕಾರಣದೊಂದಿಗೆ ತಿರಸ್ಕರಿಸಿ",
        "email_admin_subject": "ಹೊಸ ಐಡಿ ಅಪ್‌ಲೋಡ್: {$a->username}",
        "email_admin_body": "ಬಳಕೆದಾರ {$a->username} ಪರಿಶೀಲನೆಗಾಗಿ ತಮ್ಮ ಐಡಿಯನ್ನು ಅಪ್‌ಲೋಡ್ ಮಾಡಿದ್ದಾರೆ.\\nನೀವು ವಿನಂತಿಯನ್ನು ಇಲ್ಲಿ ಪರಿಶೀಲಿಸಬಹುದು: {$a->url}",
        "email_approved_subject": "ಕೋರ್ಸ್ ದಾಖಲಾತಿ ಅನುಮೋದಿಸಲಾಗಿದೆ",
        "email_approved_body": "ಹಲೋ {$a->firstname},\\n\\nನಿಮ್ಮ ನೋಂದಣಿಯನ್ನು ಅನುಮೋದಿಸಲಾಗಿದೆ. ಈ ಕೆಳಗಿನ ಕೋರ್ಸ್‌ಗಳಿಗೆ ನೀವು ದಾಖಲಾಗಿದ್ದೀರಿ:\\n{$a->courses}\\n\\nನಿರ್ವಾಹಕರ ಕಾಮೆಂಟ್‌ಗಳು: {$a->comments}\\n\\nನೀವು ಈಗ ಲಾಗ್ ಇನ್ ಮಾಡಬಹುದು ಮತ್ತು ನಿಮ್ಮ ಕೋರ್ಸ್‌ಗಳನ್ನು ಪ್ರವೇಶಿಸಬಹುದು.: {$a->sitelink}",
        "email_rejected_subject": "ನೋಂದಣಿ ತಿರಸ್ಕರಿಸಲಾಗಿದೆ",
        "email_rejected_body": "ಹಲೋ {$a->firstname},\\n\\nದುರದೃಷ್ಟವಶಾತ್, ನಿಮ್ಮ ನೋಂದಣಿಯನ್ನು ತಿರಸ್ಕರಿಸಲಾಗಿದೆ.\\n\\nನಿರ್ವಾಹಕರ ಕಾಮೆಂಟ್‌ಗಳು: {$a->comments}\\n\\nದಯವಿಟ್ಟು ಲಾಗ್ ಇನ್ ಮಾಡಿ ಮತ್ತು ನಿಮ್ಮ ದಾಖಲಾತಿಗಳನ್ನು ಮರು-ಅಪ್‌ಲೋಡ್ ಮಾಡಲು ಸೂಚನೆಗಳನ್ನು ಅನುಸರಿಸಿ.: {$a->uploadurl}",
        "email_course_approved_subject": "ಕೋರ್ಸ್ ದಾಖಲಾತಿ ಅನುಮೋದಿಸಲಾಗಿದೆ: {$a->coursename}",
        "email_course_approved_body": "ಹಲೋ {$a->firstname},\\n\\n\\\"{$a->coursename}\\\" ಕೋರ್ಸ್‌ಗೆ ಸೇರುವ ನಿಮ್ಮ ವಿನಂತಿಯನ್ನು ಅನುಮೋದಿಸಲಾಗಿದೆ.\\n\\nನಿರ್ವಾಹಕರ ಕಾಮೆಂಟ್‌ಗಳು: {$a->comments}\\n\\nನೀವು ಈಗ ಕೋರ್ಸ್ ಅನ್ನು ಪ್ರವೇಶಿಸಬಹುದು.: {$a->courseurl}",
        "notapplicable": "ಅನ್ವಯಿಸುವುದಿಲ್ಲ",
        "default_approve_comment": "ನಮ್ಮ ಹಸ್ತಚಾಲಿತ ಪರಿಶೀಲನೆಯ ನಂತರ ನಿಮ್ಮ ನೋಂದಣಿಯನ್ನು ಅನುಮೋದಿಸಲಾಗಿದೆ.",
        "default_deny_comment": "ನಿಮ್ಮ ದಾಖಲೆಗಳು ಸಾಕಷ್ಟಿರಲಿಲ್ಲ ಅಥವಾ ನಮ್ಮ ಅವಶ್ಯಕತೆಗಳಿಗೆ ಹೊಂದಿಕೆಯಾಗಲಿಲ್ಲ.",
        "default_approve_course_comment": "ಈ ಕೋರ್ಸ್‌ನಲ್ಲಿ ನಿಮ್ಮ ದಾಖಲಾತಿಯನ್ನು ಅನುಮೋದಿಸಲಾಗಿದೆ.",
        "default_deny_course_comment": "ಈ ನಿರ್ದಿష్ట ಕೋರ್ಸ್‌ಗೆ ಅಗತ್ಯವಿರುವ ಪೂರ್ವಾಪೇಕ್ಷಿತಗಳು ಅಥವಾ ಅಗತ್ಯ ಮಾನದಂಡಗಳನ್ನು ನೀವು ಪೂರೈಸುವುದಿಲ್ಲ."
    },
    "ko": {
        "admin_comments": "관리자 코멘트",
        "deny_with_reason": "사유와 함께 거절",
        "email_admin_subject": "새 ID 업로드: {$a->username}",
        "email_admin_body": "사용자 {$a->username}님이 검토를 위해 ID를 업로드했습니다.\\n여기에서 요청을 검토할 수 있습니다: {$a->url}",
        "email_approved_subject": "강좌 등록 승인됨",
        "email_approved_body": "안녕하세요 {$a->firstname}님,\\n\\n등록이 승인되었습니다. 다음 강좌에 등록되었습니다:\\n{$a->courses}\\n\\n관리자 코멘트: {$a->comments}\\n\\n이제 로그인하여 강좌에 접속할 수 있습니다.: {$a->sitelink}",
        "email_rejected_subject": "등록 거절됨",
        "email_rejected_body": "안녕하세요 {$a->firstname}님,\\n\\n안타깝게도 등록이 거절되었습니다.\\n\\n관리자 코멘트: {$a->comments}\\n\\n로그인하여 안내에 따라 서류를 다시 업로드해 주세요.: {$a->uploadurl}",
        "email_course_approved_subject": "강좌 등록 승인됨: {$a->coursename}",
        "email_course_approved_body": "안녕하세요 {$a->firstname}님,\\n\\n\\\"{$a->coursename}\\\" 강좌 참여 요청이 승인되었습니다.\\n\\n관리자 코멘트: {$a->comments}\\n\\n이제 강좌에 접속할 수 있습니다.: {$a->courseurl}",
        "notapplicable": "해당 없음",
        "default_approve_comment": "수동 검토 결과 등록이 승인되었습니다.",
        "default_deny_comment": "서류가 불충분하거나 요구 사항과 일치하지 않습니다.",
        "default_approve_course_comment": "이 강좌로의 등록이 승인되었습니다.",
        "default_deny_course_comment": "이 특정 강좌에 필요한 선수 과목이나 기준을 충족하지 못했습니다."
    },
    "ml": {
        "admin_comments": "അഡ്മിൻ അഭിപ്രായങ്ങൾ",
        "deny_with_reason": "കാരണം സഹിതം നിരസിക്കുക",
        "email_admin_subject": "പുതിയ ഐഡി അപ്‌ലോഡ്: {$a->username}",
        "email_admin_body": "{$a->username} എന്ന ഉപയോക്താവ് പരിശോധനയ്ക്കായി ഐഡി അപ്‌ലോഡ് ചെയ്തിട്ടുണ്ട്.\\nനിങ്ങൾക്ക് ഇവിടെ അപേക്ഷ പരിശോധിക്കാവുന്നതാണ്: {$a->url}",
        "email_approved_subject": "കോഴ്സ് എൻറോൾമെന്റ് അംഗീകരിച്ചു",
        "email_approved_body": "ഹലോ {$a->firstname},\\n\\nനിങ്ങളുടെ രജിസ്ട്രേഷൻ അംഗീകരിച്ചു. താഴെ പറയുന്ന കോഴ്സുകളിൽ നിങ്ങൾ എൻറോൾ ചെയ്യപ്പെട്ടിട്ടുണ്ട്:\\n{$a->courses}\\n\\nഅഡ്മിൻ അഭിപ്രായങ്ങൾ: {$a->comments}\\n\\nനിങ്ങൾക്ക് ഇപ്പോൾ ലോഗിൻ ചെയ്യാനും കോഴ്സുകൾ കാണാനും കഴியும்.: {$a->sitelink}",
        "email_rejected_subject": "രജിസ്ട്രേഷൻ നിരസിച്ചു",
        "email_rejected_body": "ഹലോ {$a->firstname},\\n\\nനിർഭാഗ്യവശാൽ, നിങ്ങളുടെ രജിസ്ട്രേഷൻ നിരസിക്കപ്പെട്ടു.\\n\\nഅഡ്മിൻ അഭിപ്രായങ്ങൾ: {$a->comments}\\n\\nദയവായ് ലോഗിன் ചെയ്ത് രേഖകൾ വീണ്ടും അപ്‌ലോഡ് ചെയ്യുന്നതിനുള്ള നിർദ്ദേശങ്ങൾ പാലിക്കുക.: {$a->uploadurl}",
        "email_course_approved_subject": "കോഴ്സ് എൻറോൾമെന്റ് അംഗീകരിച്ചു: {$a->coursename}",
        "email_course_approved_body": "ഹലോ {$a->firstname},\\n\\n\\\"{$a->coursename}\\\" എന്ന കോഴ്സിൽ ചേരാനുള്ള നിങ്ങളുടെ അപേക്ഷ അംഗീകരിച്ചു.\\n\\nഅഡ്മിൻ അഭിപ്രായങ്ങൾ: {$a->comments}\\n\\nനിങ്ങൾക്ക് ഇപ്പോൾ കോഴ്സ് കാണാൻ കഴியும்.: {$a->courseurl}",
        "notapplicable": "ബാധകമല്ല",
        "default_approve_comment": "ഞങ്ങളുടെ മാനുവൽ പരിശോധനയ്ക്ക് ശേഷം നിങ്ങളുടെ രജിസ്ട്രേഷൻ അംഗീകരിച്ചു.",
        "default_deny_comment": "നിങ്ങളുടെ രേഖകൾ അപര്യാപ്തമാണ് അല്ലെങ്കിൽ ഞങ്ങളുടെ ആവശ്യകതകളുമായി പൊരുത്തപ്പെടുന്നില്ല.",
        "default_approve_course_comment": "ഈ കോഴ്സിലേക്കുള്ള നിങ്ങളുടെ എൻറോൾമെന്റ് അംഗീകരിച്ചു.",
        "default_deny_course_comment": "ഈ നിർദ്ദിഷ്ട കോഴ്സിന് ആവശ്യമായ മാനണ്ഡങ്ങളോ യോഗ്യതകളോ നിങ്ങൾ പാലിക്കുന്നില്ല."
    },
    "mr": {
        "admin_comments": "अ‍ॅडमिन टिप्पण्या",
        "deny_with_reason": "कारणासह नाकारा",
        "email_admin_subject": "नवीन आयڈی अपलोड: {$a->username}",
        "email_admin_body": "वापरकर्ता {$a->username} ने पुनरावलोकनासाठी त्यांचा आयडी अपलोड केला आहे.\\nतुम्ही येथे विनंतीचे पुनरावलोकन करू शकता: {$a->url}",
        "email_approved_subject": "कोर्स नोंदणी मंजूर",
        "email_approved_body": "नमस्कार {$a->firstname},\\n\\nतुमची नोंदणी मंजूर झाली आहे. तुमची खालील कोर्सेसमध्ये नोंदणी झाली आहे:\\n{$a->courses}\\n\\nअ‍ॅडमिन टिप्पण्या: {$a->comments}\\n\\nतुमची नोंदणी मंजूर झाली आहे. आता आपण लॉग इन करू शकता आणि आपल्या कोर्सेसमध्ये प्रवेश करू शकता.: {$a->sitelink}",
        "email_rejected_subject": "नोंदणी नाकारली",
        "email_rejected_body": "नमस्कार {$a->firstname},\\n\\nदुर्भाग्यवशात्, तुमची नोंदणी नाकारली गेली आहे.\\n\\nअ‍ॅडमिन टिप्पण्या: {$a->comments}\\n\\nकृपया लॉग इन करा aur तुमची कागদपत्रे पुन्हा अपलोड करण्यासाठी सूचनांचे पालन करा.: {$a->uploadurl}",
        "email_course_approved_subject": "कोर्स नोंदणी मंजूर: {$a->coursename}",
        "email_course_approved_body": "नमस्कार {$a->firstname},\\n\\n\\\"{$a->coursename}\\\" कोर्समध्ये सामील होण्याची तुमची विनंती मंजूर झाली आहे.\\n\\nअ‍ॅडमिन टिप्पण्या: {$a->comments}\\n\\nतुम्ही आता कोर्समध्ये प्रवेश करू शकता.: {$a->courseurl}",
        "notapplicable": "लागू नाही",
        "default_approve_comment": "आमच्या మॅन्युअल पुनरावलोकನಾनंतर तुमची नोंदणी मंजूर झाली आहे.",
        "default_deny_comment": "तुमची कागदपत्रे अपुरी होती किंवा आमच्या आवश्यकतांशी जुळत नव्हती.",
        "default_approve_course_comment": "या कोर्समधील तुमची नोंदणी मंजूर झाली आहे.",
        "default_deny_course_comment": "तुम्ही या विशिष्ट कोर्ससाठी आवश्यक अटी किंवा निकष पूर्ण करत नाही."
    },
    "nl": {
        "admin_comments": "Opmerkingen beheerder",
        "deny_with_reason": "Afwijzen met reden",
        "email_admin_subject": "Nieuwe ID-upload: {$a->username}",
        "email_admin_body": "Gebruiker {$a->username} heeft zijn/haar ID geüpload ter beoordeling.\\nU kunt het verzoek hier bekijken: {$a->url}",
        "email_approved_subject": "Cursusinschrijving goedgekeurd",
        "email_approved_body": "Hallo {$a->firstname},\\n\\nUw registratie is goedgekeurd. U bent ingeschreven voor de volgende cursussen:\\n{$a->courses}\\n\\nOpmerkingen beheerder: {$a->comments}\\n\\nU kunt nu inloggen en uw cursussen bekijken.: {$a->sitelink}",
        "email_rejected_subject": "Registratie afgewezen",
        "email_rejected_body": "Hallo {$a->firstname},\\n\\nHelaas is uw registratie afgewezen.\\n\\nOpmerkingen beheerder: {$a->comments}\\n\\nLog in en volg de instructies om uw documentatie opnieuw te uploaden.: {$a->uploadurl}",
        "email_course_approved_subject": "Cursusinschrijving goedgekeurd: {$a->coursename}",
        "email_course_approved_body": "Hallo {$a->firstname},\\n\\nUw verzoek om deel te nemen aan de cursus \\\"{$a->coursename}\\\" is goedgekeurd.\\n\\nOpmerkingen beheerder: {$a->comments}\\n\\nU hebt nu toegang tot de cursus.: {$a->courseurl}",
        "notapplicable": "N.v.t.",
        "default_approve_comment": "Uw registratie is goedgekeurd na onze handmatige controle.",
        "default_deny_comment": "Uw documentatie was onvoldoende of voldeed niet aan onze vereisten.",
        "default_approve_course_comment": "Uw inschrijving voor deze cursus is goedgekeurd.",
        "default_deny_course_comment": "U voldoet niet aan de instapeisen of vereiste criteria voor deze specifieke cursus."
    },
    "or": {
        "admin_comments": "ପ୍ରଶାସକ ମନ୍ତବ୍ୟ",
        "deny_with_reason": "କାରଣ ସହିତ ପ୍ରତ୍ୟାଖ୍ୟାନ କରନ୍ତୁ",
        "email_admin_subject": "ନୂତନ ଆଇଡି ଅପଲୋଡ୍: {$a->username}",
        "email_admin_body": "ଉପଭୋକ୍ତା {$a->username} ସମୀକ୍ଷା ପାଇଁ ସେମାନଙ୍କର ଆଇଡି ଅପଲୋଡ୍ କରିଛନ୍ତି।\\nଆପଣ ଏଠାରେ ଅନୁରୋଧ ସମୀକ୍ଷା କରିପାରିବେ: {$a->url}",
        "email_approved_subject": "ପାଠ୍ୟକ୍ରମ ନାମଲେଖା ଅନୁମୋଦିତ",
        "email_approved_body": "ନମସ୍କାର {$a->firstname},\\n\\nଆପଣଙ୍କର ପଞ୍ଜିକରଣ ଅନୁମୋଦିତ ହୋଇଛି | ଆପଣ ନିମ୍ନଲିଖିତ ପାଠ୍ୟକ୍ରମରେ ନାମ ଲେଖାଇଛନ୍ତି:\\n{$a->courses}\\n\\nପ୍ରଶାସକ ମନ୍ତବ୍ୟ: {$a->comments}\\n\\nଆପଣ ବର୍ତ୍ତମାନ ଲଗ୍ ଇନ୍ କରିପାରିବେ ଏବଂ ଆପଣଙ୍କର ପାଠ୍ୟକ୍ରમକୁ ବ୍ୟବହାର କରିପାରିବେ |: {$a->sitelink}",
        "email_rejected_subject": "ପଞ୍ଜିକରଣ ପ୍ରତ୍ୟାଖ୍ୟାନ କରାଯାଇଛି",
        "email_rejected_body": "ନମସ୍କାର {$a->firstname},\\n\\nଦୁର୍ଭାଗ୍ୟବଶତ,, ଆପଣଙ୍କର ପଞ୍ଜିକରଣ ପ୍ରତ୍ୟାଖ୍ୟାନ କରାଯାଇଛି | \\n\\nପ୍ରଶାସକ ମନ୍ତବ୍ୟ: {$a->comments}\\n\\nଦୟାକରି ଲଗ୍ ଇନ୍ କରନ୍ତୁ ଏବଂ ଆପଣଙ୍କର ଦଲିଲଗୁଡ଼ିକୁ ପୁନର୍ବାର ଅପଲୋଡ୍ କରିବାକୁ ନିର୍ଦ୍ଦେଶାବଳୀ ଅନୁସରଣ କରନ୍ତୁ |: {$a->uploadurl}",
        "email_course_approved_subject": "ପାଠ୍ୟକ୍ରମ ନାମଲେଖା ଅନୁମୋଦିତ: {$a->coursename}",
        "email_course_approved_body": "ନମସ୍କାର {$a->firstname},\\n\\n\\\"{$a->coursename}\\\" ପାଠ୍ୟକ୍ରମରେ ଯୋଗଦେବା ପାଇଁ ଆପଣଙ୍କର ଅନୁରୋଧ ଅନୁମୋଦିତ ହୋଇଛି | \\n\\nପ୍ରଶାସକ ମନ୍ତବ୍ୟ: {$a->comments}\\n\\nଆପଣ ବର୍ତ୍ତମାନ ପାଠ୍ୟକ୍ରମଟିକୁ ବ୍ୟବహାର କରିପାରିବେ |: {$a->courseurl}",
        "notapplicable": "ଲାଗୁ ନାହିଁ",
        "default_approve_comment": "ଆମର ମାନୁଆଲ୍ ସମୀକ୍ଷା ପରେ ଆପଣଙ୍କର ପଞ୍ଜିକରଣ ଅନୁମୋଦିତ ହୋଇଛି |",
        "default_deny_comment": "ଆପଣଙ୍କର ଦଲିଲଗୁଡ଼ିକ ପର୍ଯ୍ୟାପ୍ତ ନଥିଲା କିମ୍ବା ଆମର ଆବଶ୍ୟକତା ସହିତ ମେଳ ଖାଉ ନଥିଲା |",
        "default_approve_course_comment": "ଏହି ପାଠ୍ୟକ୍ରମରେ ଆପଣଙ୍କର ନାମଲେଖା ଅନୁମୋଦିତ ହୋଇଛି |",
        "default_deny_course_comment": "ଆପଣ ଏହି ନିର୍ଦ୍ଦିଷ୍ଟ ପାଠ୍ୟକ୍ରମ ପାଇଁ ଆବଶ୍ୟକ ସର୍ତ୍ତ କିମ୍ବା ମାନଦଣ୍ଡ ପୂରଣ କରୁନାହାଁନ୍ତି |"
    },
    "pa": {
        "admin_comments": "ਐਡਮਿਨ ਟਿੱਪਣੀਆਂ",
        "deny_with_reason": "ਕਾਰਨ ਸਹਿਤ ਅਸਵੀਕਾਰ ਕਰੋ",
        "email_admin_subject": "ਨਵਾਂ ਆਈਡੀ ਅਪਲੋਡ: {$a->username}",
        "email_admin_body": "ਵਰਤੋਂਕਾਰ {$a->username} ਨੇ ਸਮੀਖਿਆ ਲਈ ਆਪਣੀ ਆਈਡੀ ਅਪਲੋਡ ਕੀਤੀ ਹੈ।\\nਤੁਸੀਂ ਇੱਥੇ ਬੇਨਤੀ ਦੀ ਸਮੀਖਿਆ ਕਰ ਸਕਦੇ ਹੋ: {$a->url}",
        "email_approved_subject": "ਕੋਰਸ ਦਾਖਲਾ ਮਨਜ਼ੂਰ",
        "email_approved_body": "ਹੈਲੋ {$a->firstname},\\n\\nਤੁਹਾਡੀ ਰਜਿਸਟ੍ਰੇਸ਼ਨ ਮਨਜ਼ੂਰ ਹੋ ਗਈ ਹੈ। ਤੁਹਾਨੂੰ ਹੇਠਾਂ ਦਿੱਤੇ ਕੋਰਸਾਂ ਵਿੱਚ ਦਾਖਲ ਕੀਤਾ ਗਿਆ ਹੈ:\\n{$a->courses}\\n\\nਐਡਮਿਨ ਟਿੱਪਣੀਆਂ: {$a->comments}\\n\\nਤੁਸੀਂ ਹੁਣ ਲੌਗ ਇਨ ਕਰ ਸਕਦੇ ਹੋ ਅਤੇ ਆਪਣੇ ਕੋਰਸਾਂ ਤੱਕ ਪਹੁੰਚ ਕਰ ਸਕਦੇ ਹੋ।: {$a->sitelink}",
        "email_rejected_subject": "ਰਜਿਸਟਰੇਸ਼ਨ ਅਸਵੀਕਾਰ ਕੀਤੀ ਗਈ",
        "email_rejected_body": "ਹੈਲੋ {$a->firstname},\\n\\nਦੁਬਾਗਵਸ਼, ਤੁਹਾਡੀ ਰਜਿਸਟ੍ਰੇਸ਼ਨ ਅਸਵੀਕਾਰ ਕਰ ਦਿੱਤੀ ਗਈ ਹੈ।\\n\\nਐਡਮਿਨ ਟਿੱਪਣੀਆਂ: {$a->comments}\\n\\nਕਿਰਪਾ ਕਰਕੇ ਲੌਗ ਇਨ ਕਰੋ ਅਤੇ ਆਪਣੇ ਦਸਤਾਵੇਜ਼ਾਂ ਨੂੰ ਦੁਬਾਰਾ ਅਪਲੋਡ ਕਰਨ ਲਈ ਨਿਰਦੇਸ਼ਾਂ ਦੀ ਪਾਲਣਾ ਕਰੋ।: {$a->uploadurl}",
        "email_course_approved_subject": "ਕੋਰਸ ਦਾਖਲਾ ਮਨਜ਼ੂਰ: {$a->coursename}",
        "email_course_approved_body": "ਹੈਲੋ {$a->firstname},\\n\\nਕੋਰਸ \\\"{$a->coursename}\\\" ਵਿੱਚ ਸ਼ਾਮਲ ਹੋਣ ਦੀ ਤੁਹਾਡੀ ਬੇਨਤੀ ਮਨਜ਼ੂਰ ਹੋ ਗਈ ਹੈ।\\n\\nਐਡਮਿਨ ਟਿੱਪਣੀਆਂ: {$a->comments}\\n\\nਤੁਸੀਂ ਹੁਣ ਕੋਰਸ ਤੱਕ ਪਹੁੰਚ ਕਰ ਸਕਦੇ ਹੋ।: {$a->courseurl}",
        "notapplicable": "ਲਾਗੂ ਨਹੀਂ",
        "default_approve_comment": "ਸਾਡੀ ਮੈਨੂਅਲ ਸਮੀਖਿਆ ਤੋਂ ਬਾਅਦ ਤੁਹਾਡੀ ਰਜਿਸਟ੍ਰੇਸ਼ਨ ਮਨਜ਼ੂਰ ਕਰ ਦਿੱਤੀ ਗਈ ਹੈ।",
        "default_deny_comment": "ਤੁਹਾਡੇ ਦਸਤਾਵੇਜ਼ ਨਾਕਾਫ਼ੀ ਸਨ ਜਾਂ ਸਾਡੀਆਂ ਲੋੜਾਂ ਨਾਲ ਮੇਲ ਨਹੀਂ ਖਾਂਦੇ ਸਨ।",
        "default_approve_course_comment": "ਇਸ ਕੋਰਸ ਵਿੱਚ ਤੁਹਾਡਾ ਦਾਖਲਾ ਮਨਜ਼ੂਰ ਕਰ ਦਿੱਤਾ ਗਿਆ ਹੈ।",
        "default_deny_course_comment": "ਤੁਸੀਂ ਇਸ ਖਾਸ ਕੋਰਸ ਲਈ ਲੋੜੀਂਦੀਆਂ ਸ਼ਰਤਾਂ ਜਾਂ ਮਾਪਦੰਡਾਂ ਨੂੰ ਪੂਰਾ ਨਹੀਂ ਕਰਦੇ ਹੋ।"
    },
    "pt": {
        "admin_comments": "Comentários do administrador",
        "deny_with_reason": "Rejeitar com motivo",
        "email_admin_subject": "Novo carregamento de ID: {$a->username}",
        "email_admin_body": "O utilizador {$a->username} carregou a sua identificação para revisão.\\nPode rever o pedido aqui: {$a->url}",
        "email_approved_subject": "Inscrição no curso aprovada",
        "email_approved_body": "Olá {$a->firstname},\\n\\nO seu registo foi aprovado. Foi inscrito nos seguintes cursos:\\n{$a->courses}\\n\\nComentários do administrador: {$a->comments}\\n\\nPode agora iniciar sessão e aceder aos seus cursos.: {$a->sitelink}",
        "email_rejected_subject": "Registo rejeitado",
        "email_rejected_body": "Olá {$a->firstname},\\n\\nInfelizmente, o seu registo foi rejeitado.\\n\\nComentários do administrador: {$a->comments}\\n\\nInicie sessão e siga as instruções para voltar a carregar a sua documentação.: {$a->uploadurl}",
        "email_course_approved_subject": "Inscrição no curso aprovada: {$a->coursename}",
        "email_course_approved_body": "Olá {$a->firstname},\\n\\nO seu pedido para aderir ao curso \\\"{$a->coursename}\\\" foi aprovado.\\n\\nComentários do administrador: {$a->comments}\\n\\nPode agora aceder ao curso.: {$a->courseurl}",
        "notapplicable": "N/A",
        "default_approve_comment": "O seu registo foi aprovado após a nossa revisão manual.",
        "default_deny_comment": "A sua documentação foi insuficiente ou não correspondeu aos nossos requisitos.",
        "default_approve_course_comment": "A sua inscrição neste curso foi aprovada.",
        "default_deny_course_comment": "Não cumpre os pré-requisitos ou critérios necessários para este curso específico."
    },
    "ru": {
        "admin_comments": "Комментарии администратора",
        "deny_with_reason": "Отклонить с указанием причины",
        "email_admin_subject": "Загружено новое удостоверение: {$a->username}",
        "email_admin_body": "Пользователь {$a->username} загрузил свое удостоверение для проверки.\\nВы можете просмотреть запрос здесь: {$a->url}",
        "email_approved_subject": "Зачисление на курс одобрено",
        "email_approved_body": "Здравствуйте, {$a->firstname}!\\n\\nВаша регистрация одобрена. Вы были зачислены на следующие курсы:\\n{$a->courses}\\n\\nКомментарии администратора: {$a->comments}\\n\\nТеперь вы можете войти в систему и получить доступ к своим курсам.: {$a->sitelink}",
        "email_rejected_subject": "Регистрация отклонена",
        "email_rejected_body": "Здравствуйте, {$a->firstname}!\\n\\nК сожалению, ваша регистрация была отклонена.\\n\\nКомментарии администратора: {$a->comments}\\n\\nПожалуйста, войдите в систему и следуйте инструкциям для повторной загрузки документов.: {$a->uploadurl}",
        "email_course_approved_subject": "Зачисление на курс одобрено: {$a->coursename}",
        "email_course_approved_body": "Здравствуйте, {$a->firstname}!\\n\\nЗапрос на ваше участие в курсе «{$a->coursename}» был одобрен.\\n\\nКомментарии администратора: {$a->comments}\\n\\nТеперь вы можете получить доступ к курсу.: {$a->courseurl}",
        "notapplicable": "Н/Д",
        "default_approve_comment": "Ваша регистрация была одобрена после нашей проверки вручную.",
        "default_deny_comment": "Ваши документы были недостаточными или не соответствовали нашим требованиям.",
        "default_approve_course_comment": "Ваше зачисление на этот курс одобрено.",
        "default_deny_course_comment": "Вы не соответствуете предварительным условиям или предъявляемым критериям для данного конкретного курса."
    },
    "ta": {
        "admin_comments": "நிர்வாகி கருத்துகள்",
        "deny_with_reason": "காரணத்துடன் நிராகரிக்கவும்",
        "email_admin_subject": "புதிய அடையாள அட்டை பதிவேற்றம்: {$a->username}",
        "email_admin_body": "பயனர் {$a->username} தனது அடையாள அட்டையை சரிபார்ப்புக்காக பதிவேற்றியுள்ளார்.\\nகோரிக்கையை இங்கே மதிப்பாய்வு செய்யலாம்: {$a->url}",
        "email_approved_subject": "பாடநெறி சேர்க்கை அனுமதிக்கப்பட்டது",
        "email_approved_body": "வணக்கம் {$a->firstname},\\n\\nஉங்கள் பதிவு அனுமதிக்கப்பட்டது. நீங்கள் பின்வரும் பாடநெறிகளில் சேர்க்கப்பட்டுள்ளீர்கள்:\\n{$a->courses}\\n\\nநிர்வாகி கருத்துகள்: {$a->comments}\\n\\nஇப்போது நீங்கள் உள்நுழைந்து உங்கள் பாடநெறிகளை அணுகலாம்.: {$a->sitelink}",
        "email_rejected_subject": "பதிவு நிராகரிக்கப்பட்டது",
        "email_rejected_body": "வணக்கம் {$a->firstname},\\n\\nதுரதிர்ஷ்டவசமாக, உங்கள் பதிவு நிராகரிக்கப்பட்டது.\\n\\nநிர்வாகி கருத்துகள்: {$a->comments}\\n\\nதயவுசெய்து உள்நுழைந்து, உங்கள் ஆவணங்களை மீண்டும் பதிவேற்றுவதற்கான வழிமுறைகளைப் பின்பற்றவும்.: {$a->uploadurl}",
        "email_course_approved_subject": "பாடநெறி சேர்க்கை அனுமதிக்கப்பட்டது: {$a->coursename}",
        "email_course_approved_body": "வணக்கம் {$a->firstname},\\n\\n\\\"{$a->coursename}\\\" பாடநெறியில் சேர நீங்கள் விடுத்த கோரிக்கை அனுமதிக்கப்பட்டது.\\n\\nநிர்வாகி கருத்துகள்: {$a->comments}\\n\\nஇப்போது நீங்கள் பாடநெறியை அணுகலாம்.: {$a->courseurl}",
        "notapplicable": "பொருந்தாது",
        "default_approve_comment": "எங்கள் கைமுறை சரிபார்ப்பைத் தொடர்ந்து உங்கள் பதிவு அனுமதிக்கப்பட்டது.",
        "default_deny_comment": "உங்கள் ஆவணங்கள் போதுமானதாக இல்லை அல்லது எங்கள் தேவைகளுடன் பொருந்தவில்லை.",
        "default_approve_course_comment": "இந்த பாடநெறியில் உங்கள் சேர்க்கை அனுமதிக்கப்பட்டது.",
        "default_deny_course_comment": "இந்த குறிப்பிட்ட பாடநெறிக்கான முன்நிபந்தனைகள் அல்லது தேவையான அளவுகோல்களை நீங்கள் பூர்த்தி செய்யவில்லை."
    },
    "te": {
        "admin_comments": "అడ్మిన్ వ్యాఖ్యలు",
        "deny_with_reason": "కారణంతో తిరస్కరించండి",
        "email_admin_subject": "కొత్త ఐడి అప్‌లోడ్: {$a->username}",
        "email_admin_body": "వినియోగదారు {$a->username} సమీక్ష కోసం తమ ఐడిని అప్‌లోడ్ చేశారు.\\nమీరు అభ్యర్థనను ఇక్కడ సమీక్షించవచ్చు: {$a->url}",
        "email_approved_subject": "కోర్సు ఎన్‌రోల్‌మెంట్ ఆమోదించబడింది",
        "email_approved_body": "హలో {$a->firstname},\\n\\nమీ రిజిస్ట్రేషన్ ఆమోదించబడింది. మీరు ఈ క్రింది కోర్సులలో ఎన్‌రోల్ చేయబడ్డారు:\\n{$a->courses}\\n\\nఅడ్మిన్ వ్యాఖ్యలు: {$a->comments}\\n\\nమీరు ఇప్పుడు లాగిన్ చేసి మీ కోర్సులను యాక్సెస్ చేయవచ్చు.: {$a->sitelink}",
        "email_rejected_subject": "రిజిస్ట్రేషన్ తిరస్కరించబడింది",
        "email_rejected_body": "హలో {$a->firstname},\\n\\nదురదృష్టవశాత్తు, మీ రిజిస్ట్రేషన్ తిరస్కరించబడింది.\\n\\nఅడ్మిన్ వ్యాఖ్యలు: {$a->comments}\\n\\nదయచేసి లాగిన్ చేసి, మీ పత్రాలను మళ్లీ అప్‌లోడ్ చేయడానికి సూచనలను అనుసరించండి.: {$a->uploadurl}",
        "email_course_approved_subject": "కోర్సు ఎన్‌రోల్‌మెంట్ ఆమోదించబడింది: {$a->coursename}",
        "email_course_approved_body": "హలో {$a->firstname},\\n\\n\\\"{$a->coursename}\\\" కోర్సులో చేరడానికి మీ అభ్యర్థన ఆమోదించబడింది.\\n\\nఅడ్మిన్ వ్యాఖ్యలు: {$a->comments}\\n\\nమీరు ఇప్పుడు కోర్సును యాక్సెస్ చేయవచ్చు.: {$a->courseurl}",
        "notapplicable": "వర్తించదు",
        "default_approve_comment": "మా మాన్యువల్ సమీక్ష తర్వాత మీ రిజిస్ట్రేషన్ ఆమోదించబడింది.",
        "default_deny_comment": "మీ డాక్యుమెంటేషన్ సరిపోలేదు లేదా మా అవసరాలకు సరిపోలలేదు.",
        "default_approve_course_comment": "ఈ కోర్సులో మీ ఎన్‌రోల్‌మెంట్ ఆమోదించబడింది.",
        "default_deny_course_comment": "ఈ నిర్దిష్ట కోర్సు కోసం మీరు ముందస్తు అవసరాలు లేదా అవసరమైన ప్రమాణాలను పూర్తి చేయలేదు."
    },
    "tr": {
        "admin_comments": "Yönetici Yorumları",
        "deny_with_reason": "Nedeniyle Reddet",
        "email_admin_subject": "Yeni Kimlik Yükleme: {$a->username}",
        "email_admin_body": "Kullanıcı {$a->username}, inceleme için kimliğini yükledi.\\nTalebi buradan inceleyebilirsiniz: {$a->url}",
        "email_approved_subject": "Kurs Kaydı Onaylandı",
        "email_approved_body": "Merhaba {$a->firstname},\\n\\nKaydınız onaylandı. Aşağıdaki kurslara kaydedildiniz:\\n{$a->courses}\\n\\nYönetici Yorumları: {$a->comments}\\n\\nArtık giriş yapabilir ve kurslarınıza erişebilirsiniz.: {$a->sitelink}",
        "email_rejected_subject": "Kayıt Reddedildi",
        "email_rejected_body": "Merhaba {$a->firstname},\\n\\nMaalesef kaydınız reddedildi.\\n\\nYönetici Yorumları: {$a->comments}\\n\\nLütfen giriş yapın ve belgelerinizi yeniden yüklemek için talimatları izleyin.: {$a->uploadurl}",
        "email_course_approved_subject": "Kurs Kaydı Onaylandı: {$a->coursename}",
        "email_course_approved_body": "Merhaba {$a->firstname},\\n\\n\\\"{$a->coursename}\\\" kursuna katılma talebiniz onaylandı.\\n\\nYönetici Yorumları: {$a->comments}\\n\\nArtık kursa erişebilirsiniz.: {$a->courseurl}",
        "notapplicable": "N/A",
        "default_approve_comment": "Manuel incelememizin ardından kaydınız onaylandı.",
        "default_deny_comment": "Belgeleriniz yetersizdi veya gereksinimlerimize uymuyordu.",
        "default_approve_course_comment": "Bu kursa kaydınız onaylandı.",
        "default_deny_course_comment": "Bu özel kurs için gereken ön koşulları veya kriterleri karşılamıyorsunuz."
    },
    "ur": {
        "admin_comments": "ایڈمن تبصرے",
        "deny_with_reason": "وجہ کے ساتھ مسترد کریں",
        "email_admin_subject": "نیا شناختی کارڈ اپ لوڈ: {$a->username}",
        "email_admin_body": "صارف {$a->username} نے جائزے کے لیے اپنا شناختی کارڈ اپ لوڈ کیا ہے۔\\nآپ یہاں درخواست کا جائزہ لے سکتے ہیں: {$a->url}",
        "email_approved_subject": "کورس میں داخلہ منظور",
        "email_approved_body": "ہیلو {$a->firstname}،\\n\\nآپ کی رجسٹریشن منظور کر لی گئی ہے۔ آپ کو درج ذیل کورسز میں داخل کیا گیا ہے:\\n{$a->courses}\\n\\nایڈمن تبصرے: {$a->comments}\\n\\nاب آپ لاگ ان کر سکتے ہیں اور اپنے کورسز تک رسائی حاصل کر سکتے ہیں۔: {$a->sitelink}",
        "email_rejected_subject": "رجسٹریشن مسترد",
        "email_rejected_body": "ہیلو {$a->firstname}،\\n\\nبدقسمتی سے، آپ کی رجسٹریشن مسترد کر دی گئی ہے۔\\n\\nایڈمن تبصرے: {$a->comments}\\n\\nبراہ کرم لاگ ان کریں اور اپنی دستاویزات دوبارہ اپ لوڈ کرنے کے لیے ہدایات پر عمل کریں۔: {$a->uploadurl}",
        "email_course_approved_subject": "کورس میں داخلہ منظور: {$a->coursename}",
        "email_course_approved_body": "ہیلو {$a->firstname}،\\n\\nکورس \\\"{$a->coursename}\\\" میں شامل ہونے کی آپ کی درخواست منظور کر لی گئی ہے۔\\n\\nایڈمن تبصرے: {$a->comments}\\n\\nاب آپ کورس تک رسائی حاصل کر سکتے ہیں۔: {$a->courseurl}",
        "notapplicable": "قابل اطلاق نہیں",
        "default_approve_comment": "ہمارے مینوئل جائزے کے بعد آپ کی رجسٹریشن منظور کر لی گئی ہے۔",
        "default_deny_comment": "آپ کی دستاویزات ناکافی تھیں یا ہماری ضروریات کے مطابق نہیں تھیں۔",
        "default_approve_course_comment": "اس کورس میں آپ کا داخلہ منظور کر لیا گیا ہے۔",
        "default_deny_course_comment": "آپ اس مخصوص کورس کے لیے مطلوبہ شرائط یا معیار پر پورا نہیں اترتے۔"
    },
    "zh_cn": {
        "admin_comments": "管理员说明",
        "deny_with_reason": "拒绝并说明理由",
        "email_admin_subject": "新身份证明上传：{$a->username}",
        "email_admin_body": "用户 {$a->username} 已上传身份证明供审核。\\n您可以在此处审核请求：{$a->url}",
        "email_approved_subject": "课程注册已通过",
        "email_approved_body": "您好 {$a->firstname}：\\n\\n您的注册已获批准。您已加入以下课程：\\n{$a->courses}\\n\\n管理员说明：{$a->comments}\\n\\n您现在可以登录并访问您的课程。: {$a->sitelink}",
        "email_rejected_subject": "注册被拒绝",
        "email_rejected_body": "您好 {$a->firstname}：\\n\\n很抱歉，您的注册已被拒绝。\\n\\n管理员说明：{$a->comments}\\n\\n请登录并按照说明重新上传您的材料。: {$a->uploadurl}",
        "email_course_approved_subject": "课程注册已通过：{$a->coursename}",
        "email_course_approved_body": "您好 {$a->firstname}：\\n\\n您加入课程“{$a->coursename}”的请求已获批准。\\n\\n管理员说明：{$a->comments}\\n\\n您现在可以访问该课程。: {$a->courseurl}",
        "notapplicable": "不适用",
        "default_approve_comment": "经过我们人工审核，您的注册已获批准。",
        "default_deny_comment": "您的材料不足或不符合我们的要求。",
        "default_approve_course_comment": "您在该课程的注册已获批准。",
        "default_deny_course_comment": "您不符合此特定课程的先决条件或要求。"
    }
}

base_path = "/Users/ugen/Documents/GitHub/customreg"

# First, clean any junk from previous failed attempts
for lang in languages:
    filepath = os.path.join(base_path, "lang", lang, "local_customreg.php")
    if not os.path.exists(filepath): continue
    with open(filepath, 'r', encoding='utf-8') as f:
        lines = f.readlines()
    
    clean_lines = []
    for line in lines:
        if "<ffff" in line or "heredoc>" in line or "stomreg/lang" in line or "import os" in line:
            continue
        if "$string" in line or "<?php" in line or line.strip() == "":
            clean_lines.append(line)
            
    with open(filepath, 'w', encoding='utf-8') as f:
        f.writelines(clean_lines)

# Second, apply translations carefully
for lang in languages:
    filepath = os.path.join(base_path, "lang", lang, "local_customreg.php")
    if not os.path.exists(filepath): continue
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    lang_strings = translations[lang]
    
    for key, val in lang_strings.items():
        escaped_val = val.replace("'", "\\'")
        pattern = rf"\$string\['{key}'\]\s*=\s*'.*?';"
        replacement = f"$string['{key}'] = '{escaped_val}';"
        
        if re.search(pattern, content):
            content = re.sub(pattern, replacement, content)
        else:
            content += f"\n$string['{key}'] = '{escaped_val}';"
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Processed {lang}")
