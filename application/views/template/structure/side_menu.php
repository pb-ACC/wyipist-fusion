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
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?php echo base_url();?>home" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-industry"></i>
                        <p>
                            Stocks 
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?=base_url();?>stocks/saida_producao" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Saída Produção</p>
                            </a>
                        </li>  

                        <li class="nav-item">
                            <a href="<?=base_url();?>stocks/rececao_material" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Receção Int. de Mat.</p>
                            </a>
                        </li>  
                                            
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Mov. Internas
                                <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item">
                                    <a href="<?=base_url();?>stocks/movimentacoes_internas/troca_localizacao" class="nav-link">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Troca Localização</p>
                                    </a>
                                </li>

                                <?php if($user_type == 1 || $user_type == 2 || $user_type == 6){ ?>
                                    <li class="nav-item">
                                        <a href="<?=base_url();?>stocks/movimentacoes_internas/fabrica_centro" class="nav-link">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Fábrica <i class="fas fa-arrow-right"></i> Centro</p>
                                        </a>
                                    </li>
                                <?php }?>
                                
                                <?php if($user_type == 1 || $user_type == 2 || $user_type == 5){ ?>
                                    <li class="nav-item">
                                        <a href="<?=base_url();?>stocks/movimentacoes_internas/centro_fabrica" class="nav-link">
                                            <i class="far fa-dot-circle nav-icon"></i>
                                            <p>Centro <i class="fas fa-arrow-right"></i> Fábrica</p>
                                        </a>
                                    </li>
                                <?php }?>
                            </ul>
                        </li>  
                        <?php if($user_type == 1 || $user_type == 2){ ?>
                            <li class="nav-item">
                                <a href="<?=base_url();?>stocks/listas" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Listas</p>
                                </a>
                            </li>  
                        <?php }?>                            
                        <li class="nav-item">
                            <a href="<?=base_url();?>stocks/nao_conformidade" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Não Conformidade</p>
                            </a>
                        </li>  
                        <li class="nav-item">
                            <a href="<?=base_url();?>stocks/caixas_partidas" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Caixas Partidas</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=base_url();?>stocks/gera_palete_phc" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gerar Palete via PHC</p>
                            </a>
                        </li>   
                        <li class="nav-item">
                            <a href="<?=base_url();?>stocks/paletes_sem_stock" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Paletes Sem Stock</p>
                            </a>
                        </li> 


                        
                        <li class="nav-item">

                        </li> 

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Anulação Paletes
                                <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item">
                                    <a href="<?=base_url();?>stocks/anular_paletes/anular_palete" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Anular Paletes PLP</p>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item">
                                    <a href="<?=base_url();?>stocks/anular_paletes/anular_palete" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Anular Paletes em PNC</p>
                                    </a>
                                </li>
                            </ul>
                        </li> 

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Plano Cargas
                                <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: none;">
                                <li class="nav-item">
                                    <a href="<?=base_url();?>stocks/preparacao_carga" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Preparação de Cargas</p>
                                    </a>
                                </li>                                 
                                <li class="nav-item">
                                <a href="<?=base_url();?>stocks/anulacao_preparacao_carga" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Anulação Prep. de Cargas</p>
                                    </a>
                                </li> 
                                <li class="nav-item">
                                <a href="<?=base_url();?>stocks/correcao_preparacao_carga" class="nav-link">
                                    <i class="far fa-dot-circle nav-icon"></i>
                                        <p>Correção Prep. de Cargas</p>
                                    </a>
                                </li> 
                            </ul>
                        </li>  

                        <li class="nav-item">
                            <a href="<?=base_url();?>stocks/reembalagem" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Reembalagem</p>
                            </a>
                        </li> 

                        <li class="nav-item">
                            <a href="<?=base_url();?>stocks/gera_palete_subcontratos" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Gerar PL Subcontratados</p>
                            </a>
                        </li> 

                    </ul>
                </li>

                <?php if ($user_type==1) { ?>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-dice-d6"></i>
                        <p>
                            Gestão
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?=base_url();?>utilizadores" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Utilizadores</p>
                            </a>
                        </li>

                        <!--
                        <li class="nav-item">
                            <a href="<?=base_url();?>fornecedores" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Fornecedores</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=base_url();?>artigos" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Artigos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=base_url();?>familias" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Famílias de Artigos</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=base_url();?>qualidade" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Qualidade</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?=base_url();?>templates" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Templates Importação</p>
                            </a>
                        </li>
                        -->
                    </ul>
                </li>
                <?php } ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>