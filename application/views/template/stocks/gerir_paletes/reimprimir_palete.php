<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reimprimir Paletes</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-house"></i><a href="<?php echo base_url(); ?>home"> Página Inicial</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-boxes-packing"></i> Stocks</li> 
                        <li class="breadcrumb-item active"><i class="fa-solid fa-print"></i> Reimprimir Paletes</li>
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
                   <h3 class="card-title">Escolha Palete</h3>
                </div>

                <div class="card-body">
                    <div id="select">
                    </div>
                    <div id="radioButtons" class="form-group" style="margin-left: 5px; margin-top: 10px;">
                    </div>
                    <div id="selected-palets-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                    </div>
                </div>
                
                <div class="card-footer">                    
                    <div id="buttons"></div>
                </div>

            </div>

        </div>
    </section>
</div>

<script>
let user_type=<?php echo $user_type;?>;
let codigoempresa=<?php echo $codigoempresa;?>;
</script>
<script src="<?=base_url();?>js/reload.js"></script>
<script src="<?=base_url();?>js/stocks/gerir_paletes/reimprimir_paletes.js"></script>