<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js lt-ie10"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>D3 Catalyst - Backend</title>

        <meta name="description" content="Panel de administracion para GA Comunicacion y clientes de la misma">
        <meta name="author" content="D3 Catalyst">
        <meta name="robots" content="noindex, nofollow">

        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}">
        <link rel="apple-touch-icon" href="{{ asset('img/icon57.png') }}" sizes="57x57">
        <link rel="apple-touch-icon" href="{{ asset('img/icon72.png') }}" sizes="72x72">
        <link rel="apple-touch-icon" href="{{ asset('img/icon76.png') }}" sizes="76x76">
        <link rel="apple-touch-icon" href="{{ asset('img/icon114.png') }}" sizes="114x114">
        <link rel="apple-touch-icon" href="{{ asset('img/icon120.png') }}" sizes="120x120">
        <link rel="apple-touch-icon" href="{{ asset('img/icon144.png') }}" sizes="144x144">
        <link rel="apple-touch-icon" href="{{ asset('img/icon152.png') }}" sizes="152x152">
        <!-- END Icons -->

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-title" content="D3 Catalyst">

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        {{ HTML::style('css/bootstrap.min.css') }}

        <!-- Related styles of various icon packs and plugins -->
        {{ HTML::style('css/plugins.css') }}

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        {{ HTML::style('css/main.css') }}

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        {{ HTML::style('css/themes.css') }}
        <!-- END Stylesheets -->

        <!-- Estilos personalizados -->
        @yield('styles')

        <!-- Modernizr (browser feature detection library) & Respond.js (Enable responsive CSS code on browsers that don't support it, eg IE8) -->
        {{ HTML::script('js/vendor/modernizr-2.7.1-respond-1.4.2.min.js') }}
        <script type="text/javascript">var base_path="{{ URL::to("/") }}";</script>
    </head>
    <!-- In the PHP version you can set the following options from inc/config file -->
    <!--
        Available body classes:

        'page-loading'      enables page preloader
    -->
    <body>
        <!-- Preloader -->
        <!-- Preloader functionality (initialized in js/app.js) - pageLoading() -->
        <!-- Used only if page preloader is enabled from inc/config (PHP version) or the class 'page-loading' is added in body element (HTML version) -->
        <div class="preloader themed-background">
            <h1 class="push-top-bottom text-light text-center"><strong>GA</strong>Comunicacion</h1>
            <div class="inner">
                <h3 class="text-light visible-lt-ie9 visible-lt-ie10"><strong>Cargando..</strong></h3>
                <div class="preloader-spinner hidden-lt-ie9 hidden-lt-ie10"></div>
            </div>
        </div>
        <!-- END Preloader -->

        <!-- Page Container -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!--
            Available #page-container classes:

            '' (None)                                       for a full main and alternative sidebar hidden by default (> 991px)

            'sidebar-visible-lg'                            for a full main sidebar visible by default (> 991px)
            'sidebar-partial'                               for a partial main sidebar which opens on mouse hover, hidden by default (> 991px)
            'sidebar-partial sidebar-visible-lg'            for a partial main sidebar which opens on mouse hover, visible by default (> 991px)

            'sidebar-alt-visible-lg'                        for a full alternative sidebar visible by default (> 991px)
            'sidebar-alt-partial'                           for a partial alternative sidebar which opens on mouse hover, hidden by default (> 991px)
            'sidebar-alt-partial sidebar-alt-visible-lg'    for a partial alternative sidebar which opens on mouse hover, visible by default (> 991px)

            'sidebar-partial sidebar-alt-partial'           for both sidebars partial which open on mouse hover, hidden by default (> 991px)

            'sidebar-no-animations'                         add this as extra for disabling sidebar animations on large screens (> 991px) - Better performance with heavy pages!

            'style-alt'                                     for an alternative main style (without it: the default style)
            'footer-fixed'                                  for a fixed footer (without it: a static footer)

            'disable-menu-autoscroll'                       add this to disable the main menu auto scrolling when opening a submenu

            'header-fixed-top'                              has to be added only if the class 'navbar-fixed-top' was added on header.navbar
            'header-fixed-bottom'                           has to be added only if the class 'navbar-fixed-bottom' was added on header.navbar
        -->
        <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">
            <!-- Alternative Sidebar -->
            {{-- <div id="sidebar-alt">
                <!-- Wrapper for scrolling functionality -->
                <div class="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <div class="sidebar-content">
                        <!-- Chat -->
                        <!-- Chat demo functionality initialized in js/app.js -> chatUi() -->
                        <a href="page_ready_chat.html" class="sidebar-title">
                            <i class="gi gi-comments pull-right"></i> <strong>Chat</strong>UI
                        </a>
                        <!-- Chat Users -->
                        <ul class="chat-users clearfix">
                            <li>
                                <a href="javascript:void(0)" class="chat-user-online">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar12.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="chat-user-online">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar15.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="chat-user-online">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar10.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="chat-user-online">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar4.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="chat-user-away">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar7.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="chat-user-away">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar9.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="chat-user-busy">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar16.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar1.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar4.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar3.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar13.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <span></span>
                                    <img src="{{ asset('img/placeholders/avatars/avatar5.jpg') }}" alt="avatar" class="img-circle">
                                </a>
                            </li>
                        </ul>
                        <!-- END Chat Users -->

                        <!-- Chat Talk -->
                        <div class="chat-talk display-none">
                            <!-- Chat Info -->
                            <div class="chat-talk-info sidebar-section">
                                <img src="{{ asset('img/placeholders/avatars/avatar5.jpg') }}" alt="avatar" class="img-circle pull-left">
                                <strong>John</strong> Doe
                                <button id="chat-talk-close-btn" class="btn btn-xs btn-default pull-right">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <!-- END Chat Info -->

                            <!-- Chat Messages -->
                            <ul class="chat-talk-messages">
                                <li class="text-center"><small>Yesterday, 18:35</small></li>
                                <li class="chat-talk-msg animation-slideRight">Hey admin?</li>
                                <li class="chat-talk-msg animation-slideRight">How are you?</li>
                                <li class="text-center"><small>Today, 7:10</small></li>
                                <li class="chat-talk-msg chat-talk-msg-highlight themed-border animation-slideLeft">I'm fine, thanks!</li>
                            </ul>
                            <!-- END Chat Messages -->

                            <!-- Chat Input -->
                            <form action="{{ URL::to('/'); }}" method="post" id="sidebar-chat-form" class="chat-form">
                                <input type="text" id="sidebar-chat-message" name="sidebar-chat-message" class="form-control form-control-borderless" placeholder="Type a message..">
                            </form>
                            <!-- END Chat Input -->
                        </div>
                        <!--  END Chat Talk -->
                        <!-- END Chat -->

                        <!-- Activity -->
                        <a href="javascript:void(0)" class="sidebar-title">
                            <i class="fa fa-globe pull-right"></i> <strong>Activity</strong>UI
                        </a>
                        <div class="sidebar-section">
                            <div class="alert alert-danger alert-alt">
                                <small>just now</small><br>
                                <i class="fa fa-thumbs-up fa-fw"></i> Upgraded to Pro plan
                            </div>
                            <div class="alert alert-info alert-alt">
                                <small>2 hours ago</small><br>
                                <i class="gi gi-coins fa-fw"></i> You had a new sale!
                            </div>
                            <div class="alert alert-success alert-alt">
                                <small>3 hours ago</small><br>
                                <i class="fa fa-plus fa-fw"></i> <a href="page_ready_user_profile.html"><strong>John Doe</strong></a> would like to become friends!<br>
                                <a href="javascript:void(0)" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Accept</a>
                                <a href="javascript:void(0)" class="btn btn-xs btn-default"><i class="fa fa-times"></i> Ignore</a>
                            </div>
                            <div class="alert alert-warning alert-alt">
                                <small>2 days ago</small><br>
                                Running low on space<br><strong>18GB in use</strong> 2GB left<br>
                                <a href="page_ready_pricing_tables.html" class="btn btn-xs btn-primary"><i class="fa fa-arrow-up"></i> Upgrade Plan</a>
                            </div>
                        </div>
                        <!-- END Activity -->

                        <!-- Messages -->
                        <a href="page_ready_inbox.html" class="sidebar-title">
                            <i class="fa fa-envelope pull-right"></i> <strong>Messages</strong>UI (5)
                        </a>
                        <div class="sidebar-section">
                            <div class="alert alert-alt">
                                Debra Stanley<small class="pull-right">just now</small><br>
                                <a href="page_ready_inbox_message.html"><strong>New Follower</strong></a>
                            </div>
                            <div class="alert alert-alt">
                                Sarah Cole<small class="pull-right">2 min ago</small><br>
                                <a href="page_ready_inbox_message.html"><strong>Your subscription was updated</strong></a>
                            </div>
                            <div class="alert alert-alt">
                                Bryan Porter<small class="pull-right">10 min ago</small><br>
                                <a href="page_ready_inbox_message.html"><strong>A great opportunity</strong></a>
                            </div>
                            <div class="alert alert-alt">
                                Jose Duncan<small class="pull-right">30 min ago</small><br>
                                <a href="page_ready_inbox_message.html"><strong>Account Activation</strong></a>
                            </div>
                            <div class="alert alert-alt">
                                Henry Ellis<small class="pull-right">40 min ago</small><br>
                                <a href="page_ready_inbox_message.html"><strong>You reached 10.000 Followers!</strong></a>
                            </div>
                        </div>
                        <!-- END Messages -->
                    </div>
                    <!-- END Sidebar Content -->
                </div>
                <!-- END Wrapper for scrolling functionality -->
            </div> --}}
            <!-- END Alternative Sidebar -->

            <!-- Main Sidebar -->
            <div id="sidebar">
                <!-- Wrapper for scrolling functionality -->
                <div class="sidebar-scroll">
                    <!-- Sidebar Content -->
                    <div class="sidebar-content">
                        <!-- Brand -->
                        <a href="{{ URL::to('/cp'); }}" class="sidebar-brand">
                            <i class="gi gi-flash"></i><strong>Ga</strong> Comunicacion
                        </a>
                        <!-- END Brand -->

                        <!-- User Info -->
                        <div class="sidebar-section sidebar-user clearfix">
                            <div class="sidebar-user-avatar">
                                <a href="{{ URL::to("cp/user/profile") }}">
                                    <img src="{{ asset('img/placeholders/avatars/avatar_default.png') }}" alt="avatar">
                                </a>
                            </div>
                            <div class="sidebar-user-name">{{ Auth::user()->username }}</div>
                            <div class="sidebar-user-links">
                                <!--<a href="{{ URL::to("cp/user/profile") }}" data-toggle="tooltip" data-placement="bottom" title="Perfil"><i class="gi gi-user"></i></a>-->
                                <!--<a href="{{ URL::to("cp/user/message") }}" data-toggle="tooltip" data-placement="bottom" title="Mensajes"><i class="gi gi-envelope"></i></a>-->
                                <!-- Opens the user settings modal that can be found at the bottom of each page (page_footer.html in PHP version) -->
                                @if(Auth::user()->role_id <= 1)
                                    <a href="#modal-user-settings" data-toggle="modal" class="enable-tooltip" data-placement="bottom" title="Configuracion"><i class="gi gi-cogwheel"></i></a>
                                @endif
                                <a href="{{ URL::to("cp/logout") }}" data-toggle="tooltip" data-placement="bottom" title="Salir"><i class="gi gi-exit"></i></a>
                            </div>
                        </div>
                        <!-- END User Info -->

                        <!-- Theme Colors -->
                        <!-- Change Color Theme functionality can be found in js/app.js - templateOptions() -->
                        <ul class="sidebar-section sidebar-themes clearfix">
                            <li class="active">
                                <a href="javascript:void(0)" class="themed-background-dark-default themed-border-default" data-theme="default" data-toggle="tooltip" title="Default Blue"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="themed-background-dark-night themed-border-night" data-theme="/css/themes/night.css" data-toggle="tooltip" title="Night"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="themed-background-dark-amethyst themed-border-amethyst" data-theme="/css/themes/amethyst.css" data-toggle="tooltip" title="Amethyst"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="themed-background-dark-modern themed-border-modern" data-theme="/css/themes/modern.css" data-toggle="tooltip" title="Modern"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="themed-background-dark-autumn themed-border-autumn" data-theme="/css/themes/autumn.css" data-toggle="tooltip" title="Autumn"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="themed-background-dark-flatie themed-border-flatie" data-theme="/css/themes/flatie.css" data-toggle="tooltip" title="Flatie"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="themed-background-dark-spring themed-border-spring" data-theme="/css/themes/spring.css" data-toggle="tooltip" title="Spring"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="themed-background-dark-fancy themed-border-fancy" data-theme="/css/themes/fancy.css" data-toggle="tooltip" title="Fancy"></a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="themed-background-dark-fire themed-border-fire" data-theme="/css/themes/fire.css" data-toggle="tooltip" title="Fire"></a>
                            </li>
                        </ul>
                        <!-- END Theme Colors -->

                        <!-- Sidebar Navigation -->
                        <ul class="sidebar-nav">
                            {{-- <li>
                                <a href="{{ URL::to('cp') }}" {{ isset($active) && $active=="dashboard"?'class="active"':'' }}><i class="gi gi-stopwatch sidebar-nav-icon"></i>Dashboard</a>
                            </li> --}}
                            @if(Auth::user()->role_id <= 1)
                            <!-- /Usuarios -->
                            <li class="sidebar-header">
                                <span class="sidebar-header-title">Usuarios</span>
                            </li>
                            <li>
                                <a href="{{ URL::to("cp/users") }}" {{ isset($active) && $active=="users"?'class="active"':'' }}><i class="gi gi-user sidebar-nav-icon"></i>Usuarios</a>
                            </li>
                            <!-- /Usuarios -->
                            @endif
                            <!-- /Notas -->
                            <li class="sidebar-header">
                                <span class="sidebar-header-options clearfix">
                                    <a title="" data-toggle="tooltip" href="javascript:void(0)" id="reload_cache" data-original-title="Recargar cache">
                                        <i class="gi gi-refresh"></i>
                                    </a>
                                </span>
                                <span class="sidebar-header-title">Notas</span>
                            </li>
                            <li>
                                <a href="#" class="sidebar-nav-menu {{ isset($main_active) && $main_active == 'characters' ? 'open' : '' }}"><i class="fa fa-angle-left sidebar-nav-indicator"></i><i class="gi gi-certificate sidebar-nav-icon"></i>Personajes</a>
                                <ul {{ isset($main_active) && $main_active == 'characters' ? 'style="display: block;"' : '' }}>
                                    <li>
                                        <a href="{{ URL::to('cp/character/212') }}" data-id="212" {{ isset($active) && $active == "212" ? 'class="active"' : '' }}>Claudia Artemisa Pavlovich Arellano <span class="count-212"></span></a>
                                    </li>
                                    <li>
                                        <a href="{{ URL::to('cp/character/926') }}" data-id="926" {{ isset($active) && $active == "926" ? 'class="active"' : '' }}>Javier Gándara Magaña <span class="count-926"></span></a>
                                    </li>
                                </ul>
                            </li>

                            <!-- /Electronicos -->
                            <li class="sidebar-header">
                                <span class="sidebar-header-title">Reportes</span>
                            </li>
                            <li>
                                <a href="{{ URL::to("cp/report/printed") }}" {{ isset($active) && $active=="impresos"?'class="active"':'' }}><i class="gi gi-notes_2 sidebar-nav-icon"></i>Impresos</a>
                            </li>

                            <!-- /Notas -->
                            <!--
                            <li class="sidebar-header">
                                <span class="sidebar-header-title">Misceláneos</span>
                            </li>
                            <li>
                                <a href="{{ URL::to("cp/misc/catalogs") }}" {{ isset($active) && $active=="catalogs"?'class="active"':'' }}><i class="gi gi-book sidebar-nav-icon"></i>Catalogos</a>
                            </li>
                            -->
                            <!-- /Notas -->
                        </ul>
                        <!-- END Sidebar Navigation -->

                    </div>
                    <!-- END Sidebar Content -->
                </div>
                <!-- END Wrapper for scrolling functionality -->
            </div>
            <!-- END Main Sidebar -->

            <!-- Main Container -->
            <div id="main-container">
                <!-- Header -->
                <!-- In the PHP version you can set the following options from inc/config file -->
                <!--
                    Available header.navbar classes:

                    'navbar-default'            for the default light header
                    'navbar-inverse'            for an alternative dark header

                    'navbar-fixed-top'          for a top fixed header (fixed sidebars with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar())
                        'header-fixed-top'      has to be added on #page-container only if the class 'navbar-fixed-top' was added

                    'navbar-fixed-bottom'       for a bottom fixed header (fixed sidebars with scroll will be auto initialized, functionality can be found in js/app.js - handleSidebar()))
                        'header-fixed-bottom'   has to be added on #page-container only if the class 'navbar-fixed-bottom' was added
                -->
                <header class="navbar navbar-default">
                    <!-- Left Header Navigation -->
                    <ul class="nav navbar-nav-custom">
                        <!-- Main Sidebar Toggle Button -->
                        <li>
                            <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar');">
                                <i class="fa fa-bars fa-fw"></i>
                            </a>
                        </li>
                        <!-- END Main Sidebar Toggle Button -->

                        <!-- Template Options -->
                        <!-- Change Options functionality can be found in js/app.js - templateOptions() -->
                        <!--
                        <li class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="gi gi-settings"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-custom dropdown-options">
                                <li class="dropdown-header text-center">Header Style</li>
                                <li>
                                    <div class="btn-group btn-group-justified btn-group-sm">
                                        <a href="javascript:void(0)" class="btn btn-primary" id="options-header-default">Light</a>
                                        <a href="javascript:void(0)" class="btn btn-primary" id="options-header-inverse">Dark</a>
                                    </div>
                                </li>
                                <li class="dropdown-header text-center">Page Style</li>
                                <li>
                                    <div class="btn-group btn-group-justified btn-group-sm">
                                        <a href="javascript:void(0)" class="btn btn-primary" id="options-main-style">Default</a>
                                        <a href="javascript:void(0)" class="btn btn-primary" id="options-main-style-alt">Alternative</a>
                                    </div>
                                </li>
                                <li class="dropdown-header text-center">Main Layout</li>
                                <li>
                                    <button class="btn btn-sm btn-block btn-primary" id="options-header-top">Fixed Side/Header (Top)</button>
                                    <button class="btn btn-sm btn-block btn-primary" id="options-header-bottom">Fixed Side/Header (Bottom)</button>
                                </li>
                                <li class="dropdown-header text-center">Footer</li>
                                <li>
                                    <div class="btn-group btn-group-justified btn-group-sm">
                                        <a href="javascript:void(0)" class="btn btn-primary" id="options-footer-static">Default</a>
                                        <a href="javascript:void(0)" class="btn btn-primary" id="options-footer-fixed">Fixed</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        -->
                        <!-- END Template Options -->
                    </ul>
                    <!-- END Left Header Navigation -->

                    <!-- Search Form -->
                    <form action="page_ready_search_results.html" method="post" class="navbar-form-custom" role="search">
                        <div class="form-group">
                            <input type="text" id="top-search" name="top-search" class="form-control" placeholder="Buscar..">
                        </div>
                    </form>
                    <!-- END Search Form -->

                    <!-- Right Header Navigation -->
                    <ul class="nav navbar-nav-custom pull-right">
                        <!-- Alternative Sidebar Toggle Button -->
                        <!-- d3v1an
                        <li>
                            // If you do not want the main sidebar to open when the alternative sidebar is closed, just remove the second parameter: App.sidebar('toggle-sidebar-alt'); 
                            <a href="javascript:void(0)" onclick="App.sidebar('toggle-sidebar-alt', 'toggle-other');">
                                <i class="gi gi-share_alt"></i>
                                <span class="label label-primary label-indicator animation-floating">4</span>
                            </a>
                        </li>
                        -->
                        <!-- END Alternative Sidebar Toggle Button -->

                        <!-- User Dropdown -->
                        <li class="dropdown">
                            <!-- d3v1an
                            <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ asset('img/placeholders/avatars/avatar_default.png') }}" alt="avatar"> <i class="fa fa-angle-down"></i>
                            </a>
                            -->
                            <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                                <li class="dropdown-header text-center">Account</li>
                                <li>
                                    <a href="page_ready_timeline.html">
                                        <i class="fa fa-clock-o fa-fw pull-right"></i>
                                        <span class="badge pull-right">10</span>
                                        Updates
                                    </a>
                                    <a href="page_ready_inbox.html">
                                        <i class="fa fa-envelope-o fa-fw pull-right"></i>
                                        <span class="badge pull-right">5</span>
                                        Messages
                                    </a>
                                    <a href="page_ready_pricing_tables.html"><i class="fa fa-magnet fa-fw pull-right"></i>
                                        <span class="badge pull-right">3</span>
                                        Subscriptions
                                    </a>
                                    <a href="page_ready_faq.html"><i class="fa fa-question fa-fw pull-right"></i>
                                        <span class="badge pull-right">11</span>
                                        FAQ
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="page_ready_user_profile.html">
                                        <i class="fa fa-user fa-fw pull-right"></i>
                                        Profile
                                    </a>
                                    <!-- Opens the user settings modal that can be found at the bottom of each page (page_footer.html in PHP version) -->
                                    <a href="#modal-user-settings" data-toggle="modal">
                                        <i class="fa fa-cog fa-fw pull-right"></i>
                                        Settings
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="page_ready_lock_screen.html"><i class="fa fa-lock fa-fw pull-right"></i> Lock Account</a>
                                    <a href="login.html"><i class="fa fa-ban fa-fw pull-right"></i> Logout</a>
                                </li>
                                <li class="dropdown-header text-center">Activity</li>
                                <li>
                                    <div class="alert alert-success alert-alt">
                                        <small>5 min ago</small><br>
                                        <i class="fa fa-thumbs-up fa-fw"></i> You had a new sale ($10)
                                    </div>
                                    <div class="alert alert-info alert-alt">
                                        <small>10 min ago</small><br>
                                        <i class="fa fa-arrow-up fa-fw"></i> Upgraded to Pro plan
                                    </div>
                                    <div class="alert alert-warning alert-alt">
                                        <small>3 hours ago</small><br>
                                        <i class="fa fa-exclamation fa-fw"></i> Running low on space<br><strong>18GB in use</strong> 2GB left
                                    </div>
                                    <div class="alert alert-danger alert-alt">
                                        <small>Yesterday</small><br>
                                        <i class="fa fa-bug fa-fw"></i> <a href="javascript:void(0)" class="alert-link">New bug submitted</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- END User Dropdown -->
                    </ul>
                    <!-- END Right Header Navigation -->
                </header>
                <!-- END Header -->

                <!-- Page content -->
                @yield('content')
                <!-- END Page Content -->

                <!-- Footer -->
                <footer class="clearfix">
                    <div class="pull-right">
                        Crafted with <i class="fa fa-heart text-danger"></i> by <a href="http://www.gacomunicacion.com" target="_blank">Ga Comunicacion</a>
                    </div>
                    <div class="pull-left">
                        <span id="year-copy"></span> &copy; <a href="http://www.d3catalyst.com" target="_blank">D3C UI</a>
                    </div>
                </footer>
                <!-- END Footer -->
            </div>
            <!-- END Main Container -->
        </div>
        <!-- END Page Container -->

        <!-- Scroll to top link, initialized in js/app.js - scrollToTop() -->
        <a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

        <!-- User Settings, modal which opens from Settings link (found in top right user menu) and the Cog link (found in sidebar user info) -->
        <div id="modal-user-settings" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header text-center">
                        <h2 class="modal-title"><i class="fa fa-pencil"></i> Configuracion</h2>
                    </div>
                    <!-- END Modal Header -->

                    <!-- Modal Body -->
                    <div class="modal-body">
                        <form action="user" method="post" id="form-user-settings" name="form-user-settings" class="form-horizontal form-bordered">
                            <input type="hidden" id="user-settings-user-id" name="user-settings-user-id" value="{{ Auth::user()->id }}">
                            <fieldset>
                                <legend>Informacion de usuario</legend>
                                <div class="form-group">
                                    <label class="col-md-4 control-label">Username</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">{{ Auth::user()->username }}</p>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-first-name">Nombre</label>
                                    <div class="col-md-8">
                                        <input type="text" id="user-settings-first-name" name="user-settings-first-name" class="form-control" value="{{ Auth::user()->first_name }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-last-name">Apellido</label>
                                    <div class="col-md-8">
                                        <input type="text" id="user-settings-last-name" name="user-settings-last-name" class="form-control" value="{{ Auth::user()->last_name }}">
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <legend>Actualizar contraseña</legend>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-password">Nueva Contraseña</label>
                                    <div class="col-md-8">
                                        <input type="password" id="user-settings-password" name="user-settings-password" class="form-control" placeholder="Nueva contraseña">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="user-settings-repassword">Confirmar Contraseña</label>
                                    <div class="col-md-8">
                                        <input type="password" id="user-settings-repassword" name="user-settings-repassword" class="form-control" placeholder="Confirmar">
                                    </div>
                                </div>
                            </fieldset>
                            <div class="form-group form-actions">
                                <div class="col-xs-12 text-right">
                                    <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-sm btn-primary">Guardar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- END Modal Body -->
                </div>
            </div>
        </div>
        <!-- END User Settings -->

        <!-- Dialogos -->
        @yield('dialogs')
        <!-- /Dialogos -->

        <!-- dialogo de confirmacion -->
        <div id="modal-confirm" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <form action="confirm" method="post" id="form-modal-confirm" name="form-modal-confirm">
                <input type="hidden" id="fmc-param" name="fmc-param">
                <input type="hidden" id="fmc-value" name="fmc-value">
            </form>
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header text-center">
                        <h2 class="modal-title"><i class="gi gi-circle_exclamation_mark"></i> Confirmacion</h2>
                    </div>
                    <!-- END Modal Header -->
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <span class="confirm-content"></span>
                    </div>
                    <!-- END Modal Body -->
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-sm btn-primary" id="button-modal-confirm">Confirmar</button>
                    </div>
                    <!-- END Modal Footer -->
                </div>
            </div>
        </div>
        <!-- END dialogo de confirmacion -->

        <!-- Include Jquery library from Google's CDN but if something goes wrong get Jquery from local file (Remove 'http:' if you have SSL) -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
        <script>!window.jQuery && document.write(decodeURI('%3Cscript src="{{ URL::to('js/vendor/jquery-1.11.1.min.js') }}"%3E%3C/script%3E'));</script>

        <!-- Bootstrap.js, Jquery plugins and Custom JS code -->
        {{ HTML::script('js/vendor/bootstrap.min.js') }}
        {{ HTML::script('js/plugins.js') }}
        {{ HTML::script('js/d3.commons.js') }}
        {{ HTML::script('js/app.js') }}

        @yield('scripts')
    </body>
</html>
