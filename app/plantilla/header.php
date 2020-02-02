<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>PULPO MASTERS</title>
    <meta name="description" content="Aplicación para registro de partidos del juego de PS4 Pro Evolution Soccer">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo ASSETS ?>/img/favicon.ico">
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,700,900" rel="stylesheet">
 <!--
    <link rel="preload" as="font"  href="<?php echo ASSETS ?>/fonts/Ubuntu-Regular.ttf" type="font/ttf" crossorigin>
    -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu&display=swap" rel="stylesheet"> 
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/font-awesome.min.css">
	<!-- nalika Icon CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/nalika-icon.css">
    <!-- owl.carousel CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/owl.theme.css">
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/owl.transitions.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/animate.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/normalize.css">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/meanmenu.min.css">
    <!-- main CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/main.css">
    <!-- morrisjs CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/morrisjs/morris.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/scrollbar/jquery.mCustomScrollbar.min.css">
    <!-- metisMenu CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/metisMenu/metisMenu.min.css">
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/metisMenu/metisMenu-vertical.css">
    <!-- calendar CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/calendar/fullcalendar.min.css">
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/calendar/fullcalendar.print.min.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <!--script src="js/vendor/modernizr-2.8.3.min.js"></script -->
    <!-- ESTILOS PERSONALIZADOS
    ============================================ -->
    <link rel="stylesheet" href="<?php echo ASSETS ?>/css/estilos.css">
    <link rel="stylesheet" href="<?php echo CSS ?>/bracket/jquery.bracket.min.css">
      <!-- style GETORGCHART
		============================================ -->
        <link rel="stylesheet" href="<?php echo ASSETS ?>/css/getorgchart/getorgchart.css">

    <script src="https://checkout.culqi.com/js/v3"></script>
    <script src="<?php echo ASSETS ?>/js/getorgchart/getorgchart.js"></script>

</head>

<body>

    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
        <div class="left-sidebar-pro">
            <nav id="sidebar" class="">
                <div class="sidebar-header" id="logo-div">
                    <a href="#portada-index"><img class="main-logo" src="<?php echo ASSETS ?>/img/logo/logo.png" alt="" /></a>
                </div>

                <div class="left-custom-menu-adp-wrap comment-scrollbar">
                    <nav class="sidebar-nav left-sidebar-menu-pro">
                        <ul class="metismenu" id="menu1">

                        </ul>
                    </nav>
                </div>
            </nav>
        </div>
        <div class="" id="vue_app">
    <!-- Start Welcome area -->
    <div class="all-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <br>
               <br>
               <br>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="logo-pro">
                        <a href="/"></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="header-advance-area">
            <div class="header-top-area">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="header-top-wraper">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-3-col-xs-12">
                                        <div class="menu-switcher-pro">
                                            <!-- <button type="button" id="sidebarCollapse" class="btn bar-button-pro header-drl-controller-btn btn-info navbar-btn">
													<i class="icon nalika-menu-task"></i>
                                                </button> -->
                                                <span class="titulo">PULPO MASTERS</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                                        <div class="header-top-menu tabl-d-n hd-search-rp">
                                            <div class="breadcome-heading">

											</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                        <div class="header-right-info">
                                            <ul class="nav navbar-nav mai-top-nav header-right-menu">

                                                <li class="nav-item">
                                                    <a href="#" data-toggle="dropdown" role="button" aria-expanded="false" class="nav-link dropdown-toggle">
                                                            <i class="icon nalika-user"></i>
															<span class="admin-name"><?php echo $_SESSION["usuario"][0]["nombre"] . ' '. $_SESSION["usuario"][0]["apellido"]; ?></span>
															<i class="icon nalika-down-arrow nalika-angle-dw"></i>
														</a>
                                                    <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated zoomIn">
                                                        <li><a href="#usuario-perfil"><span class="icon nalika-user author-log-ic"></span> Mi Perfil</a>
                                                        </li>
                                                        <li><a href="<?php $this->url("cerrar", "acceso"); ?>"><span class="icon nalika-unlocked author-log-ic"></span>Salir</a>
                                                        </li>
                                                    </ul>
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu start -->
            <div class="mobile-menu-area">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="mobile-menu">
                                <nav id="dropdown">
                                    <ul class="mobile-menu-nav" id="menu-mobile">

                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu end -->
            <div class="breadcome-area" >
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="breadcome-list">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="breadcomb-wp">
											<div class="breadcomb-icon">
												<a href="#portada-index"><i class="icon nalika-home"></i></a>
											</div>
											<div class="breadcomb-ctn" id="breadcome-area-cabecera">


											</div>
										</div>
                                    </div>
                                     <!--
                                    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                         <div class="breadcomb-report">
											<button data-toggle="tooltip" data-placement="left" title="Download Report" class="btn"><i class="icon nalika-download"></i></button>
										</div> 
                                    </div>
                                    -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
