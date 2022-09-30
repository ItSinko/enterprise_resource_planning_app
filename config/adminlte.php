<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'title' => 'AdminLTE 3',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'logo' => '<b>Sinko Prima</b> Alloy',
    'logo_img' => 'assets/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'AdminLTE',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => 'bg-gradient-dark',
    'classes_auth_body' => 'bg-gradient-dark',
    'classes_auth_footer' => 'bg-gradient-dark',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar2' => 'sidebar-dark-primary royal-bg  elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-dark',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/7.-Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/6.-Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/9.-Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/8.-Menu-Configuration
    |
    */
    'menu' => [
        [
            'header' => ''
        ],
        [
            'text'        => 'Beranda',
            'url'         => '',
            'icon'        => 'nav-icon fa fa-home',
            'auth'        => [24]
        ],
        [
            'text'        => 'Beranda',
            'url'         => '/qc/dashboard',
            'icon'        => 'nav-icon fa fa-home',
            'auth'        => [23, 34]
        ],
        // PPIC (24)
        [
            'header' => 'Data',
            'auth' => [24]
        ],
        [
            'text'  =>  'Stok',
            'icon'  =>  'fas fa-boxes',
            'auth'  => [24],
            'submenu'   =>  [
                [
                    'text'  =>  'GBJ',
                    'icon'  =>  'far fa-circle',
                    'url'   =>  '/ppic/data/gbj'
                ],
                [
                    'text'  =>  'GK',
                    'icon'  =>  'far fa-circle',
                    'url'   =>  '/ppic/data/gk'
                ],
            ]
        ],
        // [
        //     'text'  =>  'Laporan',
        //     'icon'  =>  'fas fa-file',
        //     'auth'  => [24],
        //     'submenu'   =>  [
        //         [
        //             'text'  =>  'Pembelian',
        //             'icon'  =>  'far fa-circle',
        //             'url'   =>  '#'
        //         ],
        //         [
        //             'text'  =>  'Perakitan dan Pengemasan',
        //             'icon'  =>  'far fa-circle',
        //             'url'   =>  '#'
        //         ],
        //         [
        //             'text'  =>  'Pemeriksaan, Pengujian, dan Pemeriksaan Pengemasan QA',
        //             'icon'  =>  'far fa-circle',
        //             'url'   =>  '#'
        //         ],

        //         [
        //             'text'  =>  'Kerja GK',
        //             'icon'  =>  'far fa-circle',
        //             'url'   =>  '#'
        //         ],

        //         [
        //             'text'  =>  'Penjualan',
        //             'icon'  =>  'far fa-circle',
        //             'url'   =>  '#'
        //         ],

        //         [
        //             'text'  =>  'Marketing',
        //             'icon'  =>  'far fa-circle',
        //             'url'   =>  '#'
        //         ],
        //     ]
        // ],
        [
            'text' => 'Perakitan',
            'url'  => '/ppic/data/perakitan',
            'icon' => 'fas fa-list-alt',
            'auth' => [24]
        ],
        [
            'text' => 'SO',
            'url'  => '/ppic/data/so',
            'icon' => 'fas fa-database',
            'auth' => [24]
        ],
        [
            'header' => 'PPIC',
            'auth' => [24]
        ],
        [
            'text' => 'Jadwal Perakitan',
            'icon' => 'fas fa-calendar-alt',
            'auth' => [24],
            'submenu'   =>  [
                [
                    'text'  =>  'Rencana',
                    'icon'  =>  'far fa-circle',
                    'url'   =>  '/ppic/jadwal/penyusunan'
                ],
                [
                    'text'  =>  'Pelaksanaan',
                    'icon'  =>  'far fa-circle',
                    'url'   =>  '/ppic/jadwal/pelaksanaan',
                ],
            ]
        ],
        // [
        //     'text' => 'BOM',
        //     'url'  => '/ppic/bom',
        //     'icon' => 'fas fa-list',
        //     'auth' => [24]
        // ],
        // [
        //     'text'    => 'BPPB',
        //     'icon'    => 'fas fa-project-diagram',
        //     'url' => '/bppb',
        //     'auth' => [24],
        //     'submenu'   =>  [
        //         [
        //             'text'  =>  'Pelaksanaan',
        //             'icon'  =>  'far fa-circle',
        //             'url'   =>  '/ppic/bppb/pelaksanaan',
        //         ],
        //         [
        //             'text'  =>  'Selesai',
        //             'icon'  =>  'far fa-circle',
        //             'url'   =>  '/ppic/bppb/selesai'
        //         ],
        //     ]
        // ],

        // Manager Teknik
        [
            'header' => 'Data',
            'auth' => [3]
        ],
        [
            'text' => 'Perakitan',
            'url'  => '/ppic/data/perakitan',
            'icon' => 'fas fa-list-alt',
            'auth' => [3]
        ],
        [
            'text' => 'SO',
            'url'  => '/ppic/data/so',
            'icon' => 'fas fa-database',
            'auth' => [3]
        ],
        [
            'header' => 'PPIC',
            'auth' => [3]
        ],
        [
            'text' => 'Jadwal Perakitan',
            'icon' => 'fas fa-calendar-alt',
            'auth' => [3],
            'submenu'   =>  [
                [
                    'text'  =>  'Rencana',
                    'icon'  =>  'far fa-circle',
                    'url'   =>  '/ppic/jadwal/penyusunan'
                ],
                [
                    'text'  =>  'Pelaksanaan',
                    'icon'  =>  'far fa-circle',
                    'url'   =>  '/ppic/jadwal/pelaksanaan',
                ],
            ]
        ],
        [
            'text' => 'Persetujuan Manager',
            'url'  => '/manager-teknik/persetujuan_jadwal',
            'icon' => 'fas fa-circle',
            'auth' => [3]
        ],
        [
            'header' => 'QC',
            'auth' => [3]
        ],
        [
            'text' => 'Pengujian',
            'url'  => '/manager/qc/show',
            'icon' => 'fas fa-settings',
            'auth' => [3]
        ],

        // other
        [
            'text' => 'Beranda',
            'url'  => '/penjualan/dashboard',
            'icon' => 'fas fa-home',
            'auth' => [26, 8, 32]
        ],

        [
            'header' => 'DATA',
            'auth' => [26]
        ],
        // penjualan (26)
        [
            'text' => 'Produk Penjualan',
            'url'  => '/penjualan/produk/show',
            'icon' => 'fas fa-box-open',
            'auth' => [26]
        ],
        [
            'text' => 'Customer',
            'url'  => '/penjualan/customer/show',
            'icon' => 'fas fa-users',
            'auth' => [26]
        ],
        [
            'text' => 'Rencana Order',
            'url'  => '/penjualan/rencana/show',
            'icon' => 'fas fa-archive',
            'auth' => [26]
        ],
        [
            'text' => 'Laporan',
            'url'  => '/penjualan/laporan/show',
            'icon' => 'fas fa-book-open',
            'auth' => [26]
        ],
        [
            'text' => 'Lacak',
            'url'  => '/penjualan/lacak/show',
            'icon' => 'fas fa-search',
            'auth' => [26]
        ],

        // [
        //     'text' => 'Nama & Alamat',
        //     'url'  => '/nama_alamat',
        //     'icon' => 'fas fa-table',
        //     'auth' => [26]
        // ],
        // [
        //     'text' => 'Jasa Ekspedisi',
        //     'url'  => '/jasa_eks',
        //     'icon' => 'fas fa-table',
        //     'auth' => [26]
        // ],
        [
            'header' => 'TRANSAKSI',
            'auth'   => [26]
        ],
        [
            'text' => 'Penjualan',
            'icon' => 'fas fa-mail-bulk',
            'auth' => [26],
            'url'  => '/penjualan/penjualan/show',
        ],
        [
            'text' => 'Request Emiindo',
            'icon' => 'fas fa-mail-bulk',
            'auth' => [26],
            'submenu' => [
                [
                    'text' => 'Ekatalog - SPA',
                    'url'  => '/penjualan/request_emiindo/akn_po',
                ],
                [
                    'text' => 'Terima E-Katalog',
                    'url'  => '/penjualan/request_emiindo/daftar_penerimaan',
                ]
            ]
        ],
        // [
        //     'text' => 'Sales Order',
        //     'url'  => '/penjualan/so/show',
        //     'icon' => 'fas fa-file-invoice-dollar',
        //     'auth' => [26]
        // ],
        // [
        //     'text'    => 'Daftar Pesanan',
        //     'icon'    => 'fas fa-table',
        //     'auth' => [26],
        //     'submenu' => [
        //         [
        //             'text' => 'E-Katalog',
        //             'url'  => '/penjualan_online',
        //         ],
        //         [
        //             'text' => 'E-Commerce',
        //             'url'  => '/penjualan_online_ecom',
        //         ],
        //         [
        //             'text' => 'Offline',
        //             'url'  => '/penjualan_offline',
        //         ]
        //     ],
        // ],
        // [
        //     'text'    => 'Surat Penawaran',
        //     'icon'    => 'fas fa-table',
        //     'auth' => [26],
        //     'submenu' => [
        //         [
        //             'text' => 'E-Commerce',
        //             'url'  => '/penawaran_ecom',
        //         ],
        //         [
        //             'text' => 'Offline',
        //             'url'  => '/penawaran_offline',
        //         ]
        //     ],
        // ],
        // [
        //     'text'    => 'PO / DO',
        //     'icon'    => 'fas fa-table',
        //     'auth' => [26],
        //     'submenu' => [
        //         [
        //             'text' => 'E-Katalog',
        //             'url'  => '/podo_online',
        //         ],
        //         [
        //             'text' => 'Offline',
        //             'url'  => '/podo_offline',
        //         ]
        //     ],
        // ],
        // // penjualan (26) & produksi (17)
        // [
        //     'text' => 'Produk',
        //     'url'  => '/produk',
        //     'icon' => 'fas fa-table',
        //     'auth' => [14, 17, 26]
        // ],
        // produksi (17) & QC(23)
        // [
        //     'text' => 'Data Karyawan',
        //     'icon' => 'fas fa-users',
        //     'auth' => [14, 17, 23, 28],
        //     'submenu' => [
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Jadwal Kerja Operator',
        //             'url'  => '/karyawan',
        //             'auth' => [],
        //         ],
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Daftar Karyawan',
        //             'url'  => '/daftar_karyawan',
        //             'auth' => [28],
        //         ],
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Permohonan Penugasan',
        //             'url'  => '/karyawan/peminjaman',
        //             'auth' => [],
        //         ],
        //     ],
        // ],
        [
            'text'    => 'Inventory',
            'icon'    => 'fas fa-boxes',
            'auth' => [14],
            'submenu' => [
                [
                    'icon' => 'far fa-circle',
                    'text' => 'Master Inventory',
                    'auth' => [14],
                    'url'  => '/inventory/divisi',
                ],
                [
                    'icon' => 'far fa-circle',
                    'text' => 'Inventory',
                    'url'  => '/inventory',
                ],
                [
                    'icon' => 'far fa-circle',
                    'text' => 'Permintaan Peminjaman',
                    'url'  => '/inventory/peminjaman',
                ],
            ],
        ],
        [
            'text'    => 'Peminjaman',
            'icon'    => 'fas fa-boxes',
            'auth' => [14],
            'submenu' => [
                [
                    'icon' => 'far fa-circle',
                    'text' => 'Alat',
                    'url'  => '/peminjaman/alat',
                ],
                [
                    'icon' => 'far fa-circle',
                    'text' => 'Karyawan',
                    'url'  => '/peminjaman/karyawan',
                ],

            ],
        ],
        // kesehatan (28)
        // Kesehatan
        [
            'text' => 'Beranda',
            'url'  => '/kesehatan/dashboard',
            'icon' => 'fas fa-home fa-fw',
            'auth' => [28]
        ],

        [
            'header' => 'DATA',
            'auth'   => [28]
        ],
        [
            'text' => 'Karyawan',
            'icon' => 'fas fa-users fa-fw',
            'auth' => [28],
            'url'  => '/karyawan/',
            // 'submenu' => [
            //     [
            //         'icon' => 'far fa-circle',
            //         'text' => 'Daftar Karyawan',
            //         'url'  => '/karyawan/',
            //         'auth' => [28],
            //     ],
            // ],
        ],
        [
            'icon' => 'fas fa-tablets fa-fw',
            'text' => 'Obat',
            'auth' => [28],
            'url'  => '/obat/',
        ],

        [
            'header' => 'KESEHATAN',
            'auth'   => [28]
        ],
        [
            // 'text' => 'Kesehatan',
            // 'icon' => 'fas fa-book-medical',
            // 'auth' => [28],
            // 'submenu' => [
            //     [
                    'icon' => 'fas fa-user-md fa-fw',
                    'text' => 'Awal',
                    'auth' => [28],
                    'url'  => '/kesehatan/',
                ],
                [
                    'icon' => 'fas fa-vial fa-fw',
                    'text' => 'Mingguan',
                    'auth' => [28],
                    'submenu' => [
                        [
                            'icon' => 'far fa-circle',
                            'text' => 'Data',
                            'auth' => [28],
                            'url'  => '/kesehatan/mingguan',
                        ],
                        // [
                        //     'icon' => 'far fa-circle',
                        //     'text' => 'Detail',
                        //     'auth' => [28],
                        //     'url'  => '/kesehatan/mingguan/detail',
                        // ],
                        [
                            'icon' => 'far fa-circle',
                            'text' => 'Laporan',
                            'auth' => [28],
                            'url'  => '/kesehatan/laporan/mingguan',
                        ]
                    ],
                ],
                [
                    'icon' => 'fas fa-weight fa-fw',
                    'text' => 'Bulanan',
                    'auth' => [28],
                    'submenu' => [
                        [
                            'icon' => 'far fa-circle',
                            'text' => 'Data',
                            'auth' => [28],
                            'url'  => '/kesehatan/bulanan',
                        ],
                        // [
                        //     'icon' => 'far fa-circle',
                        //     'text' => 'Detail',
                        //     'auth' => [28],
                        //     'url'  => '/kesehatan/bulanan/detail',
                        // ],
                        [
                            'icon' => 'far fa-circle',
                            'text' => 'Laporan',
                            'auth' => [28],
                            'url'  => '/kesehatan/laporan/bulanan',
                        ]
                    ],
                ],
        //     ],
        // ],
        [
            'header' => 'KHUSUS',
            'auth'   => [28]
        ],
        [
            'icon' => 'fas fa-clinic-medical fa-fw',
            'text' => 'Layanan Klinik',
            'auth' => [28],
            'url'  => '/karyawan/sakit',
        ],
        [
            'icon' => 'fas fa-walking fa-fw',
            'text' => 'Karyawan Masuk',
            'url'  => '/karyawan/masuk',
            'auth' => [28],
        ],





        // GBMP (11)
        [
            'header' => 'GBMP',
            'auth' => [11],
        ],
        [
            'text' => 'Stok',
            'auth' => [11],
            'icon' =>  'fas fa-boxes',
            'url' => '/gbmp/data_view'
        ],
        [
            'text' => 'Pesanan PPIC',
            'auth' => [11],
            'icon' =>  'fas fa-boxes',
            'url' => '/gbmp/bppb_view'
        ],
        // Produksi (17)
        // [
        //     'header' => 'PERSIAPAN',
        //     'auth' => [17]
        // ],
        // [
        //     'text'    => 'Packing Produk',
        //     'icon'    => 'fas fa-calendar-alt',
        //     'url'  => '/persiapan_packing_produk',
        //     'auth' => [17],
        // ],
        // // Produksi (17) & Engineering (10) & Maintenence (16)
        [
            'text'    => 'Dashboard',
            'icon'    => 'fas fa-layer-group',
            'url'  => '/produksi/dashboard',
            'auth' => [17]
        ],
        [
            'header' => 'PRODUKSI',
            'auth' => [17, 10, 16]
        ],
        // [
        //     'text'    => 'Jadwal Kerja Produksi',
        //     'icon'    => 'fas fa-calendar-alt',
        //     'url'  => '/jadwal_kerja_produksi',
        //     'auth' => [17],
        // ],
        // [
        //     'text'    => 'BPPB',
        //     'icon'    => 'fas fa-project-diagram',
        //     'url' => '/bppb',
        //     'auth' => [17],
        // ],
        [
            'text'    => 'Perakitan',
            'icon'    => 'fas fa-cogs',
            'auth' => [17],
            'submenu' => [
                [
                    'icon' => 'far fa-circle',
                    'text' => 'Perencanaan Perakitan',
                    'url' => 'produksi/perencanaan_perakitan',
                    'auth' => [17],
                ],
                [
                    'icon' => 'far fa-circle',
                    'text' => 'Perakitan Berlangsung',
                    'url' => 'produksi/jadwal_perakitan',
                    'auth' => [17],
                ],
                [
                    'icon' => 'far fa-circle',
                    'text' => 'Riwayat Perakitan',
                    'url' => 'produksi/riwayat_perakitan',
                    'auth' => [17],
                ],
                // [
                //     'icon' => 'far fa-circle',
                //     'text' => 'Laporan',
                //     'url'  => '/perakitan',
                //     'auth' => [17],
                // ],
            ],
        ],
        [
            'header' => 'TRANSFER',
            'auth' => [17]
        ],
        [
            'text'    => 'Gudang',
            'icon'    => 'fas fa-cubes',
            'url'  => '/produksi/pengiriman',
            'auth' => [17],
        ],
        [
            'text'    => 'Riwayat Transfer',
            'icon'    => 'fas fa-history',
            'url'  => '/produksi/riwayat_transfer',
            'auth' => [17],
        ],
        [
            'text'    => 'Perakitan',
            'icon'    => 'fas fa-cogs',
            'url'  => '/perakitan/mtc',
            'auth' => [16],
        ],
        [
            'text'    => 'Perakitan',
            'icon'    => 'fas fa-cogs',
            'url'  => '/perakitan/eng',
            'auth' => [10],
        ],
        // [
        //     'text'    => 'Pengujian',
        //     'icon'    => 'fab fa-searchengin',
        //     'auth' => [17],
        //     'submenu' => [
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Laporan',
        //             'url'  => '/pengujian/prd',
        //             'auth' => [17],
        //         ],
        //     ],
        // ],
        [
            'text' => 'Pengujian',
            'icon' => 'fab fa-searchengin',
            'url'  => '/pengujian/mtc',
            'auth' => [16],
        ],
        [
            'text' => 'Pengujian',
            'icon' => 'fab fa-searchengin',
            'url'  => '/pengujian/eng',
            'auth' => [10],
        ],
        [
            'text' => 'Pengemasan',
            'icon' => 'fas fa-box-open',
            'url'  => '/pengemasan/mtc',
            'auth' => [16],
        ],
        // [
        //     'text' => 'Pengemasan',
        //     'icon' => 'fas fa-box-open',
        //     'auth' => [17],
        //     'submenu' => [
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Laporan',
        //             'url'  => '/pengemasan',
        //             'auth' => [17],
        //         ],
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Form Produk',
        //             'url'  => '/pengemasan/form',
        //             'auth' => [17],
        //         ],
        //     ],
        // ],
        [
            'text'    => 'Pengemasan',
            'icon'    => 'fas fa-box-open',
            'url'  => '/pengemasan/eng',
            'auth' => [10],
        ],
        // [
        //     'text'    => 'Perbaikan',
        //     'icon'    => 'fas fa-wrench',
        //     'url' => '/perbaikan/produksi',
        //     'auth' => [17],
        // ],
        // QC (23) & Lab (22)
        // [
        //     'header' => 'TRANSAKSI',
        //     'auth' => [22],
        //     'header' => 'INCOMING',
        //     'auth' => [23]
        // ],

        // [
        //     'text' => 'Kedatangan',
        //     'icon' => 'fas fa-dolly-flatbed',
        //     'auth' => [23],
        //     'submenu' => [
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Packing List',
        //             'url'  => '/kedatangan/packing_list',
        //             'auth' => [23],
        //         ],
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Analisa',
        //             'url'  => '/kedatangan/analisa',
        //             'auth' => [23],
        //         ],
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Sampling',
        //             'url'  => '/kedatangan/sampling',
        //             'auth' => [23],
        //         ],
        //     ],
        // ],
        // [
        //     'text' => 'BPPB',
        //     'icon' => 'fas fa-project-diagram',
        //     'url' => '/bppb',
        //     'auth' => [23],
        // ],
        // [
        //     'header' => 'INPROCESS',
        //     'auth' => [23]
        // ],
        // [
        //     'text'    => 'Perakitan',
        //     'icon'    => 'fas fa-cogs',
        //     'auth' => [23],
        //     'submenu' => [
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Laporan',
        //             'url'  => '/perakitan/pemeriksaan',
        //             'auth' => [23],
        //         ],
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'IK Pemeriksaan',
        //             'url'  => '/perakitan/ik_pemeriksaan',
        //             'auth' => [23],
        //         ],
        //     ],
        // ],
        // [
        //     'text'    => 'Pengujian',
        //     'icon'    => 'fab fa-searchengin',
        //     'auth' => [23],
        //     'submenu' => [
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Laporan',
        //             'url'  => '/pengujian',
        //             'auth' => [23],
        //         ],
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Kalibrasi',
        //             'url'  => '/kalibrasi',
        //             'auth' => [23],
        //         ],
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'IK Pemeriksaan',
        //             'url'  => '/pengujian/ik_pemeriksaan',
        //             'auth' => [23],
        //         ],
        //     ],
        // ],
        // [
        //     'text'    => 'Pengemasan',
        //     'icon'    => 'fas fa-box-open',
        //     'auth' => [23],
        //     'submenu' => [
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'Laporan',
        //             'url'  => '/pengemasan/qc',
        //             'auth' => [23],
        //         ],
        //         [
        //             'icon' => 'far fa-circle',
        //             'text' => 'IK Pemeriksaan',
        //             'url'  => '/pengemasan/ik_pemeriksaan',
        //             'auth' => [23],
        //         ],
        //     ],
        // ],
        [
            'header' => 'DATA',
            'auth' => [23, 34]
        ],
        [
            'text' => 'Customer',
            'icon' => 'fas fa-users',
            'url' => '/penjualan/customer/show',
            'auth' => [23, 34],
        ],
        [
            'header' => 'OUTGOING',
            'auth' => [23, 34]
        ],
        [
            'text' => 'Sales Order',
            'icon' => 'fas fa-clipboard-check',
            'url' => '/qc/so/show',
            'auth' => [23, 34],
        ],
        [
            'text' => 'Riwayat Pengujian',
            'icon' => 'fas fa-history',
            'url' => '/qc/so/riwayat/show',
            'auth' => [23, 34],
        ],
        [
            'text' => 'Laporan',
            'icon' => 'fas fa-book-open',
            'url' => '/qc/so/laporan/show',
            'auth' => [23, 34],
        ],
        [
            'text' => 'Lacak',
            'url'  => '/penjualan/lacak/show',
            'icon' => 'fas fa-search',
            'auth' => [23, 34]
        ],
        [
            'header' => 'ALAT UJI',
            'auth' => [23, 34]
        ],


        [
            'text'    => 'Permintaan',
            'icon'    => 'fas fa-box-open',
            'url'  => '/kalibrasi',
            'auth' => [22],
        ],
        [
            'text'    => 'Sudah Kalibrasi',
            'icon'    => 'fas fa-box-open',
            'url'  => '/acc_kalibrasi',
            'auth' => [22],
        ],
        [
            'text' => 'Dashboard',
            'url' => '/gbj/dashboard',
            'icon' => 'fas fa-layer-group',
            'auth' => [31, 13],
        ],
        // [
        //     'header' => 'Produksi',
        //     'auth' => [13]
        // ],
        // [
        //     'text' => 'BPPB',
        //     'url' => '/bppb',
        //     'icon' => 'fas fa-circle',
        //     'auth' => [13],
        // ],
        [
            'header' => 'Stok',
            'auth' => [13]
        ],
        // GBJ
        [
            'text' => 'Produk',
            'url' => '/gbj/produk',
            'icon' => 'fas fa-boxes',
            'auth' => [13],
        ],

        // [
        //     'text' => 'Stok',
        //     'url' => '/gbj/stok',
        //     'icon' => 'fas fa-circle',
        //     'auth' => [13],
        // ],
        [
            'text' => 'Riwayat Transaksi',
            'url' => '/gbj/tp',
            'icon' => 'fas fa-history',
            'auth' => [13],
        ],

        [
            'header' => 'Penerimaan',
            'auth' => [13]
        ],
        [
            'text' => 'Dalam Perakitan',
            'url' => '/gbj/dp',
            'icon' => 'fas fa-truck-loading',
            'auth' => [13],
        ],
        [
            'text' => 'Selain Perakitan',
            'url' => '/gbj/lp',
            'icon' => 'fas fa-box-open',
            'auth' => [13],
        ],
        [
            'header' => 'Penjualan',
            'auth' => [13, 17, 31]
        ],
        [
            'text' => 'Sales Order',
            'url' => '/gbj/so',
            'icon' => 'fas fa-people-carry',
            'auth' => [13, 31],
        ],
        [
            'text' => 'Sales Order',
            'url' => '/produksi/so',
            'icon' => 'fas fa-people-carry',
            'auth' => [17],
        ],
        [
            'header' => 'Pengeluaran',
            'auth' => [13]
        ],
        [
            'text' => 'Berdasarkan SO',
            'url' => '/gbj/bso',
            'icon' => 'fas fa-swatchbook',
            'auth' => [13],
        ],
        [
            'text' => 'Tanpa SO',
            'url' => '/gbj/tso',
            'icon' => 'fas fa-book-open',
            'auth' => [13],
        ],

        //LOGISTIK
        [
            'text' => 'Beranda',
            'icon' => 'fas fa-home fa-fw',
            'url' => '/logistik/dashboard',
            'auth' => [15],
        ],
        [
            'header' => 'DATA',
            'auth' => [15]
        ],
        [
            'text' => 'Jasa Ekspedisi',
            'icon' => 'fa-fw far fa-file-alt',
            'url' => '/logistik/ekspedisi/show',
            'auth' => [15],
        ],
        [
            'text' => 'Customer',
            'icon' => 'fas fa-users fa-fw',
            'url' => '/penjualan/customer/show',
            'auth' => [15],
        ],
        [
            'header' => 'PENJUALAN',
            'auth' => [15]
        ],
        [
            'text' => 'Sales Order',
            'icon' => 'fas fa-dolly fa-fw',
            'url' => '/logistik/so/show',
            'auth' => [15],
        ],
        [
            'text' => 'Pengiriman',
            'icon' => 'fas fa-shipping-fast fa-fw',
            'url' => '/logistik/pengiriman/show',
            'auth' => [15],
        ],
        // [
        //     'text' => 'Riwayat Pengiriman',
        //     'icon' => 'far fa-circle',
        //     'url' => '/logistik/pengiriman/riwayat/show',
        //     'auth' => [15],
        // ],
        [
            'text' => 'Laporan',
            'icon' => 'fas fa-book-open fa-fw',
            'url' => '/logistik/laporan/show',
            'auth' => [15],
        ],
        [
            'text' => 'Lacak',
            'icon' => 'fas fa-search fa-fw',
            'url' => '/penjualan/lacak/show',
            'auth' => [15],
        ],
        //DOCUMENT CONTROL
        [
            'text' => 'Beranda',
            'icon' => 'fas fa-home',
            'url' => '/dc/dashboard',
            'auth' => [9],
        ],
        [
            'header' => 'DATA',
            'auth' => [9]
        ],
        [
            'text' => 'Produk',
            'icon' => 'fas fa-box-open',
            'url' => '/master/produk/show',
            'auth' => [9],
        ],
        [
            'text' => 'Lacak',
            'icon' => 'fas fa-search fa-fw',
            'url' => '/penjualan/lacak/show',
            'auth' => [9],
        ],
        [
            'header' => 'PENJUALAN',
            'auth' => [9]
        ],
        [
            'text' => 'Dalam Proses',
            'icon' => 'fas fa-spinner',
            'url' => '/dc/so_in_process/show',
            'auth' => [9],
        ],
        [
            'text' => 'Sales Order',
            'icon' => 'fas fa-clipboard-list',
            'url' => '/dc/so/show',
            'auth' => [9],
        ],
        [
            'text' => 'COO',
            'icon' => 'fas fa-certificate',
            'url' => '/dc/coo/show',
            'auth' => [9],
        ],

        //AFTER SALES PERBAIKAN
        [
            'header' => 'DATA',
            'auth' => [8]
        ],
        [
            'text' => 'Customer',
            'icon' => 'fas fa-users fa-fw',
            'url' => '/penjualan/customer/show',
            'auth' => [8],
        ],
        [
            'text' => 'Sales Order',
            'icon' => 'fas fa-history fa-fw',
            'url' => '/as/so/show',
            'auth' => [8],
        ],
        [
            'text' => 'Lacak',
            'icon' => 'fas fa-search fa-fw',
            'url' => '/penjualan/lacak/show',
            'auth' => [8],
        ],
        [
            'header' => 'TRANSAKSI',
            'auth' => [8]
        ],
        [
            'text' => 'Penjualan',
            'icon' => 'fas fa-mail-bulk fa-fw',
            'url' => '/as/penjualan/show',
            'auth' => [8],
        ],
        [
            'text' => 'Retur',
            'icon' => 'fas fa-exchange-alt fa-fw',
            'url' => '/as/retur/show',
            'auth' => [8],
        ],
        [
            'text' => 'test',
            'isNavbarRightItem' => true,
            'icon' => 'fas fa-sign-out-alt',
        ],
        // GK
        [
            'text' => 'Dashboard',
            'url' => 'gk/dashboard',
            'icon' => 'fas fa-layer-group',
            'auth' => [12],
        ],
        [
            'header' => 'Produk',
            'auth' => [12],
        ],
        [
            'text' => 'Gudang',
            'url' => 'gk/gudang',
            'icon' => 'fas fa-boxes',
            'auth' => [12],
        ],
        [
            'text' => 'Riwayat Transaksi',
            'url' => 'gk/transaksi',
            'icon' => 'fas fa-history',
            'auth' => [12],
        ],
        [
            'header' => 'Penerimaan',
            'auth' => [12],
        ],
        [
            'text' => 'Penerimaan Produk',
            'url' => 'gk/terimaProduk',
            'icon' => 'fas fa-dolly',
            'auth' => [12],
        ],
        [
            'header' => 'Transfer',
            'auth' => [12],
        ],

        //DIREKSI
        [
            'text' => 'Dashboard',
            'url' => 'direksi/dashboard',
            'icon' => 'fas fa-layer-group',
            'auth' => [2],
        ],
        [
            'text'    => 'GBJ',
            'icon'    => 'fas fa-boxes',
            'auth' => [2],
            'submenu' => [
                [
                    'text' => 'Produk',
                    'icon' => 'far fa-circle',
                    'url'  => '/gbj/produk',
                    'auth' => [2],
                ],
                [
                    'text' => 'Riwayat Transaksi',
                    'url'  => '/gbj/tp',
                    'icon' => 'far fa-circle',
                    'auth' => [2]
                ],
            ],
        ],
        [
            'text'    => 'GK',
            'icon'    => 'fas fa-tools',
            'auth' => [2],
            'submenu' => [
                [
                    'text' => 'Gudang',
                    'url' => '/gk/gudang',
                    'icon' => 'far fa-circle',
                    'auth' => [2]
                ],
                [
                    'text' => 'Riwayat Transaksi',
                    'url'  => 'gk/transaksi',
                    'icon' => 'far fa-circle',
                    'auth' => [2]
                ],
            ],
        ],
        [
            'text'    => 'Penjualan',
            'icon'    => 'fas fa-mail-bulk',
            'auth' => [2],
            'submenu' => [
                [
                    'text' => 'Penjualan',
                    'url'  => '/penjualan/penjualan/show',
                    'icon' => 'far fa-circle',
                    'auth' => [2]
                ],
                [
                    'text' => 'Customer',
                    'url'  => '/penjualan/customer/show',
                    'icon' => 'far fa-circle',
                    'auth' => [2]
                ],
                [
                    'text' => 'Lacak',
                    'icon' => 'far fa-circle',
                    'url' => '/penjualan/lacak/show',
                    'auth' => [2],
                ],
            ],
        ],
        [
            'text'    => 'PPIC',
            'icon'    => 'fas fa-database',
            'auth' => [2, 26],
            'submenu' => [
                [
                    'text' => 'Permintaan Barang',
                    'icon' => 'far fa-circle',
                    'url' => '/ppic/master_stok/show',
                    'auth' => [2, 26],
                ],
                [
                    'text' => 'Permintaan Pengiriman',
                    'icon' => 'far fa-circle',
                    'url' => '/ppic/master_pengiriman/show',
                    'auth' => [2, 26],
                ],
                [
                    'text' => 'Jadwal Perakitan',
                    'url'  => '/ppic_direksi',
                    'icon' => 'far fa-circle',
                    'auth' => [2]
                ],
            ],
        ],
        // [
        //     'text' => 'PPIC',
        //     'icon' => 'fas fa-truck-loading',
        //     'auth' => [2],
        //     'submenu' => [],
        // ],
        [
            'text'    => 'Produksi',
            'icon'    => 'fas fa-cogs',
            'auth' => [2],
            'submenu' => [
                [
                    'text' => 'Perakitan Berlangsung',
                    'url'  => 'produksi/jadwal_perakitan',
                    'icon' => 'far fa-circle',
                    'auth' => [2]
                ],
                [
                    'text' => 'Riwayat Perakitan',
                    'url'  => 'produksi/riwayat_perakitan',
                    'icon' => 'far fa-circle',
                    'auth' => [2]
                ],
            ],
        ],
        [
            'text'    => 'QC',
            'icon'    => 'fas fa-clipboard-check',
            'auth' => [2],
            'submenu' => [
                [
                    'text' => 'Sales Order',
                    'icon' => 'far fa-circle',
                    'url' => '/qc/so/show',
                    'auth' => [2],
                ],
                [
                    'text' => 'Riwayat Pengujian',
                    'icon' => 'far fa-circle',
                    'url' => '/qc/so/riwayat/show',
                    'auth' => [2],
                ],
            ],
        ],
        [
            'text'    => 'Logistik',
            'icon'    => 'fas fa-shipping-fast',
            'auth' => [2],
            'submenu' => [
                [
                    'text' => 'Sales Order',
                    'icon' => 'far fa-circle',
                    'url' => '/logistik/so/show',
                    'auth' => [2],
                ],
                [
                    'text' => 'Pengiriman',
                    'icon' => 'far fa-circle',
                    'url' => '/logistik/pengiriman/show',
                    'auth' => [2],
                ],
                [
                    'text' => 'Jasa Ekspedisi',
                    'icon' => 'far fa-circle',
                    'url' => '/logistik/ekspedisi/show',
                    'auth' => [2],
                ],
            ],
        ],
        [
            'text'    => 'DC',
            'icon'    => 'fas fa-certificate',
            'auth' => [2],
            'submenu' => [
                [
                    'text' => 'COO',
                    'icon' => 'far fa-circle',
                    'url' => '/dc/coo/show',
                    'auth' => [2],
                ],
            ],
        ],

        // [
        //     'text'    => 'After Sales',
        //     'icon'    => 'fas fa-headset',
        //     'auth' => [2],
        //     'submenu' => [
        //         [
        //             'text' => 'Lacak',
        //             'icon' => 'far fa-circle',
        //             'url' => '/penjualan/lacak/show',
        //             'auth' => [2],
        //         ],
        //     ],
        // ],
        [
            'text' => 'Transfer Produk',
            'url' => 'gk/transfer',
            'icon' => 'fas fa-cubes',
            'auth' => [12],
        ],
        // Manager Gudang
        [
            'header' => 'Persetujuan',
            'auth' => [31]
        ],
        [
            'text' => 'Produk',
            'url' => 'gbjmanager/produksi',
            'icon' => 'fas fa-cubes',
            'auth' => [31],
        ],

        //Manager Penjualan
        [
            'header' => 'TRANSAKSI',
            'auth'   => [32]
        ],
        [
            'text' => 'Penjualan',
            'icon' => 'fas fa-mail-bulk',
            'auth' => [32],
            'url'  => '/manager/penjualan/show',
        ],
        [
            'text' => 'Logistik',
            'icon' => 'fas fa-dolly fa-fw',
            'url' => '/manager/logistik/show',
            'auth' => [32],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/8.-Menu-Configuration
    |
    */

    'filters' => [
        App\Menu\Filters\GateFilter::class,
        App\Menu\Filters\HrefFilter::class,
        App\Menu\Filters\SearchFilter::class,
        App\Menu\Filters\ActiveFilter::class,
        App\Menu\Filters\ClassesFilter::class,
        App\Menu\Filters\LangFilter::class,
        App\Menu\Filters\DataFilter::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/9.-Other-Configuration
    |
    */
    'plugins' => [
        // core plugins
        'FontAwesome' => [
            'css' => [
                'vendor/fontawesome-free/css/all.min.css',
            ]
        ],
        'jQuery' => [
            'js' => [
                'vendor/jquery/jquery.min.js',
            ],
        ],
        'BootStrap' => [
            'js' => [
                'vendor/bootstrap/js/bootstrap.bundle.min.js',
            ],
        ],
        // dependecies plugins
        // 'DataTables' => [
        //     'js' => [
        //         'vendor/datatables/jquery.dataTables.min.js',
        //         'vendor/datatables/dataTables.bootstrap4.min.js',
        //         'vendor/datatables/dataTables.responsive.min.js',
        //     ],
        //     'css' => [
        //         'vendor/datatables/dataTables.bootstrap4.min.css',
        //     ],
        // ],
        'overlayScrollbars' => [
            'js' => [
                'vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
            ],
            'CSS' => [
                'vendor/overlayScrollbars/css/OverlayScrollbars.min.css',
            ],
        ],
        'Select2' => [
            'js' => [
                'vendor/select2/js/select2.full.min.js',

            ],
            'css' => [
                'vendor/select2/css/select2.min.css',
                'vendor/select2/css/select2-bootstrap4.min.css',
            ],
        ],
        'Chartjs' => [
            'js' => [
                'vendor/charts/chart.js',
            ],
        ],
        'AdminLte' => [
            'js' => [
                'vendor/adminlte/dist/js/adminlte.min.js',
            ],
            'css' => [
                'vendor/adminlte/dist/css/adminlte.min.css',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/9.-Other-Configuration
    */
    'livewire' => false,
];
