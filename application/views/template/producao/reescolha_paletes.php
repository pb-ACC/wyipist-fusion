<div class="content-wrapper">

<section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reescolha</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-house"></i><a href="<?php echo base_url(); ?>home"> Página Inicial</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fab fa-product-hunt"></i> Produção</li>    
                        <li class="breadcrumb-item active"><i class="fas fa-pencil-ruler"></i> Reescolha</li>
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
                   <h3 class="card-title">Escolha Referência</h3>
                </div>

                <div class="card-body">                
                    <div class="row">
                        <div id="select" class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                        </div>
                        <div id="code_bar" class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-barcode"></i>
                                    </span>
                                </div>                      
                            <input id="cb" type="text" class="form-control" name="cb"  onchange="filter_cb()"  placeholder="Código Barras"/>                                                          
                        </div>
                    </div>

                    <div id="selected-refs-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                    </div>

                </div>
                
                <div class="card-footer">                    
                    <div id="buttons"></div>
                </div>

            </div>

        </div>
    </section>
</div>

<div id="escolha-tipo-pl" class="modal fade bd-example-modal-xl" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl"role="document" style="width:auto; max-width: 95%;">
    <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">PL Subcontratos</h5>
				<button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-sm-push-12 col-xs-12 col-md-6 col-md-push-6 col-lg-6 col-lg-push-6" >                        
                       
                        <div id="divQtd" class="form-group" style="margin-top: 10px;">
                            <label for="inputQuantidade">Quantidade</label>
                            <input id="qtd" type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Introduza Quantidade">
                        </div>

                        <div id="divLote" class="form-group" style="margin-top: 10px;">
                            <label for="inputLote">Lote</label>
                            <input id="lote" type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Introduza Lote">
                        </div>

                        <div id="divCalibre" class="form-group" style="margin-top: 10px;">
                            <label for="inputCalibre">Calibre</label>
                            <input id="calibre" type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Introduza Calibre">
                        </div>
                        
                    </div>
                    <div class="col-sm-12 col-sm-push-12 col-xs-12 col-md-6 col-md-push-6 col-lg-6 col-lg-push-6" >                                                
                        <label for="inputObs">Observações</label>
                        <div class="input-group">
                            <div id="obs-plps" class="form-group" style="width: inherit;">
                                <textarea id="obs-plp" class="form-control" rows="4" placeholder="Escreva a sua mensagem..." style="resize: none; height: 94%;"></textarea>
                                <div id="count" style="float: right;">
                                    <span id="current_count">0 </span>
                                    <span id="maximum_count">/ 255</span>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>                
			</div>
			<div class="modal-footer">		                            
                <button id="savePLS" onclick="savePLS()" type="button" class="btn btn-success"><i class="fas fa-arrow-right"></i> Continuar</button>	
			</div>    
    </div>
  </div>
</div>

<script>
let user_type=<?php echo $user_type;?>;
let codigoempresa=<?php echo $codigoempresa;?>;
</script>

<script src="<?=base_url();?>js/reload.js"></script>
<script src="<?=base_url();?>js/producao/reescolha_paletes.js"></script>