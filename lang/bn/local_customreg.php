<?php

$string['pluginname'] = 'কাস্টম রেজিস্ট্রেশনের জন্য কোর্স সেটিংস';
$string['pendingapproval'] = 'আপনার রেজিস্ট্রেশন অনুমোদনের জন্য অপেক্ষমান।';

$string['areyouexisting'] = 'আপনি কি একজন বর্তমান সদস্য?';
$string['existingmember'] = "আমি একজন বর্তমান কর্মচারী/ছাত্র";
$string['newmember'] = "আমি নই";
$string['institutionid'] = 'প্রতিষ্ঠান আইডি';

$string['manageusers'] = 'কাস্টম রেজিস্ট্রেশন পরিচালনা করুন';
$string['approve'] = 'অনুমোদন করুন';
$string['deny'] = 'প্রত্যাখ্যান করুন';
$string['identitytype'] = 'পরিচয়ের ধরণ';
$string['documentstatus'] = 'নথির স্থিতি';
$string['action'] = 'অ্যাকশন';
$string['uploaded'] = 'আপলোড করা হয়েছে';
$string['notuploaded'] = 'আপলোড করা হয়নি';
$string['approved'] = 'অনুমোদিত';
$string['pending'] = 'অপেক্ষমান';
$string['denied'] = 'প্রত্যাখ্যাত';
$string['customreg:manage'] = 'কাস্টম রেজিস্ট্রেশন পরিচালনা করুন';
$string['userapproved'] = 'ব্যবহারকারী অনুমোদিত হয়েছে।';
$string['userdenied'] = 'ব্যবহারকারীর রেজিস্ট্রেশন প্রত্যাখ্যাত হয়েছে। ব্যবহারকারী এখন আবার তাদের আইডি আপলোড করতে পারেন।';
$string['searchusers'] = 'ব্যবহারকারী বা ইমেল খুঁজুন';
$string['uploadagain'] = 'আপনার আগের নথিটি গ্রহণ করা হয়নি। অনুগ্রহ করে আপনার সরকারি আইডির একটি পরিষ্কার কপি আবার আপলোড করুন।';
$string['availablecourses'] = 'রেজিস্ট্রেশনের জন্য উপলব্ধ কোর্স';
$string['availablecourses_desc'] = 'সাইনআপ প্রক্রিয়ার সময় ব্যবহারকারীরা যে কোর্সগুলো বেছে নিতে পারেন তা নির্বাচন করুন (সর্বোচ্চ ৫টি)।';
$string['selectcourses'] = 'কোর্স নির্বাচন করুন';
$string['maxcoursesreached'] = 'আপনি সর্বোচ্চ ৫টি কোর্স নির্বাচন করতে পারেন।';
$string['atleastonecourse'] = 'আপনাকে অন্তত একটি কোর্স নির্বাচন করতে হবে।';
$string['approvecourse'] = 'কোর্স অনুমোদন করুন';
$string['denycourse'] = 'কোর্স প্রত্যাখ্যান করুন';
$string['approveallcourses'] = 'সমস্ত কোর্স অনুমোদন করুন';
$string['enrollmentstatus'] = 'তালিকাভুক্তির স্থিতি';
$string['coursetitle'] = 'কোর্সের শিরোনাম';
$string['enrollsuccess'] = 'ব্যবহারকারী সফলভাবে কোর্সে তালিকাভুক্ত হয়েছেন।';
$string['alreadyenrolled'] = 'ব্যবহারকারী ইতিমধ্যে এই কোর্সে তালিকাভুক্ত।';

$string['course1'] = 'প্রথম কোর্স';
$string['course2'] = 'দ্বিতীয় কোর্স';
$string['course3'] = 'তৃতীয় কোর্স';
$string['course4'] = 'চতুর্থ কোর্স';
$string['course5'] = 'পঞ্চম কোর্স';
$string['selectacourse'] = 'একটি কোর্স নির্বাচন করুন';

$string['admin_comments'] = 'অ্যাডমিন মন্তব্য';
$string['deny_with_reason'] = 'কারণসহ প্রত্যাখ্যান করুন';
$string['email_admin_subject'] = 'নতুন আইডি আপলোড: {$a->username}';
$string['email_admin_body'] = 'ব্যবহারকারী {$a->username} পর্যালোচনার জন্য তাদের আইডি আপলোড করেছেন।\nআপনি এখানে অনুরোধটি পর্যালোচনা করতে পারেন: {$a->url}';
$string['email_approved_subject'] = 'কোর্স এনরোলমেন্ট অনুমোদিত';
$string['email_approved_body'] = 'হ্যালো {$a->firstname},\n\nআপনার নিবন্ধন অনুমোদিত হয়েছে। আপনি নিম্নলিখিত কোর্সগুলিতে নথিভুক্ত হয়েছেন:\n{$a->courses}\n\nঅ্যাডমিন মন্তব্য: {$a->comments}\n\nআপনি এখন লগ ইন করতে এবং আপনার কোর্সগুলি অ্যাক্সেস করতে পারেন।';
$string['email_rejected_subject'] = 'নিবন্ধন প্রত্যাখ্যাত';
$string['email_rejected_body'] = 'হ্যালো {$a->firstname},\n\nদুর্भाग्यবশত, আপনার নিবন্ধন প্রত্যাখ্যাত হয়েছে।\n\nঅ্যাডমিন মন্তব্য: {$a->comments}\n\nঅনুগ্রহ করে লগ ইন করুন এবং আপনার ডকুমেন্টেশন পুনরায় আপলোড করার নির্দেশাবলী অনুসরণ করুন।';
$string['email_course_approved_subject'] = 'কোর্স এনরোলমেন্ট অনুমোদিত: {$a->coursename}';
$string['email_course_approved_body'] = 'হ্যালো {$a->firstname},\n\n\"{$a->coursename}\" কোর্সে যোগদানের আপনার অনুরোধ অনুমোদিত হয়েছে।\n\nঅ্যাডমিন মন্তব্য: {$a->comments}\n\nআপনি এখন কোর্সটি অ্যাক্সেস করতে পারেন।';
$string['notapplicable'] = 'প্রযোজ্য নয়';
$string['default_approve_comment'] = 'আমাদের ম্যানুয়াল পর্যালোচনার পরে আপনার নিবন্ধন অনুমোদিত হয়েছে।';
$string['default_deny_comment'] = 'আপনার ডকুমেন্টেশন অপর্যাপ্ত ছিল বা আমাদের প্রয়োজনীয়তার সাথে মেলেনি।';
$string['default_approve_course_comment'] = 'এই কোর্সে আপনার এনরোলমেন্ট অনুমোদিত হয়েছে।';
$string['default_deny_course_comment'] = 'আপনি এই নির্দিষ্ট কোর্সের জন্য পূর্বশর্ত বা প্রয়োজনীয় মানদণ্ড পূরণ করেন না। ';
