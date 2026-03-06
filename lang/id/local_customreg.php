<?php

$string['pluginname'] = 'Pengaturan Kursus untuk Pendaftaran Kustom';
$string['pendingapproval'] = 'Pendaftaran Anda sedang menunggu persetujuan.';

$string['areyouexisting'] = 'Apakah Anda anggota yang sudah ada?';
$string['existingmember'] = "Saya Karyawan/Mahasiswa yang sudah ada";
$string['newmember'] = "Bukan";
$string['institutionid'] = 'ID Institusi';

$string['manageusers'] = 'Kelola Pendaftaran Kustom';
$string['approve'] = 'Setujui';
$string['deny'] = 'Tolak';
$string['identitytype'] = 'Jenis Identitas';
$string['documentstatus'] = 'Status Dokumen';
$string['action'] = 'Tindakan';
$string['uploaded'] = 'Diunggah';
$string['notuploaded'] = 'Tidak diunggah';
$string['approved'] = 'Disetujui';
$string['pending'] = 'Tertunda';
$string['denied'] = 'Ditolak';
$string['customreg:manage'] = 'Kelola pendaftaran kustom';
$string['userapproved'] = 'Pengguna telah disetujui.';
$string['userdenied'] = 'Pendaftaran pengguna telah ditolak. Pengguna sekarang dapat mengunggah kembali tanda pengenal mereka.';
$string['searchusers'] = 'Cari pengguna atau email';
$string['uploadagain'] = 'Dokumen Anda sebelumnya tidak diterima. Silakan unggah kembali salinan identitas resmi Anda yang jelas.';
$string['availablecourses'] = 'Kursus yang Tersedia untuk Pendaftaran';
$string['availablecourses_desc'] = 'Pilih kursus yang dapat dipilih pengguna selama proses pendaftaran (maks 5).';
$string['selectcourses'] = 'Pilih Kursus';
$string['maxcoursesreached'] = 'Anda dapat memilih maksimal 5 kursus.';
$string['atleastonecourse'] = 'Anda harus memilih setidaknya satu kursus.';
$string['approvecourse'] = 'Setujui Kursus';
$string['denycourse'] = 'Tolak Kursus';
$string['approveallcourses'] = 'Setujui Semua Kursus';
$string['enrollmentstatus'] = 'Status Pendaftaran';
$string['coursetitle'] = 'Judul Kursus';
$string['enrollsuccess'] = 'Pengguna berhasil didaftarkan ke kursus.';
$string['alreadyenrolled'] = 'Pengguna sudah terdaftar di kursus ini.';

$string['course1'] = 'Kursus Pertama';
$string['course2'] = 'Kursus Kedua';
$string['course3'] = 'Kursus Ketiga';
$string['course4'] = 'Kursus Keempat';
$string['course5'] = 'Kursus Kelima';
$string['selectacourse'] = 'Pilih kursus';

$string['admin_comments'] = 'Komentar Admin';
$string['deny_with_reason'] = 'Tolak dengan Alasan';
$string['email_admin_subject'] = 'Unggahan ID Baru: {$a->username}';
$string['email_admin_body'] = 'Pengguna {$a->username} telah ununggah ID mereka untuk ditinjau.
$string['email_approved_subject'] = 'Pendaftaran Kursus Disetujui';
$string['email_approved_body'] = 'Halo {$a->firstname},



$string['email_rejected_subject'] = 'Pendaftaran Ditolak';
$string['email_rejected_body'] = 'Halo {$a->firstname},



$string['email_course_approved_subject'] = 'Pendaftaran Kursus Disetujui: {$a->coursename}';
$string['email_course_approved_body'] = 'Halo {$a->firstname},



$string['notapplicable'] = 'N/A';
$string['default_approve_comment'] = 'Pendaftaran Anda telah disetujui menyusul tinjauan manual kami.';
$string['default_deny_comment'] = 'Dokumentasi Anda tidak memadai atau tidak sesuai dengan persyaratan kami.';
$string['default_approve_course_comment'] = 'Pendaftaran Anda di kursus ini telah disetujui.';
$string['default_deny_course_comment'] = 'Anda tidak memenuhi prasyarat atau kriteria yang diperlukan untuk kursus khusus ini.';

$string['email_admin_body'] = 'Pengguna {$a->username} telah ununggah ID mereka untuk ditinjau.
$string['email_approved_body'] = 'Halo {$a->firstname},



$string['email_rejected_body'] = 'Halo {$a->firstname},



$string['email_course_approved_body'] = 'Halo {$a->firstname},




$string['email_admin_body'] = 'Pengguna {$a->username} telah ununggah ID mereka untuk ditinjau.
Anda dapat meninjau permintaan di sini: {$a->url}';
$string['email_approved_body'] = 'Halo {$a->firstname},

Pendaftaran Anda telah disetujui. Anda telah terdaftar di kursus berikut:
{$a->courses}

Komentar Admin: {$a->comments}

Sekarang Anda dapat masuk dan mengakses kursus Anda.: {$a->sitelink}';
$string['email_rejected_body'] = 'Halo {$a->firstname},

Sayangnya, pendaftaran Anda telah ditolak.

Komentar Admin: {$a->comments}

Silakan masuk dan ikuti instruksi untuk mengunggah ulang dokumentasi Anda.: {$a->uploadurl}';
$string['email_course_approved_body'] = 'Halo {$a->firstname},

Permintaan Anda untuk bergabung dengan kursus \"{$a->coursename}\" telah disetujui.

Komentar Admin: {$a->comments}

Sekarang Anda dapat mengakses kursus tersebut.: {$a->courseurl}';