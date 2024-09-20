<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Wyipi Stock Terminal</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=base_url();?>public/plugins/fontawesome-free/css/all.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bbootstrap 4 -->
    <link rel="stylesheet" href="<?=base_url();?>public/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?=base_url();?>public/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="<?=base_url();?>public/plugins/jqvmap/jqvmap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=base_url();?>public/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="<?=base_url();?>public/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?=base_url();?>public/plugins/daterangepicker/daterangepicker.css">
    <!-- summernote -->
    <link rel="stylesheet" href="<?=base_url();?>public/plugins/summernote/summernote-bs4.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- JQuery -->
    <script src="<?=base_url();?>public/plugins/jquery/jquery.min.js"></script>
    <script src="<?=base_url();?>public/plugins/jquery-ui/jquery-ui.min.js"></script>
    <!-- bootstrap password toggler -->
    <script  src="<?=base_url();?>public/js/password-toggler/bootstrap-password-toggler.min.js"></script>
    <!--Tabulator-->
    <!--<script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.4.3/js/tabulator.min.js"></script>-->
    <!--<script type="text/javascript" src="https://unpkg.com/tabulator-tables@4.7.2/js/tabulator.min.js"></script>-->
    <script type="text/javascript" src="<?=base_url();?>public/js/tabulator/tabulator.min.js"></script>
    <link href="<?=base_url();?>public/css/tabulator/tabulator_bootstrap5.min.css" rel="stylesheet">
    <!--blockUI-->
    <script type="text/javascript" src="<?=base_url();?>public/js/blockUI/jquery.blockUI.js"></script>
    <!-- Select2 -->
    <link rel="stylesheet" href="<?=base_url();?>public/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?=base_url();?>public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- toastr -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?=base_url();?>public/js/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- image Picker -->
    <link href=" https://cdnjs.cloudflare.com/ajax/libs/image-picker/0.3.1/image-picker.css" rel="stylesheet">
    <!--toogle-->
    <link href="<?=base_url();?>public/js/bootstrap4_toogle/bootstrap4-toggle.min.css" rel="stylesheet">
    <!--Sweet Alert-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.12.2/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="icon" href="<?=base_url();?>public/img/logos/wyipi.ico">

    <link rel="stylesheet" href="<?=base_url();?>public/css/stockterminal.css">
    <script src="<?=base_url();?>js/header/main.js"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <!--
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge" id="quantas"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header" id="head_notificacoes"></span>
                    <?php if ($user_type!=1 and $user_type!=2) { ?>
                    <div class="dropdown-divider"></div>
                    <a href="<?=base_url();?>notificacoes/novas/2" class="dropdown-item">
                        <i class="fas fa-envelope mr-2 text-info"></i>
                        <span id="head_novas"></span>
                    </a>
                    <?php } ?>
                    <?php if ($user_type!=2) { ?>
                    <div class="dropdown-divider"></div>
                    <a href="<?=base_url();?>notificacoes/negociadas/2" class="dropdown-item">
                        <i class="fas fa-envelope mr-2 text-secondary"></i>
                        <span id="head_negociadas"></span>
                    </a>
                    <?php } ?>
                    <?php if ($user_type!=2) { ?>
                    <div class="dropdown-divider"></div>
                    <a href="<?=base_url();?>notificacoes/ativas/2" class="dropdown-item">
                        <i class="fas fa-envelope mr-2 text-success"></i>
                        <span id="head_ativas"></span>
                    </a>
                    <?php } ?>
                    <?php if ($user_type!=2) { ?>
                    <div class="dropdown-divider"></div>
                    <a href="<?=base_url();?>notificacoes/rejeitadas/2" class="dropdown-item">
                        <i class="fas fa-envelope mr-2 text-danger"></i>
                        <span id="head_rejeitadas"></span>
                    </a>
                    <?php } ?>
                    <?php if ($user_type!=2) { ?>
                    <div class="dropdown-divider"></div>
                    <a href="<?=base_url();?>notificacoes/fechadas/2" class="dropdown-item">
                        <i class="fas fa-envelope mr-2 text-primary"></i>
                        <span id="head_fechadas"></span>
                    </a>
                    <?php } ?>
                    <?php if ($user_type!=2) { ?>
                    <div class="dropdown-divider"></div>
                    <a href="<?=base_url();?>notificacoes/todas/1" class="dropdown-item">
                        <i class="fas fa-exclamation-triangle mr-2 text-danger"></i>
                        <span id="head_atrasadas"></span>
                    </a>
                    <?php } ?>
                    <div class="dropdown-divider"></div>
                    <a href="<?=base_url();?>notificacoes/ticket/2" class="dropdown-item">
                        <i class="fas fa-hands-helping mr-2 text-gray-dark"></i>
                        <span id="head_tickets"></span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="<?=base_url();?>notificacoes/todas/2" class="dropdown-item dropdown-footer">Ver todas as Notificações</a>
                </div>

            </li>
                    -->

            <li class="nav-item dropdown" style="min-width: 154px;">
                <a href="#" class="nav-link" data-toggle="dropdown">
                    <span class="hidden-xs"><?php echo $nome;?>&nbsp;&nbsp;</span>
                    <i class="fas fa-circle fa-2xs" style="color: limegreen;"></i>                    
                    <img src="<?=base_url();echo $logo_user;?>" class="user-image" alt="User Image">
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <!--
                    <li class="user-header">
                        <img src="<?=base_url(); echo $logo_user;?>" class="img-circle" alt="User Image" style="height: 120px; width: 90px;">
                    </li>       
                    -->         
                    <!--<li style="text-align: center;">
                        <i class="fas fa-circle fa-2xs" style="color: limegreen;">&nbsp;&nbsp;</i> <?php echo $nome;?>
                    </li>
                    <li style="text-align: center;">
                        <?php echo $funcao;?>
                    </li>
                    -->
                    <!-- Menu Footer-->
                    <li class="user-footer">                        
                        <a href="<?=base_url();?>home/logout" class="btn btn-secondary btn-flat" style="width: 100%"> <i class="fas fa-sign-out" style="margin-right: 5px"></i>&nbsp;Sair</a>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>
    <!-- /.navbar -->
<script src="<?=base_url();?>js/homepage/main.js"></script>