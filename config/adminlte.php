<?php

use ColorlibHQ\AdminLte\Menu\Filters\ActiveFilter;
use ColorlibHQ\AdminLte\Menu\Filters\GateFilter;
use ColorlibHQ\AdminLte\Menu\Filters\HrefFilter;
use ColorlibHQ\AdminLte\Menu\Filters\SearchFilter;

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | The default page title, and an optional prefix/postfix applied to every
    | page title set with @section('title', ...).
    |
    */

    'title' => 'Esperanzarte',
    'title_prefix' => '',
    'title_postfix' => '',
    'data_bs_theme' => 'light',
    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    */

    'use_ico_only' => 'assets/img/favicon-cel-32x32.jpg',
    'use_full_favicon' => 'assets/img/favicon-cel-32x32.jpg',

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | AdminLTE 4 uses Source Sans 3. Set to false to self-host or skip.
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | The brand logo shown in the sidebar. `logo` accepts HTML and is
    | rendered UNESCAPED ({!! !!}) — only ever put trusted, hardcoded
    | markup here, never user-supplied or database-driven content.
    |
    */

    'logo' => '<b>Esperanzarte</b>',
    'logo_img' => 'assets/img/palomita.png',
    'logo_img_class' => 'brand-image opacity-75 shadow',
    'logo_img_alt' => 'Esperanzarte Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication logo
    |--------------------------------------------------------------------------
    */

    'auth_logo' => [
        'enabled' => true,
        'img' => [
            'path' => 'assets/img/celaya-blanco.png',
            'alt' => 'Esperanzarte Auth Logo',
            'class' => '',
            'height' => 110,
        ],
    ],

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'assets/img/celaya-blanco.png',
            'alt' => 'Esperanzarte Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User menu (topbar dropdown)
    |--------------------------------------------------------------------------
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
    | Body-level layout switches. These map directly to AdminLTE 4 body classes.
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,   // .layout-fixed
    'layout_fixed_navbar' => true,    // .fixed-header
    'layout_fixed_footer' => null,       // null = respect system / user toggle
    'layout_rtl' => false,    
            // Enable right-to-left layout

    /*
    |--------------------------------------------------------------------------
    | Footer & Preloader
    |--------------------------------------------------------------------------
    |
    | `footer_left` / `footer_right` accept HTML and are rendered UNESCAPED
    | ({!! !!}) — only ever put trusted, hardcoded markup here, never
    | user-supplied or database-driven content.
    |
    */

    'footer_left' => 'Copyright &copy; '.date('Y').' . All rights reserved.',
    'footer_right' => 'Esperanzarte',
    'preloader' => true,
    'control_sidebar' => false,
    'control_sidebar_theme' => 'light',


    // Documentation URL used by the navbar "Documentation" link and the sidebar
    // "View documentation" CTA (false to hide the CTA). Defaults to the in-app
    // docs viewer served at /docs (see the `docs` keys below).
    'sidebar_docs_url' => '/docs',

    // Bundled demo/showcase pages (Dashboard v2/v3, Widgets, UI, Forms, Tables,
    // Layout Options, Theme Generate, auth variants, error pages). Set false to
    // skip registering their routes in production.
    'demo' => true,
    'demo_middleware' => ['web', 'auth'],

    // In-app documentation viewer: renders this package's docs/*.md files at
    // /docs and /docs/{page}. Set 'docs' => false to disable the route.
    'docs' => true,
    'docs_middleware' => ['web'],

    'sidebar_breakpoint' => 'lg',     // sidebar-expand-{breakpoint}
    'sidebar_mini' => true,           // .sidebar-mini
    'sidebar_collapse' => true,      // start collapsed
    'sidebar_collapse_auto_size' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'leave',

    /*
    |--------------------------------------------------------------------------
    | Color theme
    |--------------------------------------------------------------------------
    |
    | The sidebar uses data-bs-theme="dark" by default (dark sidebar on a
    | light page, matching the AdminLTE 4 demos). Set to 'light' for a light
    | sidebar.
    |
    */

    'sidebar_theme' => 'dark',  // 'dark' | 'light'

    /*
    |--------------------------------------------------------------------------
    | Custom body / element classes
    |--------------------------------------------------------------------------
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => 'fw-light',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'bg-body-secondary shadow',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-expand bg-body',
    'classes_topnav_nav' => 'navbar',
    'classes_topnav_container' => 'container-fluid',

    /*
    |--------------------------------------------------------------------------
    | Color mode toggle
    |--------------------------------------------------------------------------
    |
    | Shows the Light/Dark/Auto dropdown in the topbar (AdminLTE 4 feature).
    |
    */
   
    'color_mode_toggle' => true,

    /*
    |--------------------------------------------------------------------------
    | Menu
    |--------------------------------------------------------------------------
    |
    | The sidebar (and optional top-nav) menu. Each item is an array. Supported
    | keys:
    |
    |   'header'      => 'SECTION LABEL'            // a section header
    |   'text'        => 'Dashboard'               // link label (required for links)
    |   'route'       => 'dashboard'               // named route  -> url
    |   'url'         => 'admin/users'             // raw url (relative or absolute)
    |   'icon'        => 'bi bi-speedometer'       // Bootstrap Icons class
    |   'icon_color'  => 'primary'                 // optional text-{color}
    |   'label'       => 5                         // badge value
    |   'label_color' => 'primary'                 // badge color
    |   'active'      => ['admin/users*']          // url patterns that mark active
    |   'target'      => '_blank'                  // anchor target
    |   'can'         => 'view-users'              // gate/permission to show item
    |   'submenu'     => [ ...child items... ]     // nested items (treeview)
    |
    */

    'menu' => [
        // ---- Sidebar: mirrors the AdminLTE 4 demo sidebar ----
        [
            'text' => 'Panel de Control',
            'icon' => 'bi bi-speedometer',
            'url' => 'home',
            'can' => 'Administradores',
        ],
        [
            'text' => 'Inicio',
            'icon' => 'bi bi-speedometer',
            'url' => 'entregasmateriales',
            'can' => 'Entregas',
        ],
        [
            'text' => 'Alumnos',
            'icon' => 'fas fa-fw fa-user-graduate',
            'url' => 'alumnos',
            'can' => 'Administradores',
        ],
        [
            'text' => 'Asignar Taller',
            'icon' => 'fas fa-fw fa-arrow-right',
            'url' => 'asignar-taller',
            'can' => 'Administradores',
        ],
        [
            'text' => 'Docentes',
            'icon' => 'fas fa-fw fa-person-chalkboard',
            'can'  => 'Administradores',
            'submenu' => [
                [
                    'text' => 'Docentes',
                    'url' => 'docentes/',
                ],
                [
                    'text' => 'Enlaces',
                    'url' => 'enlaces/',
                ],
                [
                    'text' => 'Supervisores',
                    'url' => 'supervisores/',
                ],
                [
                    'text' => 'Gestores',
                    'url' => 'gestores/',
                ],
            ],
        ],

        
        [
            'text' => 'Materiales',
            'icon' => 'fas fa-fw fa-file-pen',
            'url' => 'materiales',
            'can' => 'Administradores',
        ],
        [
            'text' => 'Listado Entregas',
            'icon' => 'fas fa-fw fa-hand-holding',
            'url' => 'listadoentregas',
            'can' => 'Administradores',
        ],
        [
            'text' => 'Materiales',
            'icon' => 'fas fa-fw fa-file-pen',
            'url' => 'materiales',
            'can' => 'Entregas',
        ],
        [
            'text' => 'Listado Entregas',
            'icon' => 'fas fa-fw fa-hand-holding',
            'url' => 'listadoentregas',
            'can' => 'Entregas',
        ],
        [
            'text' => 'Reportes Docentes',
            'icon' => 'fas fa-fw fa-chart-column',
            'url' => 'reportes/docentes',
            'can' => 'Administradores',
        ],
        [
            'text' => 'Centros',
            'icon' => 'fas fa-fw fa-school',
            'url' => 'centros',
            'can' => 'Administradores',
        ],
        [
            'text' => 'Talleres',
            'icon' => 'fas fa-fw fa-chalkboard-user',
            'url' => 'talleres',
            'can' => 'Administradores',
        ],
        [
            'text' => 'Usuarios',
            'icon' => 'fas fa-fw fa-users',
            'url' => 'usuarios',
            'can' => 'Administradores',
        ],
        

       

        
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu filters
    |--------------------------------------------------------------------------
    |
    | Filters transform each menu item before rendering. Add your own classes
    | here (must implement ColorlibHQ\AdminLte\Menu\Filters\FilterInterface).
    | The defaults handle gates, active state, hrefs, and search items.
    |
    */

    'filters' => [
        GateFilter::class,
        HrefFilter::class,
        ActiveFilter::class,
        SearchFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins
    |--------------------------------------------------------------------------
    |
    | Optional JavaScript libraries integrated with AdminLTE 4. Disable plugins
    | you don't use to avoid loading unnecessary assets.
    |
    */

    'plugins' => [
        'flatpickr' => [
            'enabled' => false,
            'css' => 'vendor/flatpickr/flatpickr.min.css',
            'js' => 'vendor/flatpickr/flatpickr.min.js',
        ],
        'tom_select' => [
            'enabled' => false,
            'css' => 'vendor/tom-select/tom-select.bootstrap5.min.css',
            'js' => 'vendor/tom-select/tom-select.complete.min.js',
        ],
        'tabulator' => [
            'enabled' => false,
            'css' => 'vendor/tabulator-tables/tabulator.min.css',
            'js' => 'vendor/tabulator-tables/tabulator.min.js',
        ],
        'quill' => [
            'enabled' => false,
            'css' => 'vendor/quill/quill.snow.css',
            'js' => 'vendor/quill/quill.min.js',
        ],
        'apexcharts' => [
            'enabled' => false,
            'js' => 'vendor/apexcharts/apexcharts.min.js',
        ],
        'jsvectormap' => [
            'enabled' => false,
            'css' => 'vendor/jsvectormap/jsvectormap.min.css',
            // The library first, then the world map data (registers the 'world' map).
            'js' => [
                'vendor/jsvectormap/jsvectormap.min.js',
                'vendor/jsvectormap/maps/world.js',
            ],
        ],
        'fullcalendar' => [
            'enabled' => false,
            'css' => 'vendor/fullcalendar/index.global.min.css',
            'js' => 'vendor/fullcalendar/index.global.min.js',
        ],
        'sortablejs' => [
            'enabled' => false,
            'js' => 'vendor/sortablejs/sortablejs.min.js',
        ],
    ],

];
