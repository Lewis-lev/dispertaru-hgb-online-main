<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/form', 'Home::form', ['filter' => 'filterUserLogin']);
$routes->post('simpan', 'Home::simpanForm', ['filter' => 'filterUserLogin']);
$routes->set404Override('App\Controllers\Errors::notFound');

$routes->get('login', 'UserAuth::login');
$routes->post('login', 'UserAuth::authenticate');
$routes->get('daftar', 'UserAuth::signup');
$routes->post('daftar', 'UserAuth::register');
$routes->get('account', 'UserAuth::account', ['filter' => 'filterUserLogin']);
$routes->post('logout', 'UserAuth::logout', ['filter' => 'filterUserLogin']);

$routes->get('admin/login', 'Admin::login');
$routes->get('/administrator', 'Admin::index', ['filter' => 'filterLogin']);
$routes->get('/p3/(:num)', 'Admin::p3Detail/$1' ,['filter' => 'filterLogin']);
$routes->get('/p3/(:num)/edit', 'Admin::p3Edit/$1', ['filter' => 'filterLogin']);
$routes->post('/p3/(:num)/delete', 'Admin::p3Delete/$1', ['filter' => 'filterLogin']);
$routes->get('/admin/document/(:num)/(:segment)', 'Admin::document/$1/$2', ['filter' => 'filterLogin']);
$routes->get('admin/password', 'Admin::passwordForm', ['filter' => 'filterLogin']);
$routes->post('admin/password', 'Admin::updatePassword', ['filter' => 'filterLogin']);
$routes->post('/p3-update', 'Admin::p3Update', ['filter' => 'filterLogin']);
$routes->post('update-p3/status', 'Admin::updateStatusP3', ['filter' => 'filterLogin']);
$routes->post('admin/logout', 'Admin::logout', ['filter' => 'filterLogin']);

$routes->get('manager/login', 'Manager::login');
$routes->get('manager', 'Manager::index', ['filter' => 'filterManagerLogin']);
$routes->get('manager/admins', 'Manager::admins', ['filter' => 'filterManagerLogin']);
$routes->get('manager/admins/new', 'Manager::newAdmin', ['filter' => 'filterManagerLogin']);
$routes->get('manager/application/(:num)', 'Manager::detail/$1', ['filter' => 'filterManagerLogin']);
$routes->post('manager/admins', 'Manager::createAdmin', ['filter' => 'filterManagerLogin']);
$routes->post('manager/admins/(:num)', 'Manager::updateAdmin/$1', ['filter' => 'filterManagerLogin']);
$routes->post('manager/admins/(:num)/status', 'Manager::updateAdminStatus/$1', ['filter' => 'filterManagerLogin']);
$routes->post('manager/logout', 'Manager::logout', ['filter' => 'filterManagerLogin']);

$routes->get('/cek-dokumen', 'Home::cekStatusDokumen');
