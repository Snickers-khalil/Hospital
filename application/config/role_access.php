<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| MediCore role access: the single place that decides what each role sees.
|
| menu    : the only sidebar links shown to the role.
| pages   : what the role may open. "class" allows a whole controller and
|           "class/method" allows one page of it.
| blocked : "class/method" pages refused even when their class is in pages.
| readonly: classes whose add and edit buttons are hidden for the role.
|
| Everything not listed is hidden. Names in pages and blocked are lowercase.
*/

// Open to every logged-in user.
$config['common_menu'] = array('home', 'profile', 'auth/logout');
$config['common_pages'] = array('home', 'auth', 'profile', 'frontend', 'site', 'api', 'cronjobs', 'macro');

// These roles can no longer log in.
$config['disabled_roles'] = array('Pharmacist');

// Removed from package and plan lists.
$config['hidden_modules'] = array('pharmacist', 'pharmacy', 'donor', 'ambulance', 'inventory', 'dental', 'radiology', 'meeting', 'pgateway', 'emergency', 'payroll', 'attendance', 'chat');

// Refused for AJAX calls too, not only for pages.
$config['strict_classes'] = array('ai_patient_overview', 'ai_image_analysis', 'treatment_plan');

// Sidebar sections, in display order. A top-level item joins the section of its first link.
$config['menu_groups'] = array(
    'menu_group_main' => array('home'),
    'menu_group_hospitals' => array('hospital', 'request', 'systems'),
    'menu_group_organisation' => array('department', 'doctor', 'nurse', 'schedule'),
    'menu_group_patients' => array('patient', 'appointment', 'prescription'),
    'menu_group_clinical' => array('lab', 'bed', 'medicine', 'report'),
    'menu_group_ai' => array('ai_patient_overview', 'ai_image_analysis', 'treatment_plan'),
    'menu_group_finance' => array('finance', 'insurance'),
    'menu_group_administration' => array('notice', 'email', 'sms', 'chat', 'site', 'frontend', 'slide', 'service', 'faq', 'settings', 'import', 'logs', 'transactionlogs', 'file'),
    'menu_group_account' => array('profile', 'auth'),
);

$clinical_pages = array('patient', 'symptom', 'diagnosis', 'treatment', 'advice');
$clinical_menu = array('patient', 'patient/caseList', 'symptom', 'diagnosis', 'treatment', 'advice', 'patient/documents');
$report_menu = array('report/birth', 'report/operation', 'report/expire');
$holiday_pages = array('schedule/allholidays', 'schedule/holidays');
$appointment_view_menu = array('appointment', 'appointment/todays', 'appointment/upcoming', 'appointment/calendar');
$appointment_write_pages = array(
    'appointment/addnewview', 'appointment/addnewviewquick', 'appointment/addnew', 'appointment/editappointment',
    'appointment/delete', 'appointment/request',
);
$bed_write_pages = array(
    'bed/addbedview', 'bed/addbed', 'bed/editbed', 'bed/delete', 'bed/bedcategory', 'bed/addcategoryview',
    'bed/addcategory', 'bed/editcategory', 'bed/deletebedcategory', 'bed/addallotmentview', 'bed/addallotmentviewquick',
    'bed/editallotmentview', 'bed/addallotment', 'bed/editallotment', 'bed/deleteallotment',
);
$prescription_write_pages = array(
    'prescription/addprescriptionview', 'prescription/addnewprescription', 'prescription/addnewprescriptionquick',
    'prescription/editprescription', 'prescription/delete',
);
$schedule_write_pages = array(
    'schedule/addschedule', 'schedule/deleteschedule', 'schedule/delete', 'schedule/timeslots', 'schedule/addtimeslot',
    'schedule/deletetimeslot', 'schedule/addholiday', 'schedule/deleteholiday',
);
$expense_pages = array(
    'finance/expense', 'finance/addexpenseview', 'finance/addexpense', 'finance/editexpense', 'finance/deleteexpense',
    'finance/expensecategory', 'finance/addexpensecategoryview', 'finance/addexpensecategory',
    'finance/editexpensecategory', 'finance/deleteexpensecategory', 'finance/expenseinvoice',
    'finance/dailyexpense', 'finance/monthlyexpense', 'finance/expensevsincome',
);
$template_pages = array('lab/template', 'lab/addtemplateview', 'lab/addtemplate', 'lab/edittemplate', 'lab/deletetemplate');
$sale_menu = array('finance/pharmacy/payment', 'finance/pharmacy/addPaymentViewEnhanced');
$sale_pages = array(
    'pharmacy/payment', 'pharmacy/paymentbypagenumber', 'pharmacy/addpaymentview', 'pharmacy/addpaymentviewenhanced',
    'pharmacy/addpayment', 'pharmacy/invoice', 'pharmacy/printinvoice',
);

$config['role_access'] = array(
    'superadmin' => array(
        'menu' => array(
            'hospital', 'hospital/addNewView', 'hospital/package', 'hospital/package/addNewView', 'request',
            'systems/activeHospitals', 'systems/inactiveHospitals', 'systems/expiredHospitals',
            'systems/registeredPatient', 'systems/registeredDoctor', 'hospital/reportSubscription',
            'frontend', 'frontend/settings', 'slide', 'service', 'faq',
            'email/superadminSendView', 'email/sent', 'email/emailSettings', 'email/contactEmailSettings',
            'settings', 'settings/language',
        ),
        'pages' => array('hospital', 'package', 'request', 'systems', 'slide', 'service', 'faq', 'email', 'settings'),
        'blocked' => array('settings/googlerecaptcha', 'settings/updategooglerecaptcha'),
    ),
    'admin' => array(
        'menu' => array_merge($sale_menu, $appointment_view_menu, array(
            'department', 'doctor', 'doctor/addnewview', 'doctorvisit', 'schedule',
            'nurse', 'laboratorist', 'accountant', 'receptionist', 'leave', 'leave/leaveType',
            'patient', 'patient/addnewview', 'patient/patientPayments', 'patient/caseList', 'patient/documents',
            'finance/addPaymentView', 'finance/payment', 'finance/draftPayment', 'finance/dueCollection',
            'finance/paymentCategory', 'finance/addPaymentCategoryView',
            'finance/financialReport', 'finance/AllUserActivityReport', 'finance/doctorsCommission',
            'finance/monthly', 'finance/daily',
            'lab/testStatus', 'lab', 'lab/reportDelivery', 'lab/template',
            'bed/bedAllotment', 'bed/addAllotmentView', 'bed', 'bed/addBedView', 'bed/bedCategory',
            'medicine', 'medicine/addMedicineView', 'medicine/medicineCategory', 'medicine/addCategoryView',
            'medicine/medicineStockAlert', 'medicine/suppliers', 'medicine/addSupplierView', 'medicine/purchases',
            'medicine/addPurchaseView', 'medicine/batches', 'medicine/expiringMedicines',
            'notice', 'notice/addNewView', 'email/autoEmailTemplate', 'email/sendView', 'email/sent', 'email/settings',
            'sms/autoSMSTemplate', 'sms/sendView', 'sms/sent', 'sms',
            'site/settings', 'site/review', 'site/gridsection', 'site/gallery', 'site/slide', 'site/service', 'site/featured',
            'settings', 'settings/chatgpt', 'settings/language', 'settings/subscription', 'import', 'file', 'file/addNewView',
        )),
        'pages' => array_merge($sale_pages, array(
            'patient', 'appointment',
            'department', 'doctor', 'doctorvisit', 'schedule', 'nurse', 'laboratorist', 'accountant', 'receptionist', 'leave',
            'finance', 'lab', 'bed', 'medicine', 'notice', 'email', 'sms',
            'review', 'gridsection', 'gallery', 'slide', 'service', 'featured', 'settings', 'import', 'file',
        )),
        'blocked' => array_merge($holiday_pages, $appointment_write_pages, $expense_pages, array(
            'finance/category', 'finance/insurancereport',
        )),
        'readonly' => array('appointment'),
    ),
    'Doctor' => array(
        'menu' => array_merge($clinical_menu, $report_menu, array(
            'patient/addnewview', 'prescription',
            'appointment', 'appointment/addNewView', 'appointment/todays', 'appointment/upcoming',
            'appointment/calendar', 'appointment/request',
            'lab/testStatus', 'lab', 'bed', 'bed/bedAllotment',
            'ai_patient_overview', 'ai_image_analysis', 'treatment_plan',
            'schedule/timeSchedule', 'schedule/holidays',
        )),
        'pages' => array_merge($clinical_pages, array(
            'prescription', 'appointment', 'lab', 'bed', 'report', 'ai_patient_overview', 'ai_image_analysis',
            'treatment_plan', 'schedule',
        )),
        'blocked' => array_merge($bed_write_pages, $template_pages, array(
            'lab/reportdelivery', 'schedule/allholidays', 'patient/patientpayments',
        )),
        'readonly' => array('bed'),
    ),
    'Nurse' => array(
        'menu' => array_merge($appointment_view_menu, array(
            'schedule', 'patient', 'patient/caseList', 'patient/documents', 'prescription/all', 'bed', 'bed/bedAllotment',
        )),
        'pages' => array('patient', 'schedule', 'appointment', 'prescription', 'bed'),
        'blocked' => array_merge($holiday_pages, $appointment_write_pages, $prescription_write_pages, $schedule_write_pages, array(
            'patient/addnewview', 'patient/addnew', 'patient/addmedicalhistory', 'patient/deletecasehistory',
            'patient/addpatientmaterial', 'patient/deletepatientmaterial', 'patient/deletepatientmaterialinfolder',
            'patient/addnewfolder', 'patient/deletefolder', 'bed/bedcategory', 'bed/addcategoryview', 'bed/addcategory',
            'bed/editcategory', 'bed/deletebedcategory',
        )),
        'readonly' => array('appointment', 'schedule'),
    ),
    'Receptionist' => array(
        'menu' => array_merge($appointment_view_menu, array(
            'patient', 'patient/addnewview', 'schedule', 'appointment/addNewView', 'appointment/request',
            'finance/dueCollection', 'finance/UserActivityReport', 'lab/testStatus', 'bed',
        )),
        'pages' => array(
            'patient', 'schedule', 'appointment', 'lab/teststatus', 'bed',
            'finance/duecollection', 'finance/deposit', 'finance/useractivityreport',
            'finance/patientpaymenthistory', 'finance/invoice', 'finance/printinvoice',
        ),
        'blocked' => array_merge($holiday_pages, $schedule_write_pages, array(
            'bed/bedallotment', 'bed/addallotmentview', 'bed/addbedview', 'bed/bedcategory',
        )),
        'readonly' => array('schedule'),
    ),
    'Accountant' => array(
        'menu' => array_merge($sale_menu, array(
            'patient', 'patient/patientPayments', 'finance/payment', 'finance/addPaymentView', 'finance/dueCollection',
            'finance/paymentCategory', 'finance/doctorsCommission', 'finance/financialReport', 'finance/UserActivityReport',
        )),
        'pages' => array_merge($sale_pages, array('patient', 'finance')),
        'blocked' => array_merge($expense_pages, array(
            'patient/addnewview', 'finance/insurancereport', 'finance/draftpayment', 'finance/monthly', 'finance/daily',
        )),
    ),
    'Laboratorist' => array(
        'menu' => array('patient', 'lab/testStatus', 'lab', 'lab/reportDelivery', 'lab/template'),
        'pages' => array('patient', 'lab'),
        'blocked' => array('patient/addnewview', 'patient/addnew', 'patient/caselist', 'patient/documents', 'patient/medicalhistory'),
    ),
    'Patient' => array(
        'menu' => array(
            'patient/myCaseList', 'patient/myPrescription', 'patient/myDocuments', 'patient/myPaymentHistory',
            'appointment/myAppointments', 'appointment/myTodays', 'appointment/requestView', 'patient/calendar', 'lab/myLab', 'report/myreports',
        ),
        'pages' => array('patient', 'appointment', 'lab', 'report', 'prescription', 'finance/invoice', 'finance/printinvoice'),
        'blocked' => array(),
    ),
);
