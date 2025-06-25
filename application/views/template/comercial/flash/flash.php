<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <h1 class="m-0"><i>Flash</i></h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-house"></i><a href="<?php echo base_url(); ?>home"> Página Inicial</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-receipt"></i> Comercial</li> 
                        <li class="breadcrumb-item active"><i class="fa-solid fa-bolt "></i> <i>Flash</i></li>
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
                    <h3 class="card-title">Empresa para consulta de <i>Stock</i></h3>
                </div>
                <div class="card-body">                      
                    <div class="row">
                        <div id="empresas-table" class="table table-stripped"  style="margin-top: 10px;box-shadow: 5px 10px 18px #888888;">
                        </div>
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
</script>

<script src="<?=base_url();?>js/comercial/flash/flash.js"></script>