<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-sm-6">
                    <h1 class="m-0">Plano de Carga <?=$_SESSION['PlanoGG']?></h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-house"></i><a href="<?php echo base_url(); ?>home"> Página Inicial</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-shipping-fast"></i> Planos de Carga</li> 
                        <li class="breadcrumb-item"><i class="fa-solid fa-dolly-flatbed"></i><a href="<?php echo base_url(); ?>load_plans/load_preparation"> Preparação de Cargas</a></li>
                        <li class="breadcrumb-item"><i class="fa-solid fa-file-alt"></i><a href="<?php echo base_url(); ?>load_plans/load_preparation/<?=$_SESSION['SerieGG']?>/<?=$_SESSION['PlanoGG']?>"> <?=$_SESSION['PlanoGG']?></a></li>
                        <li class="breadcrumb-item active"><i class="fa-solid fa-cubes-stacked"></i> <?=$_SESSION['Referencia']?></li>
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
                    <h3 class="card-title">Preparar Referência <?=$_SESSION['Referencia']?></h3>
                    <div class="pull pull-right" style="float: right;">									
                        <button class="btn btn-light btn-sm btn-flat" title="Atualizar Página" onclick="location.reload();" style="margin-right: 10px;float: right">Atualizar Página</button>
					</div>
                </div>
                <div class="card-body">  
                    <div class="row">        
                        <div class="col-sm-12 col-sm-push-6 col-xs-12 col-md-6 col-md-push-6 col-lg-6 col-lg-push-6">
                            <h3 class="m-0">Lotes Afetados</h3>
                            <div id="afetado_encomenda-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                            </div>
                        </div>
                        <div class="col-sm-12 col-sm-push-6 col-xs-12 col-md-6 col-md-push-6 col-lg-6 col-lg-push-6">
                            <h3 class="m-0">Lotes Consumidos</h3>
                            <div id="lotes_consumidos-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h3 class="m-0">Linha da Encomenda</h3>
                        <div id="line-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                        </div>
                    </div>
                    <div class="row">
                        <h3 class="m-0">Paletes a Paletizar</h3>
                        <div id="selected-palets-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                        </div>
                    </div>
                </div>
                
                <div class="card-footer" style="background-color:transparent">      
                    <div class="row">        
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-3 col-md-push-3 col-lg-3 col-lg-push-3">
                            <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                        </div>
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-3 col-md-push-3 col-lg-3 col-lg-push-3">
                            <button onclick="cancel_palets()" type="button" class="btn btn-danger" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Anular Palete</button>
                        </div>
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-3 col-md-push-3 col-lg-3 col-lg-push-3">
                            <button onclick="close_gg()" type="button" class="btn btn-light" style="border-color: lightgrey;width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Concluir Manual.</button>
                        </div> 
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-3 col-md-push-3 col-lg-3 col-lg-push-3">
                            <button onclick="paletizar()" type="button" class="btn btn-primary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Paletizar</button>
                        </div> 
                    </div>                                  
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
                <div id="modal_buttons"></div>
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

<div id="lotes_diff" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Preencha o Motivo</h5>
                    <button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="mts-stk" style="margin-bottom: 15px;">
					    <select id="mt-stk" class="form-control select2-dropdown"  style="width: 100%;" tabindex="-1" aria-hidden="true" required>
						</select>
					</div>

                    <div class="form-group">
                        <textarea id="obs-stk" class="form-control" rows="3" placeholder="Escreva a sua mensagem..."></textarea>
                        <div id="count" style="float: right;">
                            <span id="current_count">0 </span>
                            <span id="maximum_count">/ 255</span>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">		
                    <button onclick="save_palletize()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Gravar</button>                
                </div>    
        </div>
    </div>
</div>

<div id="ver_palete" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl"role="document" style="width:auto">
    <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Palete(s) associada(s)</h5>
				<button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!--<div class="modal-body" style="max-height: calc(100vh - 210px);overflow-y:scroll;overflow-x:scroll;">-->
            <div class="modal-body" style="width: auto; height:auto;">
            <div id="tablePL_assoc" class="table table-striped"></div>
			</div>
			<div class="modal-footer">		                                                
			</div>    
    </div>
  </div>
</div>

<script>
let user_type=<?php echo $user_type;?>;
let codigoempresa=<?php echo $codigoempresa;?>;
let plano='<?=$_SESSION['PlanoGG']?>';
let serie='<?=$_SESSION['SerieGG']?>';
let docEN='<?=$_SESSION["DocEN"]?>';
let linha=<?=$_SESSION["Linha"]?>;
let refp='<?=$_SESSION["Referencia"]?>';
</script>

<script src="<?=base_url();?>js/planos_carga/preparacao/prepara_refs.js"></script>