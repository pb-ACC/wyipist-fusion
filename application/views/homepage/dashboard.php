
<div class="content-wrapper">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header" style="border-bottom: transparent;">                   
            </div>
        
            <div class="card-body">

            <?php if($user_type == 1 || $user_type == 2){ ?>
                <div class="container-fluid">
                    <div class="row">
                    </div>                    
                </div>
            <?php }?>

            <?php if($user_type == 3){ ?>
                <div class="container-fluid">
                    <div class="row">
                    </div>                    
                </div>
            <?php }?>

            <?php if($user_type == 4){ ?>
                <div class="container-fluid">
                    <div class="row">
                    </div>                    
                </div>
            <?php }?>

            <?php if($user_type == 5 || $user_type == 6){ ?>
                <div class="container-fluid">                    
                    <div class="row">
                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/saida_producao" class="btn btn-app bg-secondary" style="height: 95%; width: 95%;">
                                <i class="fas fa-boxes" style="font-size: x-large"></i></i>&nbsp;<p style="font-size: initial;">Saída<br>Produção</p>
                            </a>
                        </div>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/rececao_material" class="btn btn-app bg-default" style="height: 95%; width: 95%;">
                                <i class="fas fa-box-open" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Receção<br>Interna de<br>Material</p>
                            </a>
                        </div>  

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/preparacao_carga" class="btn btn-app bg-lime" style="height: 95%; width: 95%;">
                                <i class="fas fa-dolly" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Prepação<br>de<br>Cargas</p>
                            </a>
                        </div>    

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/anulacao_preparacao_carga" class="btn btn-app bg-fuchsia" style="height: 95%; width: 95%;">
                                <i class="fas fa-ban" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Anulação<br>Preparação de<br>Carga</p>
                            </a>
                        </div>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/correcao_preparacao_carga" class="btn btn-app bg-gray" style="height: 95%; width: 95%;">
                                <i class="fas fa-pencil-ruler" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Correção<br>Preparação de<br>Carga</p>
                            </a>
                        </div>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/movimentacoes_internas/troca_localizacao/troca_localizacao" class="btn btn-app bg-primary" style="height: 95%; width: 95%;">
                                <i class="fas fa-truck-loading" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Movimentações<br>Internas<br>(Troca Localização)</p>
                            </a>
                        </div>

                        <?php if($user_type == 1 || $user_type == 2 || $user_type == 6){ ?>
                            <div class="col-lg-2 col-6" style="padding: 0;">
                                <a href="<?=base_url();?>stocks/movimentacoes_internas/troca_localizacao/fabrica_centro" class="btn btn-app bg-info" style="height: 95%; width: 95%;">
                                    <i class="fas fa-truck-moving" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Movimentações<br>Internas<br>(Fábrica <i class="fas fa-arrow-right" style="font-size: x-large"></i> Centro)</p>
                                </a>
                            </div>
                        <?php }?>

                        <?php if($user_type == 1 || $user_type == 2 || $user_type == 5){ ?>
                            <div class="col-lg-2 col-6" style="padding: 0;">
                                <a href="<?=base_url();?>stocks/movimentacoes_internas/troca_localizacao/centro_fabrica" class="btn btn-app bg-cyan" style="height: 95%; width: 95%;">
                                    <i class="fas fa-truck-moving" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Movimentações<br>Internas<br>(Centro <i class="fas fa-arrow-right" style="font-size: x-large"></i> Fábrica)</p>
                                </a>
                            </div>
                        <?php }?>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/nao_conformidade"  class="btn btn-app bg-warning" style="height: 95%; width: 95%;">
                                <i class="fas fa-times" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Não<br>Conformidade</p>
                            </a>
                        </div>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/caixas_partidas"  class="btn btn-app bg-danger" style="height: 95%; width: 95%;">
                                <i class="fas fa-house-damage" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Caixas<br>Partidas</p>
                            </a>
                        </div>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/paletes_sem_stock" class="btn btn-app bg-olive" style="height: 95%; width: 95%;">
                                <i class="fas fa-pallet" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Paletes Sem<br>Stock</p>
                            </a>
                        </div>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/anular_paletes/anular_palete" class="btn btn-app bg-indigo" style="height: 95%; width: 95%;">
                                <i class="fas fa-history" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Anular Palete<br>PLP</p>
                            </a>
                        </div>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/anular_paletes/anular_palete_pnc" class="btn btn-app bg-indigo" style="height: 95%; width: 95%;">
                                <i class="fas fa-history" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Anular Palete<br>em PNC</p>
                            </a>
                        </div>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/reembalagem" class="btn btn-app bg-maroon" style="height: 95%; width: 95%;">
                                <i class="fas fa-tape" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Reembalagem</p>
                            </a>
                        </div>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/gera_palete_subcontratos" class="btn btn-app bg-pink" style="height: 95%; width: 95%;">
                                <i class="fas fa-file-contract" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Gerar PL<br>Subcontratos</p>
                            </a>
                        </div>

                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/gera_palete_phc" class="btn btn-app bg-dark" style="height: 95%; width: 95%;">
                                <i class="fas fa-cubes" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Gerar Palete<br>via PHC</p>
                            </a>
                        </div>

                        <?php if($user_type == 1 || $user_type == 2){?>
                            <div class="col-lg-2 col-6" style="padding: 0;">
                                <a href="<?=base_url();?>stocks/listas" class="btn btn-app bg-success" style="height: 95%; width: 95%;">
                                    <i class="fas fa-clipboard-list" style="font-size: x-large"></i>&nbsp;<p  style="font-size: initial;">Listas</p>
                                </a>
                            </div>
                        <?php }?>

                    </div>
                </div>
            <?php }?>   

            </div>            
            <div class="card-footer" style="display: block; background-color: transparent;">
            </div>		
        </div>
    </div>
</section>
</div>


