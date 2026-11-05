<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| MediCore role access: the single place that decides what each role sees.
|
| menu    : the only sidebar links shown to the role.
| pages   : what the role may open. "class" allows a whole controller and
|           "class/method" allows one page of it.
| blocked : "class/method" pages refused even when their class is in pages.
|
| Everything not listed is hidden. Names in pages and blocked are lowercase.
*/

// Open to every logged-in user.
$config['common_menu'] = array('home', 'profile', 'auth/logout');
$config['common_pages'] = array('home', 'auth', 'profile', 'frontend', 'site', 'api', 'cronjobs', 'macro');

// These roles can no longer log in.
$config['disabled_roles'] = array('Pharmacist');

// Removed from package and plan lists.
$config['hidden_modules'] = array('pharmacist', 'pharmacy', 'donor', 'ambulance', 'inventory', 'dental', 'radiology', 'meeting', 'pgateway');

// Refused for AJAX calls too, not only for pages.
$config['strict_classes'] = array('ai_patient_overview', 'ai_image_analysis', 'treatment_plan');

// Sidebar sections, in display order. A top-level item joins the section of its first link.
$config['menu_groups'] = array(
    'menu_group_main' => array('home', 'emergency'),
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

$config['role_access'] = array(
    'superadmin' => array(
        'menu' => array(
            'hospital', 'hospital/addNewView', 'hospital/package', 'hospital/package/addNewView', 'request',
            'systems/activeHospitals', 'systems/inactiveHospitals', 'systems/expiredHospitals',
            'systems/registeredPatient', 'systems/registeredDoctor', 'hospital/reportSubscription',
            'frontend', 'frontend/settings', 'slide', 'service', 'faq',
            'email/superadminSendView', 'email/sent', 'email/emailSettings', 'email/contactEmailSettings',
            'settings', 'settings/googleReCaptcha', 'settings/language',
        ),
        'pages' => array('hospital', 'package', 'request', 'systems', 'slide', 'service', 'faq', 'email', 'settings'),
        'blocked' => array(),
    ),
    'admin' => array(
        'menu' => array(
            'department', 'doctor', 'doctor/addnewview', 'doctorvisit', 'schedule',
            'nurse', 'laboratorist', 'accountant', 'receptionist',
            'payroll', 'payroll/salary', 'attendance', 'attendance/report', 'leave', 'leave/leaveType',
            'patient', 'patient/addnewview', 'patient/patientPayments', 'patient/caseList', 'symptom', 'diagnosis',
            'treatment', 'advice', 'patient/documents', 'emergency',
            'ai_patient_overview', 'ai_image_analysis', 'treatment_plan',
            'finance/addPaymentView', 'finance/payment', 'finance/draftPayment', 'finance/dueCollection',
            'finance/paymentCategory', 'finance/addPaymentCategoryView', 'finance/category',
            'finance/expense', 'finance/addExpenseView', 'finance/expenseCategory', 'insurance',
            'finance/financialReport', 'finance/AllUserActivityReport', 'finance/insuranceReport',
            'finance/doctorsCommission', 'finance/monthly', 'finance/daily', 'finance/monthlyExpense',
            'finance/dailyExpense', 'finance/expenseVsIncome',
            'lab/testStatus', 'lab', 'lab/reportDelivery', 'lab/template',
            'bed/bedAllotment', 'bed/addAllotmentView', 'bed', 'bed/addBedView', 'bed/bedCategory',
            'medicine', 'medicine/addMedicineView', 'medicine/medicineCategory', 'medicine/addCategoryView',
            'medicine/medicineStockAlert', 'medicine/suppliers', 'medicine/addSupplierView', 'medicine/purchases',
            'medicine/addPurchaseView', 'medicine/batches', 'medicine/expiringMedicines',
            'notice', 'notice/addNewView', 'email/autoEmailTemplate', 'email/sendView', 'email/sent', 'email/settings',
            'sms/autoSMSTemplate', 'sms/sendView', 'sms/sent', 'sms', 'chat',
            'site/settings', 'site/review', 'site/gridsection', 'site/gallery', 'site/slide', 'site/service', 'site/featured',
            'settings', 'settings/chatgpt', 'settings/language', 'import', 'transactionLogs', 'logs',
            'settings/subscription', 'file', 'file/addNewView',
        ),
        'pages' => array_merge($clinical_pages, array(
            'department', 'doctor', 'doctorvisit', 'schedule', 'nurse', 'laboratorist', 'accountant', 'receptionist',
            'payroll', 'attendance', 'leave', 'emergency', 'ai_patient_overview', 'ai_image_analysis', 'treatment_plan',
            'finance', 'insurance', 'lab', 'bed', 'medicine', 'notice', 'email', 'sms', 'chat',
            'review', 'gridsection', 'gallery', 'slide', 'service', 'featured', 'settings', 'import', 'logs', 'file',
        )),
        'blocked' => $holiday_pages,
    ),
    'Doctor' => array(
        'menu' => array_merge($clinical_menu, array(
            'patient/addnewview', 'patient/patientPayments', 'prescription',
            'lab/testStatus', 'lab', 'lab/template',
            'ai_patient_overview', 'ai_image_analysis', 'treatment_plan', 'emergency',
            'schedule/timeSchedule', 'schedule/holidays',
        )),
        'pages' => array_merge($clinical_pages, array(
            'prescription', 'lab', 'ai_patient_overview', 'ai_image_analysis', 'treatment_plan', 'emergency', 'schedule',
            'finance/patientpaymenthistory', 'finance/invoice', 'finance/printinvoice',
        )),
        'blocked' => array('lab/reportdelivery', 'schedule/allholidays'),
    ),
    'Nurse' => array(
        'menu' => array_merge($clinical_menu, $report_menu, array(
            'patient/addnewview', 'emergency', 'schedule',
            'appointment', 'appointment/addNewView', 'appointment/todays', 'appointment/upcoming',
            'appointment/calendar', 'appointment/request',
            'bed', 'bed/bedCategory', 'bed/bedAllotment',
        )),
        'pages' => array_merge($clinical_pages, array('emergency', 'schedule', 'appointment', 'bed', 'report')),
        'blocked' => $holiday_pages,
    ),
    'Receptionist' => array(
        'menu' => array(
            'patient', 'patient/addnewview', 'schedule', 'appointment/calendar',
            'finance/dueCollection', 'finance/UserActivityReport', 'lab/testStatus', 'lab', 'bed',
        ),
        'pages' => array(
            'patient', 'schedule', 'appointment/calendar', 'lab', 'bed',
            'finance/duecollection', 'finance/deposit', 'finance/useractivityreport',
            'finance/patientpaymenthistory', 'finance/invoice', 'finance/printinvoice',
        ),
        'blocked' => array_merge($holiday_pages, array(
            'lab/reportdelivery', 'lab/template', 'bed/bedallotment', 'bed/addallotmentview',
            'bed/addbedview', 'bed/bedcategory',
        )),
    ),
    'Accountant' => array(
        'menu' => array(
            'patient', 'patient/patientPayments', 'finance/payment', 'finance/addPaymentView', 'finance/dueCollection',
            'finance/paymentCategory', 'finance/expense', 'finance/addExpenseView', 'finance/expenseCategory',
            'finance/doctorsCommission', 'finance/financialReport', 'finance/UserActivityReport',
        ),
        'pages' => array('patient', 'finance'),
        'blocked' => array(
            'patient/addnewview', 'finance/insurancereport', 'finance/draftpayment', 'finance/monthly', 'finance/daily',
            'finance/monthlyexpense', 'finance/dailyexpense', 'finance/expensevsincome',
        ),
    ),
    'Laboratorist' => array(
        'menu' => array_merge($clinical_menu, $report_menu, array(
            'lab/testStatus', 'lab', 'lab/reportDelivery', 'lab/template',
        )),
        'pages' => array_merge($clinical_pages, array('lab', 'report')),
        'blocked' => array('patient/addnewview'),
    ),
    'Patient' => array(
        'menu' => array(
            'patient/myCaseList', 'patient/myPrescription', 'patient/myDocuments', 'patient/myPaymentHistory',
            'appointment/myAppointments', 'appointment/myTodays', 'patient/calendar', 'lab/myLab', 'report/myreports',
        ),
        'pages' => array('patient', 'appointment', 'lab', 'report', 'prescription', 'finance/invoice', 'finance/printinvoice'),
        'blocked' => array(),
    ),
);
