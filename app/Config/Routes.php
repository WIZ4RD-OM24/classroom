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
$routes->setDefaultController('DashboardController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// Auto-routing is off. With it on, every public method of every controller was
// reachable as a URL: /AdminController/loginAuth, /StudentController/add_student
// and so on bypassed the routes below along with their filters.
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Guest routes
 * --------------------------------------------------------------------
 */
$routes->group('', ['filter' => 'guest'], static function ($routes) {
    $routes->get('login', 'AuthController::showLogin', ['as' => 'login']);
    $routes->post('login', 'AuthController::login');
    $routes->get('register', 'AuthController::showRegister', ['as' => 'register']);
    $routes->post('register', 'AuthController::register');
});

$routes->post('logout', 'AuthController::logout', ['as' => 'logout']);

/*
 * --------------------------------------------------------------------
 * Authenticated routes
 * --------------------------------------------------------------------
 *
 * Every route below requires a session. Routes that change data are POST and
 * therefore also pass through the CSRF filter configured in Config\Filters.
 */
$routes->group('', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'DashboardController::index', ['as' => 'dashboard']);

    // ---- Profile ----------------------------------------------------
    $routes->get('profile', 'ProfileController::show', ['as' => 'profile']);
    $routes->get('profile/edit', 'ProfileController::edit', ['as' => 'profile.edit']);
    $routes->post('profile', 'ProfileController::update');

    // ---- Protected file downloads -----------------------------------
    // Uploads live outside the web root and are streamed only to members of
    // the owning organisation.
    $routes->get('files/(:segment)/(:segment)', 'FileController::show/$1/$2', ['as' => 'file.show']);

    // ---- Read-only listings (students may view) ---------------------
    $routes->get('assignments', 'AssignmentController::index', ['as' => 'assignments']);
    $routes->get('assignments/(:num)', 'AssignmentController::show/$1', ['as' => 'assignments.show']);
    $routes->get('notices', 'NoticeController::index', ['as' => 'notices']);
    $routes->get('classworks', 'ClassworkController::index', ['as' => 'classworks']);

    // ---- Student submissions ----------------------------------------
    $routes->post('assignments/(:num)/submit', 'AssignmentController::submit/$1', [
        'as'     => 'assignments.submit',
        'filter' => 'role:student',
    ]);
});

/*
 * --------------------------------------------------------------------
 * Management routes (admins and teachers)
 * --------------------------------------------------------------------
 */
$routes->group('', ['filter' => 'role:admin,teacher'], static function ($routes) {
    // ---- Classes ----------------------------------------------------
    $routes->get('classes', 'ClassController::index', ['as' => 'classes']);
    $routes->get('classes/new', 'ClassController::create', ['as' => 'classes.new']);
    $routes->post('classes', 'ClassController::store', ['as' => 'classes.store']);
    $routes->get('classes/(:num)/edit', 'ClassController::edit/$1', ['as' => 'classes.edit']);
    $routes->post('classes/(:num)', 'ClassController::update/$1', ['as' => 'classes.update']);
    $routes->post('classes/(:num)/delete', 'ClassController::delete/$1', ['as' => 'classes.delete']);

    // ---- Subjects ---------------------------------------------------
    $routes->get('subjects', 'SubjectController::index', ['as' => 'subjects']);
    $routes->get('subjects/new', 'SubjectController::create', ['as' => 'subjects.new']);
    $routes->post('subjects', 'SubjectController::store', ['as' => 'subjects.store']);
    $routes->get('subjects/(:num)/edit', 'SubjectController::edit/$1', ['as' => 'subjects.edit']);
    $routes->post('subjects/(:num)', 'SubjectController::update/$1', ['as' => 'subjects.update']);
    $routes->post('subjects/(:num)/delete', 'SubjectController::delete/$1', ['as' => 'subjects.delete']);

    // ---- Students ---------------------------------------------------
    $routes->get('students', 'StudentController::index', ['as' => 'students']);
    $routes->get('students/new', 'StudentController::create', ['as' => 'students.new']);
    $routes->post('students', 'StudentController::store', ['as' => 'students.store']);
    $routes->get('students/import', 'StudentController::importForm', ['as' => 'students.import']);
    $routes->post('students/import', 'StudentController::import', ['as' => 'students.import.run']);
    $routes->get('students/(:num)', 'StudentController::show/$1', ['as' => 'students.show']);
    $routes->get('students/(:num)/edit', 'StudentController::edit/$1', ['as' => 'students.edit']);
    $routes->post('students/(:num)', 'StudentController::update/$1', ['as' => 'students.update']);
    $routes->post('students/(:num)/delete', 'StudentController::delete/$1', ['as' => 'students.delete']);

    // ---- Teachers ---------------------------------------------------
    $routes->get('teachers', 'TeacherController::index', ['as' => 'teachers']);
    $routes->get('teachers/new', 'TeacherController::create', ['as' => 'teachers.new']);
    $routes->post('teachers', 'TeacherController::store', ['as' => 'teachers.store']);
    $routes->get('teachers/(:num)', 'TeacherController::show/$1', ['as' => 'teachers.show']);
    $routes->get('teachers/(:num)/edit', 'TeacherController::edit/$1', ['as' => 'teachers.edit']);
    $routes->post('teachers/(:num)', 'TeacherController::update/$1', ['as' => 'teachers.update']);
    $routes->post('teachers/(:num)/delete', 'TeacherController::delete/$1', ['as' => 'teachers.delete']);

    // ---- Assignments ------------------------------------------------
    $routes->get('assignments/new', 'AssignmentController::create', ['as' => 'assignments.new']);
    $routes->post('assignments', 'AssignmentController::store', ['as' => 'assignments.store']);
    $routes->post('assignments/(:num)/delete', 'AssignmentController::delete/$1', ['as' => 'assignments.delete']);

    // ---- Notices ----------------------------------------------------
    $routes->get('notices/new', 'NoticeController::create', ['as' => 'notices.new']);
    $routes->post('notices', 'NoticeController::store', ['as' => 'notices.store']);
    $routes->post('notices/(:num)/delete', 'NoticeController::delete/$1', ['as' => 'notices.delete']);

    // ---- Classwork --------------------------------------------------
    $routes->get('classworks/new', 'ClassworkController::create', ['as' => 'classworks.new']);
    $routes->post('classworks', 'ClassworkController::store', ['as' => 'classworks.store']);
    $routes->post('classworks/(:num)/delete', 'ClassworkController::delete/$1', ['as' => 'classworks.delete']);
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
