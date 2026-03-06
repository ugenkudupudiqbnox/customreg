<?php
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Course settings for Custom Registration';
$string['pendingapproval'] = 'Your registration is pending approval.';

$string['areyouexisting'] = 'Are you an existing member?';
$string['existingmember'] = "I'm an existing Employee/Student";
$string['newmember'] = "I'm not";
$string['institutionid'] = 'Institution ID';

$string['manageusers'] = 'Manage Custom Registrations';
$string['approve'] = 'Approve';
$string['deny'] = 'Deny';
$string['identitytype'] = 'Identity Type';
$string['documentstatus'] = 'Document Status';
$string['action'] = 'Action';
$string['uploaded'] = 'Uploaded';
$string['notuploaded'] = 'Not Uploaded';
$string['approved'] = 'Approved';
$string['pending'] = 'Pending';
$string['denied'] = 'Denied';
$string['customreg:manage'] = 'Manage custom registrations';
$string['userapproved'] = 'User has been approved.';
$string['userdenied'] = 'User registration has been denied. The user can now re-upload their ID.';
$string['searchusers'] = 'Search users or email';
$string['uploadagain'] = 'Your previous document was not accepted. Please upload a clear copy of your Government ID again.';
$string['availablecourses'] = 'Available Courses for Registration';
$string['availablecourses_desc'] = 'Select the courses that users can choose during the signup process (max 5).';
$string['selectcourses'] = 'Select Courses';
$string['maxcoursesreached'] = 'You can select a maximum of 5 courses.';
$string['atleastonecourse'] = 'You must select at least one course.';
$string['approvecourse'] = 'Approve Course';
$string['denycourse'] = 'Deny Course';
$string['approveallcourses'] = 'Approve All Courses';
$string['enrollmentstatus'] = 'Enrollment Status';
$string['coursetitle'] = 'Course Title';
$string['enrollsuccess'] = 'User enrolled into course successfully.';
$string['alreadyenrolled'] = 'User is already enrolled in this course.';

$string['course1'] = 'First Course';
$string['course2'] = 'Second Course';
$string['course3'] = 'Third Course';
$string['course4'] = 'Fourth Course';
$string['course5'] = 'Fifth Course';
$string['selectacourse'] = 'Select a course';

$string['admin_comments'] = 'Admin Comments';
$string['deny_with_reason'] = 'Reject with Reason';
$string['email_admin_subject'] = 'New ID Upload: {$a->username}';
$string['email_admin_body'] = 'User {$a->username} has uploaded their ID for review.
You can review the request here: {$a->url}';
$string['email_approved_subject'] = 'Course Enrollment Approved';
$string['email_approved_body'] = 'Hello {$a->firstname},

Your registration has been approved. You have been enrolled in the following courses:
{$a->courses}

Admin Comments: {$a->comments}

You can now log in and access your courses: {$a->sitelink}';
$string['email_rejected_subject'] = 'Registration Rejected';
$string['email_rejected_body'] = 'Hello {$a->firstname},

Unfortunately, your registration has been rejected.

Admin Comments: {$a->comments}

Please log in and follow the instructions to re-upload your documentation: {$a->uploadurl}';
$string['email_course_approved_subject'] = 'Course Enrollment Approved: {$a->coursename}';
$string['email_course_approved_body'] = 'Hello {$a->firstname},

Your request to join the course "{$a->coursename}" has been approved.

Admin Comments: {$a->comments}

You can now access the course here: {$a->courseurl}';
$string['notapplicable'] = 'N/A';
$string['default_approve_comment'] = 'Your registration has been approved following our manual review.';
$string['default_deny_comment'] = 'Your documentation was insufficient or did not match our requirements.';
$string['default_approve_course_comment'] = 'Your enrollment in this course has been approved.';
$string['default_deny_course_comment'] = 'You do not meet the prerequisites or required criteria for this specific course.';
$string['downloadcsv'] = 'Download CSV';
$string['no_records'] = 'No records found.';
$string['csv_firstname'] = 'First Name';
$string['csv_lastname'] = 'Last Name';
$string['csv_email'] = 'Email';
$string['csv_institutionid'] = 'Institution ID';
$string['csv_status'] = 'Global Status';
$string['csv_courses'] = 'Requested Courses';
$string['csv_timecreated'] = 'Created On';
