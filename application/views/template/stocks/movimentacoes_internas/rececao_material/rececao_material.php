<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Mov. Internas (Receção de Material)</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-house"></i><a href="<?php echo base_url(); ?>home"> Página Inicial</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fa-solid fa-boxes-packing"></i> Stocks</li>                        
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-exchange-alt"></i> Mov. Internas</li>
                        <li class="breadcrumb-item active"><i class="nav-icon fas fa-truck-loading"></i> Receção de Material</li>
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


<div id="escolha_palete" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width:auto">
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
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>		
                <button onclick="save_paletes()" type="button" class="btn btn-success"><i class="fas fa-arrow-right"></i> Continuar</button>
			</div>    
    </div>
  </div>
</div>

<div id="escolha_local_fab" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width:auto">
    <div class="modal-content">
            <div class="modal-header">
				<button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!--<div class="modal-body" style="max-height: calc(100vh - 210px);overflow-y:scroll;overflow-x:scroll;">-->
            <div class="modal-body" style="width: auto; height:auto;">
			
            
            <div class="form-group">
            <label for="exampleInputEmail1">Localização</label>
			<div class="input-group">            
            <input type="text" class="col-6 form-control" id="localCB" onchange="pick_local_fabric()" placeholder="Código Barras" autofocus>
            
			<button onclick="clearLocal_fabric()" type="button" class="col-4 btn btn-warning" style="margin-left: 5px;max-height: 38px;"><i class="fas fa-eraser"></i> Limpar</button>	
			
			</div>
            </div>


            <div id="local-table-fabric" class="table table-striped"></div>
			</div>
			<div class="modal-footer">		
                <button id="save_local" onclick="save_local_fabric()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Gravar</button>	
			</div>    
    </div>
  </div>
</div>

<div id="escolha_local_log" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width:auto">
    <div class="modal-content">
            <div class="modal-header">
				<button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!--<div class="modal-body" style="max-height: calc(100vh - 210px);overflow-y:scroll;overflow-x:scroll;">-->
            <div class="modal-body" style="width: auto; height:auto;">
			
            
            <div class="form-group">
            <label for="exampleInputEmail1">Localização</label>
			<div class="input-group">            
            <input type="text" class="col-6 form-control" id="localCB_logistic" onchange="pick_local_logistic()" placeholder="Código Barras" autofocus>
            
			<button onclick="clearLocal_logistic()" type="button" class="col-4 btn btn-warning" style="margin-left: 5px;max-height: 38px;"><i class="fas fa-eraser"></i> Limpar</button>	
			
			</div>
            </div>


            <div id="local-table-logistic" class="table table-striped"></div>
			</div>
			<div class="modal-footer">		
                <button id="save_local_logistic" onclick="save_local_logistic()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Gravar</button>	
			</div>    
    </div>
  </div>
</div>

<div id="escolha_local_arm" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width:auto">
    <div class="modal-content">
            <div class="modal-header">
				<button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!--<div class="modal-body" style="max-height: calc(100vh - 210px);overflow-y:scroll;overflow-x:scroll;">-->
            <div class="modal-body" style="width: auto; height:auto;">
			
            
            <div class="form-group">
            <label for="exampleInputEmail1">Localização</label>
			<div class="input-group">            
            <input type="text" class="col-6 form-control" id="localCB" onchange="pick_local_warehouse()" placeholder="Código Barras" autofocus>
            
			<button onclick="clearLocal_warehouse()" type="button" class="col-4 btn btn-warning" style="margin-left: 5px;max-height: 38px;"><i class="fas fa-eraser"></i> Limpar</button>	
			
			</div>
            </div>


            <div id="local-table-warehouse" class="table table-striped"></div>
			</div>
			<div class="modal-footer">		
                <button id="save_local" onclick="save_local_warehouse()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Gravar</button>	
			</div>    
    </div>
  </div>
</div>

<div id="motivo" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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

<script>
let user_type=<?php echo $user_type;?>;
let codigoempresa=<?php echo $codigoempresa;?>;
</script>

<script src="<?=base_url();?>js/stocks/movimentacoes_internas/rececao_material/rececao_material.js"></script>