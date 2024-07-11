<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gerar Palete via PHC</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home"> Home</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-industry"></i> Stocks</li>                        
                        <li class="breadcrumb-item active"><i class="fas fa-cubes"></i> Gerar Palete via PHC</li>
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
                        <div>
                            <div id="consulta">
                                <div class="input-group">									
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-pallet"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control col-lg-8 col-xs-12" id="barCode" onchange="pick_barCode()" placeholder="Código Barras" autofocus>
                                </div>

                                <div id="reference-table" class="table table-striped"  style="margin-top:15px; box-shadow: 15px 10px 18px #888888;">
                                </div>                
                            </div>                           
                        </div>    

                    </div>
                    <div class="card-footer" style="display: block;">
                    <div class="row">
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4" >
                            <!--<button onclick="escolha_tipo_pl()" type="button" class="btn btn-info" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Confirmar</button>-->
                        </div>
                    </div>	
                </div>
                </div>
        </div>
    </section>
</div>

<div id="escolha-tipo-pl" class="modal fade bd-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width:auto; max-width: 95%;">
    <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Escolha Tipo PL</h5>
				<button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-sm-push-12 col-xs-12 col-md-6 col-md-push-6 col-lg-6 col-lg-push-6" >                        
                        <label for="inputArmazem">Armazém Destino</label>
                        <div class="input-group">
                            <div id="arms" style="width: inherit;">
                                <select id="arm" class="form-control select2-dropdown"  style="width: 100%;" tabindex="-1" aria-hidden="true" required>									
								</select>
                            </div>
                        </div>
                       
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

                        <label for="inputNivel">Nível Palete</label>
                        <div class="input-group">
                            <div id="lvls" style="width: inherit;">
                                <select id="lvl" class="form-control select2-dropdown"  style="width: 100%;" tabindex="-1" aria-hidden="true" required>									
								</select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-sm-12 col-sm-push-12 col-xs-12 col-md-6 col-md-push-6 col-lg-6 col-lg-push-6" >
                        <label for="inputArmazem">Stock PHC</label>
                        <div id="stock-phc-table" class="table table-striped"></div>
                        
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
                <button id="defineLocal" onclick="go_to_defineLocal()" type="button" class="btn btn-primary"><i class="fas fa-map-marker-alt"></i>  Definir Localização</button>	
                <button id="savePalete" onclick="go_to_save_PNC()" type="button" class="btn btn-success" style="display: none;"><i class="fas fa-save"></i> Confirmar</button>	
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
                <button id="save_plp_creation" onclick="save_plp_creation()" type="button" class="btn btn-success"><i class="fas fa-save"></i> Confirmar</button>	
			</div>    
    </div>
  </div>
</div>

<div id="motivo_pnc" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Preencha o Motivo</h5>
                    <button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div id="pncs" style="margin-bottom: 15px;">
					    <select id="pnc" class="form-control select2-dropdown"  style="width: 100%;" tabindex="-1" aria-hidden="true" required>
						</select>
					</div>

                    <div class="form-group">
                        <textarea id="obs-pnc" class="form-control" rows="3" placeholder="Escreva a sua mensagem..."></textarea>
                    </div>
                </div>
                
                <div class="modal-footer">		
                    <button onclick="indica_motivo()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Confirmar</button>                
                </div>    
        </div>
    </div>
</div>

<script>

    let tableReferences, tableStock, tableSelPaletes, tableLocal,  tableLocal_logistic, selectedData=[], OG, dt, count=0, count2=0, count3=0, local='';
    let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='';
    let palets=[], paletsLin=[], paletsOG=[], marosca=[];
    let phcSector='', qtdStock=0, tipoecra='';
    let gblote, gbcalibre, gbqtd, gbnome_arm, gbcodigo_arm, gbnome_lvl, gbcodigo_lvl;
    let gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras;


    $('#obs-plp').keyup(function() {    
        var characterCount = $(this).val().length,
        current_count = $('#current_count'),
        maximum_count = $('#maximum_count'),
        count = $('#count');    
        current_count.text(characterCount);        
    });

    $(document).ready(function(){

        $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

        toastr.clear();
        toastr["info"]("A carregar referências...");        

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/GeraPaletePHC/listReferences",
            dataType: "json",


            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {

                    console.log(data);
                    OG=data;
                    listReferences(data);

                    toastr.clear();
                    toastr["success"]("Referências carregadas com sucesso.");
                    toastr.clear();                    

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
            url: "<?php echo base_url();?>standards/Dropdowns/list_armazens",
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
                    dropdown_armazens(data);
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
            url: "<?php echo base_url();?>standards/Dropdowns/list_paletslevels",
            dataType: "json",


            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {

                    console.log(data);                    
                    dropdown_paletslevels(data);
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
            url: "<?php echo base_url();?>standards/Standards_Stocks/motivo_pnc",
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
                    dropdown_motive_pnc(data);
                }

            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });
           
        $('#pnc').select2(); 
        $('#arm').select2();

        $("#arm").on("select2:close", function () {

            check=$("#arm").val();    
            if(check==='FB003' || check==='CL001'){                
                $("#defineLocal").show();
                $("#savePalete").hide();
            }
            else if(check==='FB002' || check==='CL002'){                
                //else if(check==='CL001'){                
                $("#defineLocal").hide();
                $("#savePalete").show();
            }
        });

        $('#escolha-tipo-pl').on('hidden.bs.modal', function (e) {
            tableReferences.deselectRow();
            $("#qtd").val('');                   
            $("#lote").val('');  
            $("#calibre").val('');  
            palets=[];
            paletsLin=[];
        });

        $('#lvl').select2();

        $("#barCode").focus();
        
    });


    function listReferences(data){
    
        $("#reference-table").empty();

            tableReferences= new Tabulator("#reference-table", {
                //data:data, //assign data to table            
                rowClick:function(e, row){				    
                    //row.toggleSelect();
                    escolha_tipo_pl(row.getData().Referencia,row.getData().Descricao,row.getData().CodigoFormato,row.getData().CodigoBarras,row.getData().TipoEcra);
			    },  
                selectable:true,
                headerSort:false, //disable header sort for all columns
                placeholder:"No Data Available",
                pagination:"local",
                    paginationSize:25,
                    paginationSizeSelector:[25,50,75,100],
                layout:"fitColumns", //fit columns to width of table (optional)
                columns:[
                    {title:"Referência", field:"Referencia", align:"center",headerFilter:"input"},
                    {title:"Descrição", field:"Descricao", align:"center",headerFilter:"input"},
                    {title:"Formato", field:"Formato", align:"center",headerFilter:"input"},
                    {title:"Cód. Barras", field:"CodigoBarras", align:"center",headerFilter:"input"},
                    {title:"Artigo", field:"Artigo", align:"center", visible:false},      
                    {title:"CodigoFormato", field:"CodigoFormato", align:"center", visible:false},                                    
                    {title:"Ordem", field:"Ordem", align:"center", visible:false},                
                    {title:"Operador", field:"Operador", align:"center", visible:false},     
                    {title:"TipoEcra", field:"TipoEcra", align:"center", visible:false}
                ]
            });

            tableReferences.setData(data);
            tableReferences.redraw(true);
    }

    function dropdown_armazens(data){
        
        for(i=0;i<data.length;i++) {
            let value = {
                id: data[i]['Codigo'],
                text: data[i]['Descricao']
            };

            let tmp = '<option value="'+value['id']+'">'+
                        value['text']+
				      '</option>';
		    let html = $.tmpl(tmp,value);
		    $("#arm").append(html);
        }

    }

    function dropdown_motive_pnc(data){
        
        for(i=0;i<data.length;i++) {
            let value = {
                id: data[i]['Codigo'],
                text: data[i]['Descricao']
            };

            let tmp = '<option value="'+value['id']+'">'+
                        value['text']+
				      '</option>';
		    let html = $.tmpl(tmp,value);
		    $("#pnc").append(html);
        }

    }

    function dropdown_paletslevels(data){
        for(i=0;i<data.length;i++) {
            let value = {
                id: data[i]['Codigo'],
                //text: data[i]['Descricao']
                text: data[i]['Tipo']
            };

            let tmp = '<option value="'+value['id']+'">'+
                        value['text']+
				      '</option>';
		    let html = $.tmpl(tmp,value);
		    $("#lvl").append(html);
        }
    }

    function pick_barCode(){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/GeraPaletePHC/listReferences_filter",
            dataType: "json",
            data: {
              cb : $("#barCode").val()
            },
            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {

                    //console.log(data);                   
                    listReferences(data);
                }
            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });
    }


    function escolha_tipo_pl(Referencia,Descricao,CodigoFormato,CodigoBarras,Ecra){
        
        document.getElementById('obs-plp').value='';
        $('#current_count').text(0);
       // sizeofTBL=tableReferences.getSelectedData();   
       tipoecra=Ecra;
       gbreferencia=Referencia;
       gbdescricao=Descricao;
       gbcodigoformato=CodigoFormato;
       gbcodigobarras=CodigoBarras;

        if(Ecra=='A'){
            $("#divCalibre").show();
            $("#divLote").show();
            $("#divQtd").show();
        }
        else if(Ecra=='B'){            
            $("#divCalibre").hide();
            $("#divLote").show();
            $("#divQtd").show();
        }
        else if(Ecra=='C'){
            $("#divCalibre").hide();
            $("#divLote").hide();
            $("#divQtd").show();
        }
       
       // if(sizeofTBL.length>0){

            cb = tableReferences.getSelectedData()[0]['Referencia'];
            newTitle = 'Escolha Tipo PL ('+ Referencia +' - '+Descricao+')';
            $(".modal-title").text(newTitle);

            setTimeout(function () {  
                $("#escolha-tipo-pl").modal("show");               
            }, 350);

            //input qtd (preenchida por default)
            recolhe_qtd(CodigoFormato);
            
            //table stock
            recolhe_stock_referencia(tipoecra,Referencia);

            setTimeout(function () {                                                   
                //input lote
                //$("#lote").focus(); 
                tableStock.redraw(true);                           
            }, 850);

      //  }
        //        else{
        //          toastr["error"]("Não foi selecionado(a) nenhum(a) Código Barras ou Referência!");
            //    }
    }

    function recolhe_qtd(CodigoFormato){

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/GeraPaletePHC/getQtd_Referencia",
            dataType: "json",    
            data:{
                formato: CodigoFormato
            },
            success: function (data) {
                //alert(data);                
                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {

                    if(data.length>0){
                        $("#qtd").val(data[0]['Quantidade']);                   
                    }

                }
            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });
    }

    function recolhe_stock_referencia(tipoecra,Referencia){

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/GeraPaletePHC/getStock_Referencia",
            dataType: "json",    
            data:{
                referencia: Referencia
            },
            success: function (data) {
                //alert(data);                
                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {
                    stockPHC(tipoecra,data);
                }
            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });

    }

    function stockPHC(tipoecra,data){
    
        $("#stock-phc-table").empty();

        tableStock= new Tabulator("#stock-phc-table", {
                    //data:data, //assign data to table            
                    rowClick:function(e, row){				    
                        //row.toggleSelect();
                        tableStock.deselectRow();
                        row.select();
                        update_LoteCalibreQtd(row.getData().Lote,row.getData().Calibre,row.getData().Quantidade,row.getData().Sector,tipoecra);
                    },  
                selectable:true,
                headerSort:false, //disable header sort for all columns
                placeholder:"No Data Available",
                pagination:"local",
                    paginationSize:25,
                    paginationSizeSelector:[25,50,75,100],
                layout:"fitColumns", //fit columns to width of table (optional)
                columns:[
                    {title:"Lote", field:"Lote", align:"center"},
                    {title:"Calibre", field:"Calibre", align:"center"},
                    {title:"Quantidade", field:"Quantidade", align:"center"},
                    {title:"Sector", field:"Sector", align:"center", visible:false},
                    {title:"Id", field:"Id", align:"center", visible:false}
                ]
        });

        tableStock.setData(data);
        tableStock.redraw(true);
    }

    function update_LoteCalibreQtd(Lote,Calibre,Quantidade,Sector,tipoecra){
        
        if(tipoecra=='A'){
            $("#lote").val(Lote);
            $("#calibre").val(Calibre);
        }
        else if(tipoecra=='B'){            
            $("#calibre").val('');
            $("#lote").val(Lote);
        }
        else if(tipoecra=='C'){
            $("#lote").val('');
            $("#calibre").val('');
        }
        //$("#qtd").val(Quantidade);
        phcSector=Sector;
        qtdStock=Quantidade;
    }

    function go_to_defineLocal(){
        
        sizeofText = document.getElementById('obs-plp').value;

        if(sizeofText.length<=255){
            armm = $("#arm option:selected").text();
            arm = $.trim(armm);

            lvll = $("#lvl option:selected").text();
            lvl = $.trim(lvll);

            if(tipoecra=='A'){
                if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                    if($("#lote").val() == '') toastr["error"]("Tem de indicar o Lote!"); 
                        if($("#calibre").val() == '') toastr["error"]("Tem de indicar o Calibre!"); 
                
                            else create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val(),armm,$("#arm").val(),lvl,$("#lvl").val(),phcSector,qtdStock);   
            }
            else if(tipoecra=='B'){            
                if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                    if($("#lote").val() == '') toastr["error"]("Tem de indicar o Lote!"); 

                        else create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val(),armm,$("#arm").val(),lvl,$("#lvl").val(),phcSector,qtdStock);   
            }
            else if(tipoecra=='C'){
                if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                    
                    else create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val(),armm,$("#arm").val(),lvl,$("#lvl").val(),phcSector,qtdStock);   
            }
        }else{
              toastr["error"]("Só pode escrever até 255 caracteres!");
         }   

    }
    
    function create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,lote,calibre,qtd,nome_arm,codigo_arm,nome_lvl,codigo_lvl,phcSector,qtdStock){
        gblote=lote;
        gbcalibre=calibre;
        gbqtd=qtd;
        gbnome_arm=nome_arm;
        gbcodigo_arm=codigo_arm;
        gbnome_lvl=nome_lvl;
        gbcodigo_lvl=codigo_lvl;
        qtdSup=(qtdStock-qtd)*-1;
        qtdSupPos=qtdSup*-1;

        if(qtdStock-qtd<0){

            

            type='success';
            title='Vai criar uma PL cuja quantidade ultrapassa em '+ qtdSup + 'M2 o existente no stock PHC.';
            text2='Confirma?';
            action='confirmRefPL';
            xposition='center';
            campo='';
            valor=qtdSup;

            fire_annotation(type,title,text2,action,xposition,campo,valor);

        }
        else{
            palets.push({referencia:gbreferencia, descricao:gbdescricao, formato:gbcodigoformato, cb:gbcodigobarras, lote:lote, calibre:calibre, 
                         qtd:qtd, nome_arm:nome_arm, codigo_arm:codigo_arm, phcSector:phcSector, qtdPHC:qtd, qtdSup: (qtd-qtdStock), phc: 0,
                         nome_nivel:gbnome_lvl, codigo_nivel:gbcodigo_lvl});
            chooseLocal();
        }                
    }


    function chooseLocal(){

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/GeraPaletePHC/localizacaoCB",
            dataType: "json",
            data:{
                sector: gbcodigo_arm
            },
            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {

                    //console.log(data);  
                    $("#defineLocal").prop("disabled",true); 
                    $("#escolha_local").modal("show");                                             
                    listLocal(data);    
                }


            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });
       
    }

    function listLocal(data){

        $("#local-table").empty();

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
                {title:"Zona", field:"Zona", align:"center",visible:true,headerFilter:"input"},
                {title:"Celula", field:"Celula", align:"center",visible:true,headerFilter:"input"},
                {title:"CodigoBarras", field:"CodigoBarras", align:"center",visible:true,headerFilter:"input"}
            ]
        });

        tableLocal.setData(data);

        setTimeout(function () {
            tableLocal.redraw();
        }, 100);
    }

    function pick_local(){

        let rows = tableLocal.searchRows("CodigoBarras", "=", $("#localCB").val());//get row components for all rows with an age greater than 12

        if(rows.length > 0){
            sizeOfTBL=tableLocal.getData();  
            tbl = rows[0].getData();    
            
            if(count2<1){                        
            for(i=0;i<sizeOfTBL.length;i++){                 
                if(tableLocal.getData()[i]['CodigoBarras']==tbl['CodigoBarras']){                                        
                    tableLocal.setFilter("CodigoBarras",  "=", $("#localCB").val());
                    tableLocal.selectRow("active"); 
                    tableLocal.clearFilter(true);
                    $("#localCB").val('');
                }                       
            }
                
            // count2++;
                //console.log(count2);   
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

    function save_plp_creation(){
      
        sel1 = tableLocal.getSelectedData();

        if(sel1.length<=1){

                $("#save_plp_creation").prop("disabled",true); 

                sel2 = tableStock.getSelectedData();

                if(sel2.length>=1){               
                    
                    palets.forEach(v => {
                        //Test if projectname  == parameter1. If it is update status                
                        if ((v.calibre == tableStock.getSelectedData()[0]['Calibre']) && (v.lote == tableStock.getSelectedData()[0]['Lote']) ) v.phc = 1;
                        v.qtdPHC=tableStock.getSelectedData()[0]['Quantidade'];
                    });
                
                }
            
            //console.log(palets);
            //console.log(sel1);

                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>stocks/GeraPaletePHC/grava_criacao_paletes_plp",
                    dataType: "json",
                    data:{
                        tbl: palets,
                        local : tableLocal.getSelectedData()[0]['CodigoBarras'],
                        obsPL: document.getElementById('obs-plp').value
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
                            going_to_print();

                            setTimeout(function(){
                                location.reload();
                            },2500);                        

                        }
                    },
                    error: function (e) {
                        //alert(e);
                        //alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                        //console.log(e);
                        $("#save_plp_creation").prop("disabled",false); 
                        $("#defineLocal").prop("disabled",false); 
                        toastr["error"]("Erro na gravação dos dados! Tente novamente!");
                    }

                });    
            }
            else{
                  toastr["error"]("Só pode picar um local de cada vez!");
            }        
    }

    function go_to_save_PNC(){
        armm = $("#arm option:selected").text();
        arm = $.trim(armm);

        sizeofText = document.getElementById('obs-plp').value;

        if(sizeofText.length<=255){

            if(tipoecra=='A'){
                if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                    if($("#lote").val() == '') toastr["error"]("Tem de indicar o Lote!"); 
                        if($("#calibre").val() == '') toastr["error"]("Tem de indicar o Calibre!"); 
                
                            else create_array_PNC(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val(),armm,$("#arm").val(),phcSector,qtdStock);   
            }
            else if(tipoecra=='B'){            
                if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                    if($("#lote").val() == '') toastr["error"]("Tem de indicar o Lote!"); 

                        else create_array_PNC(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val(),armm,$("#arm").val(),phcSector,qtdStock);   
            }
            else if(tipoecra=='C'){
                if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                    
                    else create_array_PNC(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val(),armm,$("#arm").val(),phcSector,qtdStock);   
            }
        }else{
              toastr["error"]("Só pode escrever até 255 caracteres!");
         }   
    }

    function create_array_PNC(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,lote,calibre,qtd,nome_arm,codigo_arm,phcSector,qtdStock){
        gblote=lote;
        gbcalibre=calibre;
        gbqtd=qtd;
        gbnome_arm=nome_arm;
        gbcodigo_arm=codigo_arm;

        qtdSup=(qtdStock-qtd)*-1;
        qtdSupPos=qtdSup*-1;

        if(qtdStock-qtd<0){

            type='success';
            title='Vai criar uma PL cuja quantidade ultrapassa em '+ qtdSup + 'M2 o existente no stock PHC.';
            text2='Confirma?';
            action='confirmRefPNC';
            xposition='center';
            campo='';
            valor=qtdSup;

            fire_annotation(type,title,text2,action,xposition,campo,valor);

        }
        else{
            palets.push({referencia:gbreferencia, descricao:gbdescricao, formato:gbcodigoformato, cb:gbcodigobarras, lote:lote, calibre:calibre, 
                         qtd:qtd, nome_arm:nome_arm, codigo_arm:codigo_arm, phcSector:phcSector, qtdPHC:qtd, qtdSup: (qtd-qtdStock), phc: 0, nome_nivel:gbnome_lvl, 
                         codigo_nivel:gbcodigo_lvl});    
            save_pnc_creation();
        }                
    }

    function save_pnc_creation(){

        type='success';
        title='Tem a certeza que pretende continuar?';
        text2='A palete será enviada para - PNC';
        action='confirmFPNC';
        xposition='center';

        fire_annotation(type,title,text2,action,xposition,campo,valor);     

    }

    function confirm_fabrica_to_pnc(){

        setTimeout(function () {  
            $("#motivo_pnc").modal(); 
        }, 350);

        setTimeout(function () {            
            //fire_annotation(type,title,text2,action,xposition,campo,valor);
            $('#obs-pnc').trigger('focus');       
        }, 850);

    }

    function indica_motivo(){

        sel2 = tableStock.getSelectedData();
        pncc = $("#pnc option:selected").text();
        pnc = $.trim(pncc);
        
        if(sel2.length>=1){               
                    
                palets.forEach(v => {
                    //Test if projectname  == parameter1. If it is update status                
                    if ((v.calibre == tableStock.getSelectedData()[0]['Calibre']) && (v.lote == tableStock.getSelectedData()[0]['Lote']) ) v.phc = 1;
                    v.qtdPHC=tableStock.getSelectedData()[0]['Quantidade'];
                });
                
            }

            $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>stocks/GeraPaletePHC/grava_criacao_paletes_pnc",
                    dataType: "json",
                    data:{
                        tbl: palets,
                        motivo: pnc,
                        obs: document.getElementById('obs-pnc').value,
                        obsPL: document.getElementById('obs-plp').value
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
                            going_to_print();

                            setTimeout(function(){
                                location.reload();
                            },1200);                        

                        }
                    },
                    error: function (e) {
                        //alert(e);
                        //alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                        //console.log(e);
                        toastr["error"]("Erro na gravação dos dados! Tente novamente!");
                    }

                }); 
                
    }

    function going_to_print(){

        $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>stocks/GeraPaletePHC/recolhe_dados_palete",
                dataType: "json",
                success: function (data) {
                    //alert(data);
                    //console.log(data);

                    if (data === "kick") {
                        //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        window.location = "home/logout";
                    } else {
                        //toastr["success"]("Dados gravados com Sucesso");
                        //console.log(data);
                        get_report(data);
                    }
                },
                error: function (e) {
                    //alert(e);
                    //alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    //console.log(e);
                    toastr["error"]("Erro na gravação dos dados! Tente novamente!");
                }

            });   

    }

    function  get_report(data){

        artigo = data[0]['Artigo'];
        calibre = data[0]['Calibre'];
        datahora = data[0]['DataHoraMOV'];
        lote = data[0]['Lote'];
        nossaref = data[0]['NossaRef']; 
        palete = data[0]['Numero'];
        qtduni = data[0]['QtdUni'];
        vossaref = data[0]['VossaRef'];
        serie = data[0]['Serie'];   

        $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>standards/Templates/get_report",
                dataType: "json",
                data:{
                     tipodoc: data[0]['Serie']   
                },
                success: function (data) {
                    //alert(data);
                    //console.log(data);

                    if (data === "kick") {
                        //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        window.location = "home/logout";
                    } else {
                        //toastr["success"]("Dados gravados com Sucesso");
                        //console.log(data);                        
                        going_to_create_report(artigo,calibre,datahora,lote,nossaref,palete,qtduni,vossaref,serie,data[0]['text']);
                    }
                },
                error: function (e) {
                    //alert(e);
                    //alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    //console.log(e);
                    toastr["error"]("Erro na gravação dos dados! Tente novamente!");
                }

            });  
    }

    function going_to_create_report(artigo,calibre,datahora,lote,nossaref,palete,qtduni,vossaref,serie,text){

        console.log(text);
        newWindow = window.open();
        newWindow.document.write(text);

        console.log('lote '+lote);
        console.log('calibre '+calibre);
        

        if(serie == 'PLP'){

            //if(lote ==''){
            if(lote == 'XXXXX'){
                //newWindow.document.getElementById('lotes').style.display = 'none';
                newWindow.document.getElementById("lote").style.display = 'none';
            }
            //if(calibre ==''){
            if(calibre == 'XXXXX'){
                //newWindow.document.getElementById('calibres').style.display = 'none';
                newWindow.document.getElementById("calibre").style.display = 'none';
            }
            console.log('lables');
            newWindow.document.getElementById("palete").innerHTML=palete;
            newWindow.document.getElementById("qtd_uni").innerHTML=qtduni;
            newWindow.document.getElementById("lote").innerHTML=lote;
            newWindow.document.getElementById("artigo").innerHTML=artigo;
            newWindow.document.getElementById("calibre").innerHTML=calibre;                
            newWindow.document.getElementById("nossaref").innerHTML=nossaref;
            newWindow.document.getElementById("datahora").innerHTML=datahora;
        }
        else{
            console.log('lables');
            newWindow.document.getElementById("palete").innerHTML=palete;                
            newWindow.document.getElementById("vossaref").innerHTML=vossaref;
            newWindow.document.getElementById("nossaref").innerHTML=nossaref;   
            newWindow.document.getElementById("datahora").innerHTML=datahora;
        }
        
        
        console.log('barcode');
        JsBarcode(newWindow.document.getElementById("barcode"), newWindow.document.getElementById("palete").innerHTML, {
            format: "CODE128A",
            font: "arial",
            fontSize: 72,
            width: 6,
            height: 150,
            textMargin: 15
        });

        console.log('fechou escrita');
        newWindow.document.close();    

        
            console.log('menu impressão');
            console.log('focus');
            newWindow.focus();
		    console.log('print');
            newWindow.print();   
        
        
    }

    function fire_annotation(type,title,text2,action,xposition,campo,valor){

        if(action==='confirmRefPL') {
			Swal.fire({
					icon: type,
					iconHtml: '?',
					iconColor: '#f8bb86',
					title: title,
					html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 28px;color: #333">'+text2+'</p>',
					showDenyButton: true,
					confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
					denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
				}
			).then((result) => {


				if (result.isConfirmed) {
                    palets=[];
                    palets.push({referencia:gbreferencia, descricao:gbdescricao, formato:gbcodigoformato, cb:gbcodigobarras, lote:gblote, 
                                 calibre:gbcalibre, qtd:gbqtd, nome_arm:gbnome_arm, codigo_arm:gbcodigo_arm, phcSector:phcSector, 
                                 qtdPHC:(gbqtd-valor), qtdSup: valor, phc: 0, nome_nivel:gbnome_lvl, codigo_nivel:gbcodigo_lvl});                               
                    chooseLocal();
                  
				} else if (result.isDenied) {
				
				}
			})
        }
        if(action==='confirmRefPNC') {
			Swal.fire({
					icon: type,
					iconHtml: '?',
					iconColor: '#f8bb86',
					title: title,
					html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 28px;color: #333">'+text2+'</p>',
					showDenyButton: true,
					confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
					denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
				}
			).then((result) => {


				if (result.isConfirmed) {
                    palets=[];
                    palets.push({referencia:gbreferencia, descricao:gbdescricao, formato:gbcodigoformato, cb:gbcodigobarras, lote:gblote, 
                                 calibre:gbcalibre, qtd:gbqtd, nome_arm:gbnome_arm, codigo_arm:gbcodigo_arm, phcSector:phcSector, 
                                 qtdPHC:(gbqtd-valor), qtdSup: valor, phc: 0, nome_nivel:gbnome_lvl, codigo_nivel:gbcodigo_lvl});    
                    save_pnc_creation(palets);
                  
				} else if (result.isDenied) {
				
				}
			})
        }
        if(action==='confirmFPNC') {
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
                    
                   confirm_fabrica_to_pnc();

                } else if (result.isDenied) {
                
                }
            })
        }


    }


</script>
