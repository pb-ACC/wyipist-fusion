<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <h1 class="m-0"><i>Flash</i> (<?=$_SESSION['empTC']?>)</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-house"></i><a href="<?php echo base_url(); ?>home"> Página Inicial</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-receipt"></i> Comercial</li> 
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-bolt "></i><a href="<?php echo base_url(); ?>commercial/terminal_flash">  <i>Flash</i></a></li>
                        <li class="breadcrumb-item active"><i class="fa-solid fa-id-badge"></i> <?=$_SESSION['empTC']?></li>
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
                    <h3 class="card-title">Listagem <i>Stock</i></h3>
                    <div class="pull pull-right" style="float: right;">									
                        <button class="btn btn-light btn-sm btn-flat" title="Atualizar Página" onclick="location.reload();" style="margin-right: 10px;float: right">Atualizar Página</button>
					</div>
                </div>
                <div class="card-body">                      
                    <div class="row">

                    </div>
                </div>
                <div class="card-footer" style="background-color:transparent">                      
                        <div class="row">                              
                        </div>   
                </div>
        </div>
    </section>
</div>



<script>
let user_type=<?php echo $user_type;?>;
let codigoempresa=<?php echo $codigoempresa;?>;
let aVer='<?=$_SESSION['empTC']?>';
</script>

<script src="<?=base_url();?>js/comercial/flash/stock_flash.js"></script>