<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?=base_url();?>index.php" class="brand-link">
        <img src="<?=base_url();echo $logo_empresa;?>" alt="Logo Empresa" class="brand-logo"
             style="opacity: .8">
        <span class="brand-text font-weight-light" style="margin-left: 10px;"><?php echo $empresa;?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?=base_url();echo $logo_user;?>" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $nome;?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <?php if ($user_type==1) { ?>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-gears"></i>
                        <p> Gestão <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview" style="display: none;">                    
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa-solid fa-users"></i>
                                <p> Utilizadores <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item">
                                    <a href="<?=base_url();?>users/all-users" class="nav-link">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <p> Consultar </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fa-solid fa-user-plus"></i>
                                        <p> Adicionar </p>
                                    </a>
                                </li>                    
                            </ul>
                        </li>
                    
                    </ul>
                </li>
            <?php } ?>         
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>