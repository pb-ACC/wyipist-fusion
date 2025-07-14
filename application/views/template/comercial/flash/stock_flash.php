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
                        <div id="plano-carga-table" class="table table-striped"  style="box-shadow: 5px 10px 18px #888888;">
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

<div id="detalhes-linha" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl"role="document" style="width:auto">
    <div class="modal-content">
            <div class="modal-header">
                <div id="modal-title">
                </div>
				<button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<!--<div class="modal-body" style="max-height: calc(100vh - 210px);overflow-y:scroll;overflow-x:scroll;">-->
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0" style="background-color: whitesmoke">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-four-enc-tab" data-toggle="pill" href="#custom-tabs-four-enc" role="tab" aria-controls="custom-tabs-four-enc" aria-selected="true">Encomendas em carteira</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-four-lotpal-tab" data-toggle="pill" href="#custom-tabs-four-lotpal" role="tab" aria-controls="custom-tabs-four-lotpal" aria-selected="false">Lotes e Paletes</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-four-preenc-tab" data-toggle="pill" href="#custom-tabs-four-preenc" role="tab" aria-controls="custom-tabs-four-preenc" aria-selected="false">Pré-Encomendas Pendentes</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <div class="tab-pane fade active show" id="custom-tabs-four-enc" role="tabpanel" aria-labelledby="custom-tabs-four-enc-tab">
                                        <div id="enc-table" class="table table-striped"  style="box-shadow: 5px 10px 18px #888888;">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-four-lotpal" role="tabpanel" aria-labelledby="custom-tabs-four-lotpal-tab">
                                        <h2 class="card-title"><i>Stock</i> por Lote</h2>
                                        <div id="lotes-table" class="table table-striped"  style="box-shadow: 5px 10px 18px #888888; margin-top: 5px;">
                                        </div>
                                        <br><br>
                                        <h2 class="card-title">Paletes</h2>
                                        <div id="paletes-table" class="table table-striped"  style="box-shadow: 5px 10px 18px #888888; margin-top: 5px;">
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="custom-tabs-four-preenc" role="tabpanel" aria-labelledby="custom-tabs-four-preenc-tab">
                                        <div id="preenc-table" class="table table-striped"  style="box-shadow: 5px 10px 18px #888888;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<div class="modal-footer">		                                
                <div id="modal_buttons"></div>
			</div>    
    </div>
  </div>
</div>

<div id="modal-blocker" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.6); z-index: 9999; cursor: not-allowed; display: none;">
</div>

<script>
let user_type=<?php echo $user_type;?>;
let codigoempresa=<?php echo $codigoempresa;?>;
let aVer='<?=$_SESSION['empTC']?>';
</script>

<script src="<?=base_url();?>js/comercial/flash/stock_flash.js"></script>