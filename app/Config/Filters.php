<?php

namespace Config;

use App\Filters\FilterPengunjung;
use App\Filters\FilterAdmin;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'filterPengunjung' => FilterPengunjung::class,
        'filterAdmin' => FilterAdmin::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            // 'honeypot',
            // 'csrf',
            // 'invalidchars',
            'filterPengunjung' => [
                'except' => [
                    '/', 'home',
                    '/about-us',
                    'blog', 'blog/*',
                    'contact',
                    'ubah-profile',
                    'destination', 'destination/*',
                    'home', 'home/index',
                    'login', 'login/*',
                    'loginWithGoogle',
                    'lupa-password',
                    'ubah-password/*',
                    'register',
                    'verifikasi/*',
                    'tampil-tiket'
                ]
            ],
            'filterAdmin' => [
                'except' => [
                    '/',
                    '/about-us',
                    'blog', 'blog/*',
                    'contact',
                    'destination', 'destination/*',
                    'home', 'home/*',
                    'login', 'login/*',
                    'loginWithGoogle',
                    'lupa-password',
                    'ubah-password/*',
                    'register',
                    'verifikasi/*',
                    'tampil-tiket'
                ]
            ],
        ],
        'after' => [
            'toolbar',
            // 'honeypot',
            // 'secureheaders',
            'filterPengunjung' => [
                'except' => [
                    '/', 'home',
                    'about-us',
                    'blog', 'blog/*',
                    'contact',
                    'destination', 'destination/*',
                    'profile',
                    'ubah-profile',
                    'ubah-password',
                    'tampil-tiket',
                    'transaksi/*',
                    'keluar'
                ]
            ],
            'filterAdmin' => [
                'except' => [
                    '/', 'home',
                    '/about-us',
                    'blog', 'blog/*',
                    'contact',
                    'destination', 'destination/*',
                    'tampil-tiket',
                    'admin', 'admin/*',
                    'keluar'
                ]
            ]
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['foo', 'bar']
     *
     * If you use this, you should disable auto-routing because auto-routing
     * permits any HTTP method to access a controller. Accessing the controller
     * with a method you don’t expect could bypass the filter.
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [];
}
