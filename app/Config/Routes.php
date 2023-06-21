<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(true);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/about-us', 'Home::about_us');
$routes->get('/blog', 'Home::blog');
$routes->get('/blog/(:any)', 'Home::blog/$1');
$routes->get('/contact', 'Home::contact');
$routes->get('/destination', 'Home::destination');
$routes->get('/destination/(:any)', 'Home::destination/$1');
$routes->post('/tampil-tiket', 'Home::tampil_tiket');

// profile
$routes->get('/profile', 'Home::profile');
$routes->post('/ubah-profile', 'Home::ubah_profile');
$routes->post('/ubah-password', 'Home::ubah_password');

// auth
$routes->get('/loginWithGoogle', 'Login::loginWithGoogle');
$routes->get('/register', 'Login::register');
$routes->get('/lupa-password', 'Login::lupa_password');
$routes->get('/verifikasi/(:any)/(:any)', 'Login::verifikasi/$1/$2');
$routes->get('/ubah-password/(:any)/(:any)', 'Login::ubah_password/$1/$2');
$routes->get('/keluar', 'Login::keluar');

// hapus
$routes->delete('/admin/users/delete/(:any)', 'Admin\Users::delete/$1');
$routes->delete('/admin/users/forceDelete/(:any)', 'Admin\Users::forceDelete/$1');

// hapus
$routes->delete('/admin/blog/delete/(:any)', 'Admin\Blog::delete/$1');
$routes->delete('/admin/blog/forceDelete/(:any)', 'Admin\Blog::forceDelete/$1');

// hapus
$routes->delete('/admin/transaksioffline/delete/(:any)', 'Admin\TransaksiOffline::delete/$1');

// hapus
$routes->delete('/admin/wisata/delete/(:any)', 'Admin\Wisata::delete/$1');
$routes->delete('/admin/wisata/forceDelete/(:any)', 'Admin\Wisata::forceDelete/$1');

// hapus temp
$routes->delete('/transaksi/hapusTemp/(:any)', 'Transaksi::hapusTemp/$1');

// hapus transaksi
$routes->delete('/transaksi/hapusTransaksi/(:any)', 'Transaksi::hapusTransaksi/$1');

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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
