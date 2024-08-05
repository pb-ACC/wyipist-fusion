<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Plano de Carga <?=$_SESSION['PlanoGG']?></h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-house"></i><a href="<?php echo base_url(); ?>home"> Página Inicial</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-shipping-fast"></i> Planos de Carga</li> 
                        <li class="breadcrumb-item"><i class="fa-solid fa-dolly-flatbed"></i><a href="<?php echo base_url(); ?>load_plans/load_preparation"> Preparação de Cargas</a></li>
                        <li class="breadcrumb-item active"><i class="fa-solid fa-file-alt"></i> <?=$_SESSION['PlanoGG']?></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <div class="card card-info">
                <div class="card-header">
                   <h3 class="card-title">Linhas do Plano de Carga <?=$_SESSION['PlanoGG']?></h3>
                </div>

                <div class="card-body">
                    <div id="plano-carga-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                    </div>
                </div>                
                <div class="card-footer">                                        
                </div>
            </div>

        </div>
    </section>
</div>

<script>
let user_type=<?php echo $user_type;?>;
let codigoempresa=<?php echo $codigoempresa;?>;
let plano='<?=$_SESSION['PlanoGG']?>';
let serie='<?=$_SESSION['SerieGG']?>';
</script>

<script src="<?=base_url();?>js/planos_carga/preparacao/linhas_plano_carga.js"></script>