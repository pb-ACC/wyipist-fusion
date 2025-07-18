<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Correção de Stock</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-house"></i><a href="<?php echo base_url(); ?>home"> Página Inicial</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-boxes-packing"></i> Stocks</li> 
                        <li class="breadcrumb-item active"><i class="fa-solid fa-edit"></i> Correção de Stock</li>
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
                    <div id="select">
                    </div>
         
                    <br>
         
                    <form id="form-palete">
                        <div class="row g-3">
                          
                        <div class="col-12 col-md-3">
                                <label for="referencia" class="form-label">Referência</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-hash"></i></span>
                                    <input type="text" class="form-control" id="referencia" name="referencia">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="palete" class="form-label">Palete</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                    <input type="text" class="form-control" id="palete" name="palete">
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
                                <label for="lote" class="form-label">Lote</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-123"></i></span>
                                    <input type="text" class="form-control" id="lote" name="lote">
                                </div>
                            </div>

                            <div class="col-12 col-md-3">
                                <label for="calibre" class="form-label">Calibre</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-rulers"></i></span>
                                    <input type="text" class="form-control" id="calibre" name="calibre">
                                </div>
                            </div>
                        
                        </div>
                    </form>
                    

                    <br>

                    <div id="button01"></div>

                    <div id="selected-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                    </div>
                        
                </div>
                        
                <div class="card-footer">                    
                    <div id="button02"></div>
                </div>

            </div>

        </div>
    </section>
</div>


<div id="escolha_palete" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl"role="document" style="width:auto">
    <div class="modal-content">
            <div class="modal-header">
				<button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!--<div class="modal-body" style="max-height: calc(100vh - 210px);overflow-y:scroll;overflow-x:scroll;">-->
            <div class="modal-body" style="width: auto; height:auto;">
			
            
            <div class="form-group">
            <label for="exampleInputEmail1">Palete</label>
			<div class="input-group">            
            <input type="text" class="col-6 form-control" id="paleteCB" onchange="pick_palete()" placeholder="Código Barras" autofocus>
            
			<button onclick="clearPaletes()" type="button" class="col-4 btn btn-warning" style="margin-left: 5px;max-height: 38px;"><i class="fas fa-eraser"></i> Limpar</button>	
			
			</div>
            </div>


            <div id="tablePLs" class="table table-striped"></div>
			</div>
			<div class="modal-footer">
                <div class="row">        	
                    <div class="col-sm-12 col-sm-push-4 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">	                
                    <button onclick="select_all_paletes()" id="select-all" type="button" class="btn btn-light" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;background-color:#E6D9A2;border-color:#D4C38F"><i class="fas fa-clipboard-check"></i> Selecionar</button>		
                    </div>
                    <div class="col-sm-12 col-sm-push-4 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                        <button type="button" class="btn btn-danger" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>		
                    </div>
                    <div class="col-sm-12 col-sm-push-4 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                        <button onclick="save_paletes()" type="button" class="btn btn-success" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"><i class="fas fa-arrow-right"></i> Continuar</button>
                    </div>
                </div>
			</div>    
    </div>
  </div>
</div>

<div id="motivo" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Preencha o Motivo</h5>
                    <button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="mts" style="margin-bottom: 15px;">
					    <select id="mt" class="form-control select2-dropdown"  style="width: 100%;" tabindex="-1" aria-hidden="true" required>
						</select>
					</div>

                    <div class="form-group">
                        <textarea id="obs" class="form-control" rows="3" placeholder="Escreva a sua mensagem..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">		
                    <button onclick="save_motivo()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Gravar</button>                
                </div>    
        </div>
    </div>
</div>

<div id="motivo_anula" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Preencha o Motivo</h5>
                    <button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!--
                    <div id="anls" style="margin-bottom: 15px;">
					    <select id="anl" class="form-control select2-dropdown"  style="width: 100%;" tabindex="-1" aria-hidden="true" required>
						</select>
					</div>
                    -->

                    <div class="form-group">
                        <textarea id="obs-anl" class="form-control" rows="3" placeholder="Escreva a sua mensagem..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">		
                    <button id="save_anulacao" onclick="save_anulacao()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Gravar</button>                
                </div>    
        </div>
    </div>
</div>

<script>
let user_type=<?php echo $user_type;?>;
let codigoempresa=<?php echo $codigoempresa;?>;
</script>
<script src="<?=base_url();?>js/stocks/corrigir_stock/corrigir_stock.js"></script>