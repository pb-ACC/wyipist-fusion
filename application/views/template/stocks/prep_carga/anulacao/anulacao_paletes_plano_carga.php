<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Anulação Preparação de Carga</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home"> Home</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-industry"></i> Stocks</li>                        
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>stocks/anulacao_preparacao_carga"><i class="fas fa-ban"></i> Anulação Preparação de Carga</a></li>    
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>stocks/anulacao_preparacao_carga/<?php echo $_SESSION['PlanoGG'];?>"><?php echo $_SESSION['PlanoGG']; ?></a></li>                    
                        <li class="breadcrumb-item active"> <?php echo $_SESSION['LinhaGG']; ?></li>                    
                        
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
                <h3 class="card-title">Anulação do Plano de Carga <?php echo $_SESSION['PlanoGG']; ?> (Nº Linha <?php echo $_SESSION['LinhaGG']; ?>)</h3>
                </div>

                <div class="card-body">
                    <label>Afetado ao Plano de Carga</label>
                    <div id="lotes-table" class="table table-striped"  style="box-shadow: 5px 10px 18px #888888;">
                    </div>
                    <label style="margin-top: 35px;">Paletes Disponíveis</label>
                    <div id="paletes-table" class="table table-striped"  style="box-shadow: 5px 10px 18px #888888;">
                    </div>
                </div>
                
                <div class="card-footer" style="display: block;">
                    <div class="row">
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4" >
                            <button onclick="open_picking_modal()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                        </div>      
                        <div id="factory" class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4" >
                            <button onclick="grava_anulacao()" type="button" class="btn btn-info" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Confirmar</button>
                        </div>                  
                    </div>	
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

			<div class="modal-body" style="max-height: calc(100vh - 210px);overflow-y:scroll;overflow-x:scroll;">
			
            
            <div class="form-group">
            <label for="exampleInputEmail1">Palete</label>
			<div class="input-group">            
            <input type="text" class="col-6 form-control" id="paleteCB" onchange="pick_palete()" placeholder="Código Barras" autofocus>
            
			<button onclick="clearPaletes()" type="button" class="col-4 btn btn-warning" style="margin-left: 5px;max-height: 38px;"><i class="fas fa-eraser"></i> Limpar</button>	
			
			</div>
            </div>


            <div id="picking-palets-table" class="table table-striped"></div>
			</div>

			<div class="modal-footer">		                
                <!--<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>		-->
                <button onclick="save_paletes()" type="button" class="btn btn-success"><i class="fas fa-arrow-right"></i> Continuar</button>
			</div>    
    </div>
  </div>
</div>

<div id="escolha_local" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width:auto">
    <div class="modal-content">
            <div class="modal-header">
				<button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" style="max-height: calc(100vh - 210px);overflow-y:scroll;overflow-x:scroll;">
			
            
            <div class="form-group">
            <label for="exampleInputEmail1">Localização</label>
			<div class="input-group">            
            <input type="text" class="col-6 form-control" id="localCB" onchange="pick_local()" placeholder="Código Barras" autofocus>
            
			<button onclick="clearLocal()" type="button" class="col-4 btn btn-warning" style="margin-left: 5px;max-height: 38px;"><i class="fas fa-eraser"></i> Limpar</button>	
			
			</div>
            </div>


            <div id="local-table" class="table table-striped"></div>
			</div>
			<div class="modal-footer">		
                <button onclick="save_local()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Confirmar</button>	
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
                    <button onclick="save_motivo()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Confirmar</button>                
                </div>    
        </div>
    </div>
</div>

<script>

    let tableLotes, tablePaletes, tablePicking, tableLocal;
    let palets=[], paletsOG=[], marosca=[], maroscadamarosaca=[];
    let ogLotes, ogPaletes, ogPaletesPicking, count=0, count2=0, k=0;
    let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='';
    let tblInserter=[];

    $(document).ready(function(){

        $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/prep_carga/anulacao/AnulacaoPreparacaoCarga_Picagens/recolha_preparacao_carga",
            dataType: "json",
            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {                    
                    //console.log(data);
                    ogLotes=data['lotes']; 
                    listLotes(data['lotes']);
                    ogPaletes=data['paletes'];
                    dd=[];
                    listPaletes(dd);
                }

            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>standards/Standards_Stocks/localizacaoCB",
            dataType: "json",


            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {

                    //console.log(data);
                    dt = data;
                    listLocal(data);    
                }


            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/prep_carga/anulacao/AnulacaoPreparacaoCarga_Picagens/recolha_preparacao_carga_picking",
            dataType: "json",
            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {                    
                    //console.log(data);
                    ogPaletesPicking=data;
                    picking_palet(data);
                }

            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>standards/Standards_Stocks/motivo_erro_palete",
            dataType: "json",


            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {

                    //console.log(data);                    
                    dropdown_motive(data);
                }

            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });
        
        $('#escolha_palete').on('hidden.bs.modal', function (event) {
           // $("#palets-table").empty();
            $("#paleteCB").val('');
        }); 
        
        $('#escolha_local').on('hidden.bs.modal', function (event) {
            //$("#local-table").empty();
            $("#localCB").val('');
        }); 

        $('#mt').select2();

    });

    function listLotes(data){

        tableLotes= new Tabulator("#lotes-table", {
            //data:data, //assign data to table            
            headerSort:false, //disable header sort for all columns
            placeholder:"No Data Available",
            layout:"fitColumns", //fit columns to width of table (optional)
            index:"Id", //set the index field to the "age" field.
            columns:[
                {title:"Carga", field:"DocGG", align:"center", headerFilter:"input"}, 
                {title:"Palete", field:"DocPL", align:"center", headerFilter:"input"},                  
                {title:"Encomenda", field:"DocEN", align:"center", visible:false},
                {title:"Quantidade", field:"Quantidade", align:"center", headerFilter:"input"},  
                {title:"QtdRepor", field:"QtdRepor", align:"center", visible:false}, 
                {title:"QtdPaletizado", field:"QtdPaletizado", align:"center", visible:false},                 
                {title:"Uni", field:"Unidade", align:"center"},  
                {title:"Sector", field:"Sector", align:"center", headerFilter:"input"}, 
                {title:"Referencia", field:"Referencia", align:"center", headerFilter:"input"}, 
                {title:"Artigo", field:"Artigo", align:"center", headerFilter:"input",visible:false},
                {title:"Descrição Artigo", field:"DescricaoArtigo", align:"center", headerFilter:"input"}, 
                {title:"Lote", field:"Lote", align:"center", headerFilter:"input"}, 
                {title:"Calibre", field:"Calibre", align:"center", headerFilter:"input"},  
                {title:"Local", field:"Local", align:"center", headerFilter:"input"},  
                {title:"Nível", field:"Nivel", align:"center", headerFilter:"input"},  
                {title:"QtdTemp", field:"QtdTemp", align:"center", visible:false},
                {title:"LinhaGG", field:"LinhaGG", align:"center", visible:false},                
                {title:"LinhaEN", field:"LinhaEN", align:"center", visible:false},                
                {title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                
                {title:"Linhazx", field:"Linhazx", align:"center", visible:false},
                {title:"SectorOrigem", field:"SectorOrigem", align:"center", visible:false},
                {title:"LocalOrigem", field:"LocalOrigem", align:"center", visible:false},
                {title:"Editado", field:"Editado", align:"center", visible:false},
                {title:"SectorAntigo", field:"SectorAntigo", align:"center", visible:false},
                {title:"Id", field:"Id", align:"center", visible:false}
            ]
        });

        tableLotes.setData(data);
        tableLotes.redraw(true);

    }
     
    function listPaletes(data){

        $("#paletes-table").empty();

        tablePaletes= new Tabulator("#paletes-table", {
            //data:data, //assign data to table
            rowFormatter:function(row){
                //row - row component        
                qtd = row.getData().QtdRepor;

                if(qtd > 0){
                    row.getElement().style.backgroundColor = "#8fbc8f";
                }
                else{
                    row.getElement().style.backgroundColor = "transparent";
                }
            },            
            headerSort:false, //disable header sort for all columns
            placeholder:"No Data Available",
            layout:"fitColumns", //fit columns to width of table (optional)
            index:"Id", //set the index field to the "age" field.
            columns:[
                {title:"Carga", field:"DocGG", align:"center", headerFilter:"input"}, 
                {title:"Palete", field:"DocPL", align:"center", headerFilter:"input"},                  
                {title:"Encomenda", field:"DocEN", align:"center", visible:false},
                {title:"Quantidade", field:"Quantidade", align:"center", visible:false},
                {title:"QtdRepor", field:"QtdRepor", align:"center", headerFilter:"input"}, 
                {title:"QtdPaletizado", field:"QtdPaletizado", align:"center", visible:false},  
                {title:"Uni", field:"Unidade", align:"center"},  
                {title:"Sector", field:"Sector", align:"center", headerFilter:"input"}, 
                {title:"Referencia", field:"Referencia", align:"center", headerFilter:"input"}, 
                {title:"Artigo", field:"Artigo", align:"center", headerFilter:"input",visible:false},
                {title:"Descrição Artigo", field:"DescricaoArtigo", align:"center", headerFilter:"input"}, 
                {title:"Lote", field:"Lote", align:"center", headerFilter:"input"}, 
                {title:"Calibre", field:"Calibre", align:"center", headerFilter:"input"},  
                {title:"Local", field:"Local", align:"center", headerFilter:"input"},  
                {title:"Nível", field:"Nivel", align:"center", headerFilter:"input"},  
                {title:"QtdTemp", field:"QtdTemp", align:"center", visible:false},
                {title:"LinhaGG", field:"LinhaGG", align:"center", visible:false},                
                {title:"LinhaEN", field:"LinhaEN", align:"center", visible:false},                
                {title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                
                {title:"Linhazx", field:"Linhazx", align:"center", visible:false},                
                {title:"SectorOrigem", field:"SectorOrigem", align:"center", visible:false},
                {title:"LocalOrigem", field:"LocalOrigem", align:"center", visible:false},
                {title:"Editado", field:"Editado", align:"center", visible:false},~
                {title:"SectorAntigo", field:"SectorAntigo", align:"center", visible:false},
                {title:"Id", field:"Id", align:"center", visible:false}
            ]
        });

        //tablePaletes.setData(data);
        tablePaletes.setData(Object.values(data));
        tablePaletes.redraw(true);

    }


    function listLocal(data){

        tableLocal= new Tabulator("#local-table", {
            //data:data, //assign data to table            
            selectable:true,
            headerSort:false, //disable header sort for all columns
            placeholder:"No Data Available",
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
            columns:[
                {title:"#", field:"NumSeq", width:"5%", align:"center",visible:false},
                {title:"Armazém", field:"Armazem", align:"center",visible:true,headerFilter:"input"},
                {title:"Zona", field:"Zona", align:"center",visible:true,headerFilter:"input"},
                {title:"Celula", field:"Celula", align:"center",visible:true,headerFilter:"input"},
                {title:"CodigoBarras", field:"CodigoBarras", align:"center",visible:true,headerFilter:"input"},      
                {title:"Operador", field:"Operador", align:"center", visible:false}
            ]
        });

        tableLocal.setData(data);

        setTimeout(function () {
            tableLocal.redraw();
        }, 100);
    }
    function picking_palet(data){

        $("#picking-palets-table").empty();

        tablePicking = new Tabulator("#picking-palets-table", {
            //data:data, //assign data to table
            rowFormatter:function(row){
                //row - row component        
                qtd = row.getData().QtdRepor;

                if(qtd > 0){
                    row.getElement().style.backgroundColor = "#8fbc8f";
                }
                else{
                    row.getElement().style.backgroundColor = "transparent";
                }
            }, 
            rowClick:function(e, row){  
                count++;              
                row.update({"QtdRepor":row.getData().Quantidade}); //update the row data for field "name"
            }, 
            selectable:true,
            headerSort:false, //disable header sort for all columns
            placeholder:"No Data Available",
            layout:"fitColumns", //fit columns to width of table (optional)
            index:"Id", //set the index field to the "age" field.
            columns:[
                {title:"Carga", field:"DocGG", align:"center", headerFilter:"input"}, 
                {title:"Palete", field:"DocPL", align:"center", headerFilter:"input"},                  
                {title:"Encomenda", field:"DocEN", align:"center", visible:false},
                {title:"Quantidade", field:"Quantidade", align:"center", visible:false},        
                {title:"Qtd. Usar", field:"QtdRepor", align:"center", headerFilter:"input",editor:"input",						
                    cellEdited:function(cell){

                        qtd = cell.getRow().getData().QtdRepor;
                        qtdPL = cell.getRow().getData().Quantidade;

                        
                        if(qtd > 0){
                            cell.getRow().getElement().style.backgroundColor = "#8fbc8f";
                        }
                        else{
                            count--;
                            tablePicking.updateRow(cell.getRow(), {QtdRepor:0});
                            cell.getRow().getElement().style.backgroundColor = "transparent";
                        }
                        

                        if (parseFloat(qtd) > parseFloat(qtdPL)) {
                            toastr["error"]("Está a tentar usar mais quantidade do que aquela que algumas palete têm!");
                            tablePicking.updateRow(cell.getRow(), {QtdRepor:0});
						}

                        clearTBL_lotes();
                    }
                },
                {title:"Uni", field:"Unidade", align:"center"},  
                {title:"Sector", field:"Sector", align:"center", headerFilter:"input"}, 
                {title:"Referencia", field:"Referencia", align:"center", headerFilter:"input"}, 
                {title:"Artigo", field:"Artigo", align:"center", headerFilter:"input",visible:false},
                {title:"Descrição Artigo", field:"DescricaoArtigo", align:"center", headerFilter:"input"}, 
                {title:"Lote", field:"Lote", align:"center", headerFilter:"input"}, 
                {title:"Calibre", field:"Calibre", align:"center", headerFilter:"input"},  
                {title:"Local", field:"Local", align:"center", headerFilter:"input"},  
                {title:"Nível", field:"Nivel", align:"center", headerFilter:"input"},  
                {title:"QtdTemp", field:"QtdTemp", align:"center", visible:false},
                {title:"LinhaGG", field:"LinhaGG", align:"center", visible:false},                
                {title:"LinhaEN", field:"LinhaEN", align:"center", visible:false},                
                {title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                
                {title:"Linhazx", field:"Linhazx", align:"center", visible:false},
                {title:"Editado", field:"Editado", align:"center", visible:false},
                {title:"SectorAntigo", field:"SectorAntigo", align:"center", visible:false},
                {title:"Id", field:"Id", align:"center", visible:false}
            ]
        });

        tablePicking.setData(data);
        tablePicking.redraw(true);

    }

    function open_picking_modal(){

        marosca=[];

        setTimeout(function () {  
            $("#escolha_palete").modal("show");
            //$("#escolha_palete").modal();  
        }, 350);

        setTimeout(function () {            
            $('#paleteCB').trigger('focus');
            tablePicking.redraw(true);            
        }, 850);
    } 

    function pick_palete(){

        let rows = tablePicking.searchRows("DocPL", "=", $("#paleteCB").val());//get row components for all rows with an age greater than 12        

        if(rows.length>0){            
            
            tbl = rows[0].getData();    
            paletsOG=tablePicking.getData();                
            sizeOfTBL=tablePicking.getData();       

            if(count<2){                
            
                for(i=0;i<sizeOfTBL.length;i++){                    
                    if(tablePicking.getData()[i]['DocPL']==tbl['DocPL']){                                                                           
                        tablePicking.setFilter("DocPL",  "=", $("#paleteCB").val());
                        tablePicking.selectRow("active");                         
                        //palets.push(tablePaletes.getSelectedData());                        
                        palets=tablePicking.getSelectedData();                            
                        tablePicking.setData([]);
                        //console.log(paletsOG);
                        for(j=0;j<palets.length;j++){                            
                            //for(k=0;k<palets[j].length;k++){    
                                for(x=0;x<paletsOG.length;x++){
                                    palets[j]['QtdRepor']=palets[j]['Quantidade'];

                                    if(palets[j]['QtdRepor']>0){
                                        count++;
                                    }else{
                                        count--;
                                    }

                                    //if(paletsOG[x]['DocPL']==palets[j][k]['DocPL']){        
                                    if(paletsOG[x]['DocPL']==palets[j]['DocPL']){            
                                        //console.log(palets[j][k]['DocPL']);
                                        //console.log(palets[j]['DocPL']);
                                        //console.log(paletsOG[x]['DocPL']);
                                        paletsOG.splice(x, 1);                                                                                                              
                                        sizeOfTBL.length=sizeOfTBL.length-1;  
                                        x--;                                                            
                                    } 
                                }                                
                           // }       
                                //tablePaletes.setData(palets[j]);                                   
                                tablePicking.setData(palets);                                   
                                tablePicking.selectRow("all");                               
                        }                           
                        tablePicking.addRow(paletsOG, false);                  
                        tablePicking.clearFilter(true);
                        $("#paleteCB").val('');                               
                    }                                               
                }                
            }
            else{
                toastr["error"]("Só pode picar duas paletes de cada vez!");
            }            
        }        
        else{

            toastr["error"]("Não existe nenhuma palete com o código picado!");

            type='error';
            title='Preencha um motivo';
            text2='Escreva a sua mensagem aqui...';
            action='motive';
            xposition='center';


            setTimeout(function () {  
                $("#motivo").modal(); 
            }, 350);

            setTimeout(function () {            
                 //fire_annotation(type,title,text2,action,xposition,campo,valor);
                 $('#obs').trigger('focus');       
            }, 850);
        }

    }

    function clearPaletes(){
        
        $("#picking-palets-table").empty();        

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/prep_carga/anulacao/AnulacaoPreparacaoCarga_Picagens/recolha_preparacao_carga",
            dataType: "json",
            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {                    
                    ogPaletes=data['paletes'];
                    listPaletes(data['paletes']);                    
                }

            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });    

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/prep_carga/anulacao/AnulacaoPreparacaoCarga_Picagens/recolha_preparacao_carga_picking",
            dataType: "json",
            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {                    
                    //console.log(data);
                    ogPaletesPicking=data;
                    picking_palet(data);
                }

            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });


        clearTBL_lotes();

        count=0;
        palets=[];
        marosca=[];
        $('#paleteCB').trigger('focus');

    }

    function save_paletes(){

        marosca=[];

        sizeOfTBL = tablePicking.getData();

        for(i=0;i<sizeOfTBL.length;i++){   
            if(tablePicking.getData()[i]['QtdRepor'] > 0){

                //tablePicking.updateRow(i,{Sector: tablePicking.getData()[i]['SectorOrigem'] }); //update data          
                //tablePicking.updateRow(i,{Local: tablePicking.getData()[i]['LocalOrigem'] }); //update data  
                tablePicking.updateRow(i,{SectorAntigo: tablePicking.getData()[i]['Sector'] }); //update data 
                if((tablePicking.getData()[i]['SectorOrigem']).match('FB')){
                    tablePicking.updateRow(i,{Sector: 'FB998' }); //update data          
                }else{
                    tablePicking.updateRow(i,{Sector: 'CL998' }); //update data          
                }               
                       
                tablePicking.updateRow(i,{Local: '' }); //update data   

                /*type='success';
                title='Deseja assumir a localização por defeito?';
                text2='Sector: ' +  tablePicking.getData()[i]['SectorOrigem']  +' Local: ' +  tablePicking.getData()[i]['LocalOrigem'] ;
                action='validaLocal';
                xposition='center';
                campo='';
                valor='';
                
                fire_annotation(type,title,text2,action,xposition,campo,valor); */

                marosca.push(tablePicking.getData()[i]);
            } 

        } 

        console.log(marosca.length);
        console.log(marosca);

        if(marosca.length>0){
            if(marosca.length<=2){            
            //$("#selected-palets-table").empty();
            //selectedPalets(tablePaletes.getSelectedData());  

            listPaletes(marosca);            
            $('#escolha_palete').modal('hide');

            }
            else{
                toastr["error"]("Só pode picar duas paletesde cada vez!");
            }   
        }else{
                type='success';
                title='Não picou nenhuma Palete';
                text2='Tem a certeza que pretende continuar?';
                action='confirmPK';
                xposition='center';
                campo='';
                valor='';
            
                fire_annotation(type,title,text2,action,xposition,campo,valor);            
        }


    }

    function validate_qtd_EN(){

        sizeOfTBL01=tablePaletes.getData(); 
        sizeOfTBL02=tableLotes.getData(); 
        
        console.log(tableLotes.getData());

        maroscadamarosaca=[];

        if(marosca.length > 0){
            for(i=0;i<sizeOfTBL01.length;i++){       
                for(j=0;j<sizeOfTBL02.length;j++){                              
                    if(tablePaletes.getData()[i]['QtdRepor']<=tableLotes.getData()[j]['Quantidade']){        
                        
                        //tableLotes.updateRow(j,{QtdPaletizado: parseFloat(tableLotes.getData()[j]['QtdTemp'])} ); //update data                    
                        
                        tableLotes.updateRow(j,{QtdPaletizado: ( ( parseFloat(tableLotes.getData()[j]['QtdTemp'])) + (parseFloat(tablePaletes.getData()[i]['QtdRepor']) )  )}); //update data                                                
                                   
                    }else{
                        maroscadamarosaca.push(tableLotes.getData()[j]);
                    }          
                }
            }
        }else{
            clearTBL_lotes();
        }


        console.log(tableLotes.getData());
        if(maroscadamarosaca.length>0){
            toastr["error"]("Está a tentar repor mais quantidade do que a palete tem!");
                        
            $("#lotes-table").empty();                    
            listLotes(ogLotes);

            $("#paletes-table").empty();                    
            listPaletes(ogPaletes);

            open_picking_modal();
            
        }else{
            $("#paletes-table").empty();                    
            listPaletes(tablePaletes.getData());
        }
    }

    function dropdown_motive(data){
        
        for(i=0;i<data.length;i++) {
            let value = {
                id: data[i]['Codigo'],
                text: data[i]['Descricao']
            };

            let tmp = '<option value="'+value['id']+'">'+
                        value['text']+
				      '</option>';
		    let html = $.tmpl(tmp,value);
		    $("#mt").append(html);
        }

    }


    function save_motivo(){
        
        mtt = $("#mt option:selected").text();
        mt = $.trim(mtt);
        //grava na zx a criar id,palete,mt,obs,mt_val,username,func_gpac,nome,datetime

        //alert($("#radioButtons input[type='radio']:checked").val() );
        let text = $("#paleteCB").val();

        if(text.match("PL")){

            if($("input[type='radio']:checked").val() == 1){
                sectorDestino='FB991';
            }else{
                sectorDestino='CL991';
            }
       
            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>standards/Standards_Stocks/save_motivo_and_movstock",
                dataType: "json",
                data:{
                    motivo: mt,
                    codigomotivo: $("#mt").val(),
                    palete: $("#paleteCB").val(),
                    sectorDestino: sectorDestino,
                    obs: document.getElementById('obs').value
                },
                success: function (data) {
                    //alert(data);
                    //console.log(data);

                    if (data === "kick") {
                        //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        window.location = "home/logout";
                    } else {
                        toastr["success"]("Dados gravados com Sucesso");
                        
                        obs=document.getElementById('obs').value='';
                        $("#mt").val('');                        
                        $('#motivo').modal('hide');
                        $("#paleteCB").val('');
                        $("#paleteCB").focus();
                        

                    }
                },
                error: function (e) {
                    //alert(e);
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }

            });

        }else{

            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>standards/Standards_Stocks/save_motivo",
                dataType: "json",
                data:{
                    motivo: mt,
                    codigomotivo: $("#mt").val(),
                    palete: $("#paleteCB").val(),
                    obs: document.getElementById('obs').value
                },
                success: function (data) {
                    //alert(data);
                    //console.log(data);

                    if (data === "kick") {
                        //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        window.location = "home/logout";
                    } else {
                        toastr["success"]("Dados gravados com Sucesso");
                        
                        obs=document.getElementById('obs').value='';
                        $("#mt").val('');                        
                        $('#motivo').modal('hide');
                        $("#paleteCB").val('');
                        $("#paleteCB").focus();
                        

                    }
                },
                error: function (e) {
                    //alert(e);
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }

            });

        }
        //clear da text box

    }

    function grava_anulacao(){

        sel = tablePaletes.getData();
        for(w = 0; w < sel.length; w++){
            if(tablePaletes.getData()[w]['QtdRepor'] > 0){
                tblInserter.push(tablePaletes.getData()[w]);                
            }
        }
               
        $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>stocks/prep_carga/anulacao/AnulacaoPreparacaoCarga_Grava/valida_documentos",
                dataType: "json",
                data:{
                    tbl: tblInserter
                },
                success: function (data) {
                    //alert(data);
                    //console.log(data);

                    if (data === "kick") {
                        //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        window.location = "home/logout";
                    } else {
                        console.log(data);

                        if(data.length === 0){
                            apos_validar_PL_docs(tblInserter);
                        }else{
                            toastr["error"]("Existem PLs que pretende anular prepração que já estão associadas a documentos!<br>Impossivel continuar!<br>Contacte gestor comercial da carga!");
                        }
                    }
                },
                error: function (e) {
                        toastr["error"]("Erro ao validar! Contacte o responsável!");
                }

            });

        }
    
    function apos_validar_PL_docs(tblInserter){                    
            
        $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>stocks/prep_carga/anulacao/AnulacaoPreparacaoCarga_Grava/grava_anulacao_preparacao_carga",
                dataType: "json",
                data:{
                    tbl: tblInserter
                },
                success: function (data) {
                    //alert(data);
                    //console.log(data);

                    if (data === "kick") {
                        //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        window.location = "home/logout";
                    } else {
                        toastr["success"]("Dados gravados com Sucesso");
                        setTimeout(function(){
                            location.reload();
                        },2500);
                    }
                },
                error: function (e) {
                        toastr["error"]("Erro ao gravar!");
                }

            });
                   
    }

    function clearTBL_lotes(){
        sizeOfTBL02=tableLotes.getData();
        for(k=0;k<sizeOfTBL02.length;k++){   
                if(parseFloat(tableLotes.getData()[k]['QtdPaletizado']) == 0){
                    tableLotes.updateRow(k,{QtdPaletizado: 0}); //update data                                                      
                }                                           
        }
    }

    function pick_local(){

        let rows = tableLocal.searchRows("CodigoBarras", "=", $("#localCB").val());//get row components for all rows with an age greater than 12

        if(rows.length > 0){

            tbl = rows[0].getData();     
            sizeOfTBL=tableLocal.getData();  
            console.log(sizeOfTBL);

            if(count2<1){
                for(i=0;i<sizeOfTBL.length;i++){
                    if(tableLocal.getData()[i]['CodigoBarras']==tbl['CodigoBarras']){                    
                        
                        tableLocal.setFilter("CodigoBarras",  "=", $("#localCB").val());
                        tableLocal.selectRow("active"); 
                        tableLocal.clearFilter(true);
                        $("#localCB").val('');
                    }                        
                }
                
                count2++;
                console.log(count2);   
            }
            else{
                toastr["error"]("Só pode picar um local!");
            }    
        }        
        else{
                toastr["error"]("Localização inexistente!");
                $("#localCB").val('');
                $('#localCB').trigger('focus');                  
            }         

    }

    function clearLocal(){
        tableLocal.deselectRow();
        count2=0;
    }

    function save_local(){
      
      sel = tableLocal.getSelectedData();

      sizeOfTBL02=tablePaletes.getData(); 
   
      for(j=0;j<sizeOfTBL02.length;j++){                              
            if(tablePaletes.getData()[j]['QtdRepor'] > 0 && tablePaletes.getData()[j]['Editado'] == 0){  
                tablePaletes.updateRow(j,{Local: tableLocal.getSelectedData()[0]['CodigoBarras']}); //update data  
                tablePaletes.updateRow(j,{Sector: tableLocal.getSelectedData()[0]['Armazem']}); //update data     
                
                tablePaletes.updateRow(j,{Editado: 1}); //update data  
            }
        }    

        $('#escolha_local').modal('hide');        
    }

    function fire_annotation(type,title,text2,action,xposition,campo,valor){

        if(action==='motive') {

            Swal.fire({
                    title: title,
                    input: 'textarea',
                    inputPlaceholder: text2,
                    inputAttributes: {
                        'aria-label': text2
                    },
                    showCancelButton: true
                    });     
        }

        if(action==='confirmPL') {
			Swal.fire({
					icon: type,
					iconHtml: '?',
					iconColor: '#f8bb86',
					title: title,
					html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 20px;color: #333">'+text2+'</p>',
					showDenyButton: true,
					confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
					denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
				}
			).then((result) => {


				if (result.isConfirmed) {
                    
                    $('.form-check-input').prop('checked', false); // Unchecks it    
                    $(valor).prop('checked', true); // Checks it              

                    changeSector(campo);

                    if($(valor).val() === '1'){
                       sectorDestino='FB992';
                    }else{
                        sectorDestino='CL992';
                    }

				} else if (result.isDenied) {
                    $(valor).prop('checked', false); // Checks it   
                    if($(valor).val() === '1'){
                        sectorDestino='FB992';
                    }else{
                        sectorDestino='CL992';
                    }

				}
			})
        }

        if(action==='confirmCP') {
			Swal.fire({
					icon: type,
					iconHtml: '?',
					iconColor: '#f8bb86',
					title: title,
					//html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
					showDenyButton: true,
					confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
					denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
				}
			).then((result) => {


				if (result.isConfirmed) {
                    
                    confirm_confirma_partidas();

				} else if (result.isDenied) {
				
				}
			})
        }

        if(action==='confirmPK') {
			Swal.fire({
                icon: type,
					iconHtml: '?',
					iconColor: '#f8bb86',
					title: title,
					html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 20px;color: #333">'+text2+'</p>',
					showDenyButton: true,
					confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
					denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
				}
			).then((result) => {


				if (result.isConfirmed) {
                    
                    $('#escolha_palete').modal('hide');
                    validate_qtd_EN();

				} else if (result.isDenied) {
				
				}
			})
        }

        
        if(action==='validaLocal') {
			Swal.fire({
                icon: type,
					iconHtml: '?',
					iconColor: '#f8bb86',
					title: title,
					html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 20px;color: #333">'+text2+'</p>',
					showDenyButton: true,
					confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
					denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
				}
			).then((result) => {


				if (result.isConfirmed) {
                
                    sel = tableLocal.getSelectedData();
                    sizeOfTBL02=tableLotes.getData(); 
   
                    for(j=0;j<sizeOfTBL02.length;j++){                              
                        if(tablePaletes.getData()[j]['QtdRepor'] > 0 && tablePaletes.getData[j]['Editado'] == 0){  
                            tablePaletes.updateRow(j,{Editado: 1}); //update data  
                        }
                    }    

				} else if (result.isDenied) {
				
                    setTimeout(function () {  
                        $("#escolha_local").modal("show");
                        //$("#escolha_palete").modal();  
                    }, 350);

                    setTimeout(function () {            
                        $('#localCB').trigger('focus');
                        tableLocal.redraw(true);            
                    }, 850);

				}
			})
        }

    }

</script>