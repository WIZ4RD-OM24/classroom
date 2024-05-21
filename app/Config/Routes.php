<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'AdminController::index',['filter' => 'authGuard']);
$routes->get('/view','AdminController::page');


//-----------------ADMIN ROUTES------------------------
$routes->get('/xyz', 'AdminController::signup');
$routes->get('/signin', 'AdminController::signin');
$routes->get('/logout','AdminController::logout');
$routes->get('/admin-profile','AdminController::view_admin');
$routes->get('/view-edit-admin-profile','AdminController::view_edit_admin_profile');
$routes->post('/edit-admin-profile','AdminController::edit_profile');


//-----------------CLASS ROUTES------------------------
$routes->get('/classes','ClassController::index',['filter' => 'authGuard']);
$routes->get('/add-class','ClassController::add_class');
$routes->get('/view-add-class',function(){session(); return view('class/add_class');});
$routes->post('/update-class', 'ClassController::update_class');
$routes->get('/delete-class/(:num)', 'ClassController::delete_class/$1');
$routes->get('/edit-class/(:num)', 'ClassController::singleClass/$1');
//$routes->get('/edit-class)', function(){return view('class/edit_class',$data);});


//-----------------STUDENT ROUTES------------------------
$routes->get('/students','StudentController::index',['filter' => 'authGuard']);
$routes->get('/add-student','StudentController::add_student');
$routes->post('/add-bulk-student', 'StudentController::add_bulk_student');
// $routes->get('/view-add-student',function(){session(); return view('student/add_student');});
$routes->get('/view-add-student','StudentController::view_add_student',['filter' => 'authGuard']);
$routes->get('/view-add-bulk-student',function(){session(); return view('student/add_bulk_student');});
$routes->post('/update-student', 'StudentController::update_student');
$routes->get('/view-student/(:num)', 'StudentController::singleStudent/$1');
$routes->get('/delete-student/(:num)', 'StudentController::delete_student/$1');
$routes->get('/view-student',function(){session(); return view('student/view_student_profile_by_admin');});


//-----------------SUBJECTS ROUTES------------------------
$routes->get('/subjects','SubjectController::index',['filter' => 'authGuard']);
$routes->get('/view-add-subject','SubjectController::view_add_subject',['filter' => 'authGuard']);
$routes->get('/delete-subject/(:num)', 'SubjectController::delete_subject/$1');
$routes->get('/edit-subject/(:num)', 'SubjectController::singleSubject/$1');

 
//-----------------ASSIGNMENT ROUTES----------------------
$routes->get('/assignments','AssignmentController::index',['filter' => 'authGuard']);
$routes->get('/view-post-assignment','AssignmentController::view_post_assignment',['filter' => 'authGuard']);
$routes->post('/post-assignment','AssignmentController::post_assignment');
$routes->get('/view-assignment/(:num)', 'AssignmentController::edit_assignment/$1');
$routes->get('/delete-assignment/(:num)', 'AssignmentController::delete_assignment/$1');

//-----------------TEACHER ROUTES------------------------
$routes->get('/teachers','TeacherController::index',['filter' => 'authGuard']);
$routes->get('/add-teacher','TeacherController::add_teacher');
$routes->get('/view-add-teacher','TeacherController::view_add_teacher',['filter' => 'authGuard']);
$routes->post('/update-teacher', 'TeacherController::update_teacher');
$routes->get('/view-teacher/(:num)', 'TeacherController::singleTeacher/$1');
$routes->get('/delete-teacher/(:num)', 'TeacherController::delete_teacher/$1');
$routes->get('/view-teacher',function(){session(); return view('teacher/view_teacher_profile_by_admin');});


$routes->get('/stud_view', 'CsvController::index');


//-----------------NOTICE ROUTES------------------------
$routes->get('/notices','NoticeController::index',['filter' => 'authGuard']);
$routes->get('/view-add-notice','NoticeController::view_add_notice',['filter' => 'authGuard']);
$routes->post('/add-notice','NoticeController::add_notice');
$routes->get('/delete-notice/(:num)', 'NoticeController::delete_notice/$1');


//-----------------CLASSWORK ROUTES------------------------
$routes->get('/classworks','ClassworkController::index',['filter' => 'authGuard']);
$routes->get('/view-add-classwork','ClassworkController::view_add_classwork',['filter' => 'authGuard']);
$routes->post('/add-classwork','ClassworkController::add_classwork');
$routes->get('/delete-classwork/(:num)', 'ClassworkController::delete_classwork/$1');

$routes->get('/viewassignment','StudentController::view_assignments');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
