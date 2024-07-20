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
                <li id="menu-stocks01" class="nav-item">
                    <a id="menu-stocks02" href="#" class="nav-link">
                        <i class="fa-solid fa-boxes-packing"></i>
                        <p>  Stocks <i class="fas fa-angle-left right"></i> </p>
                    </a>
                    <ul id="menu-stocks03" class="nav nav-treeview" style="display: none;">
                        <li id="saida-prod01" class="nav-item">
                            <a id="saida-prod02" href="<?=base_url();?>stocks/production" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Saída Produção</p>
                            </a>
                        </li>                              
                        <li id="menu-mov01" class="nav-item">
                            <a id="menu-mov02" href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Mov. Internas <i class="right fas fa-angle-left"></i> </p>
                            </a>
                            <ul id="opcoes-mov01" class="nav nav-treeview" style="display: none;">
                                <li id="troca-loc01" class="nav-item">
                                    <a id="troca-loc02" href="<?=base_url();?>stocks/internal_movements/change_location" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p> Troca Localização </p>
                                    </a>
                                </li>                      
                            </ul>
                        </li>
                        <li id="anula-pl01" class="nav-item">
                            <a id="anula-pl02" href="<?=base_url();?>stocks/manage_palettes/cancel_palettes" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Anular Paletes</p>
                            </a>
                        </li> 
                        <?php if($user_type == 1 || $user_type == 2){ ?>
                        <li id="menu-list01" class="nav-item">
                            <a id="menu-list02" href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Listagem de Stock <i class="right fas fa-angle-left"></i> </p>
                            </a>
                            <ul id="opcoes-list01" class="nav nav-treeview" style="display: none;">
                                <li id="list-stock01" class="nav-item">
                                    <a id="list-stock02" href="<?=base_url();?>stocks/stock_list/all-stock" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Consulta de Stock</p>
                                    </a>
                                </li>                  
                                <li id="list-stock03" class="nav-item">
                                    <a id="list-stock04" href="<?=base_url();?>stocks/stock_list/stock-between-dates" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Cons. Stock datas</p>
                                    </a>
                                </li>                  
                            </ul>
                        </li>
                        <?php }?>  
                    </ul>
                </li>
            <?php if ($user_type==1 || $user_type==2) { ?>
                <li id="menu-gestao01" class="nav-item">
                    <a id="menu-gestao02" href="#" class="nav-link">
                        <i class="nav-icon fas fa-gears"></i>
                        <p> Gestão <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul id="menu-user01" class="nav nav-treeview" style="display: none;">                    
                        <li id="menu-user02" class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa-solid fa-users"></i>
                                <p> Utilizadores <i class="right fas fa-angle-left"></i></p>
                            </a>
                            <ul id="opcoes-user01" class="nav nav-treeview" style="display: none;">
                                <li id="consultar-user01" class="nav-item">
                                    <a id="consultar-user02" href="<?=base_url();?>users/all-users" class="nav-link">
                                        <i class="fa-solid fa-magnifying-glass"></i>
                                        <p> Consultar </p>
                                    </a>
                                </li>
                                <li id="adicionar-user01" class="nav-item">
                                    <a id="adicionar-user02" href="<?=base_url();?>users/add-user" class="nav-link">
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