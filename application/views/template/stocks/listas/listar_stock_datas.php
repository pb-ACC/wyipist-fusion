<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Consulta de Stock entre datas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-house"></i><a href="<?php echo base_url(); ?>home"> Página Inicial</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-boxes-packing"></i> Stocks</li> 
                        <li class="breadcrumb-item"><i class="fa-solid fa-clipboard-list"></i> Listagem de Stock</li>
                        <li class="breadcrumb-item active"><i class="fa-solid fa-clipboard"></i> Consulta de Stock entre datas</li>
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
                   <h3 class="card-title">Lista de Paletes</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div id="select" class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                        </div>
                        <div id="date_range" class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-2 col-lg-push-2">                           
                            <div class="input-group">
                                <input type="text" class="form-control" name="daterange" placeholder="Select date range"/>
                                <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="selected-palets-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                    </div>
                </div>                
                <div class="card-footer"></div>
            </div>

        </div>
    </section>
</div>

<script>
let user_type=<?php echo $user_type;?>;
let codigoempresa=<?php echo $codigoempresa;?>;
</script>

<script src="<?=base_url();?>js/stocks/listas/listar_stock_datas.js"></script>