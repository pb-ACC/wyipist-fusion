<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Gerar Palete Subcontratos</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home"> Home</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-industry"></i> Stocks</li>                        
                        <li class="breadcrumb-item active"><i class="fas fa-cubes"></i> Gerar Palete Subcontratos</li>
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
                <button id="savePLS" onclick="savePLS()" type="button" class="btn btn-success"><i class="fas fa-save"></i> Confirmar</button>	
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
        
        if(parseInt(characterCount) > 255){
            $('#current_count').css('color', 'red');
        }else{
            $('#current_count').css('color', 'black');
        }
    });

    $(document).ready(function(){

        $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

        toastr.clear();
        toastr["info"]("A carregar referências...");        

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>standards/GetReferences/listReferences",
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

    function pick_barCode(){

        toastr.clear();
        toastr["info"]("A carregar referências...");      

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>standards/GetReferences/listReferences_filter",
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
            newTitle = 'PL Subcontrato ('+ Referencia +' - '+Descricao+')';
            $(".modal-title").text(newTitle);

            setTimeout(function () {  
                $("#escolha-tipo-pl").modal("show");               
            }, 350);

            //input qtd (preenchida por default)
            recolhe_qtd(CodigoFormato);

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

    function savePLS(){
        
        sizeofText = document.getElementById('obs-plp').value;

        if(sizeofText.length<=255){            
          
            if(tipoecra=='A'){
                if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                    if($("#lote").val() == '') toastr["error"]("Tem de indicar o Lote!"); 
                        if($("#calibre").val() == '') toastr["error"]("Tem de indicar o Calibre!"); 
                
                            else create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val());   
            }
            else if(tipoecra=='B'){            
                if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                    if($("#lote").val() == '') toastr["error"]("Tem de indicar o Lote!"); 

                        else create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val());   
            }
            else if(tipoecra=='C'){
                if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                    
                    else create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val());   
            }
        }else{
              toastr["error"]("Só pode escrever até 255 caracteres!");
         }   

    }
    
    function create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,lote,calibre,qtd){
        gblote=lote;
        gbcalibre=calibre;
        gbqtd=qtd;

        palets.push({referencia:gbreferencia, descricao:gbdescricao, formato:gbcodigoformato, cb:gbcodigobarras, lote:lote, calibre:calibre, qtd:qtd, 
                     sector:'FB001', serie:'S', nome_nivel:'Nível 1', codigo_nivel:'lvl1'});        
        save_pls_creation();   
    }


    function save_pls_creation(){
      
        $("#savePLS").prop("disabled",true); 
        //console.log(palets);

        $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>stocks/subcontratos/Grava_PL_Gerada/grava_criacao_paletes_pls",
                dataType: "json",
                data:{
                    tbl: palets,
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
                        $("#savePLS").prop("disabled",false); 
                        toastr["error"]("Erro na gravação dos dados! Tente novamente!");
                    }

                });    
   
    }

    function going_to_print(){

        $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>stocks/subcontratos/GetPalete/recolhe_dados_palete",
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

    function get_report(data){

        artigo = data[0]['Artigo'];
        calibre = data[0]['Calibre'];
        datahora = data[0]['DataHoraMOV'];
        lote = data[0]['Lote'];
        nossaref = data[0]['NossaRef']; 
        palete = data[0]['Numero'];
        qtduni = data[0]['QtdUni'];
        vossaref = data[0]['VossaRef'];
        serie = data[0]['Serie'];  
        
        //console.log(serie);

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

        //console.log(text);
        newWindow = window.open();
        newWindow.document.write(text);

        /*
        console.log('lote '+lote);
        console.log('calibre '+calibre);
        console.log('palete '+palete);
        */

        if(serie == 'PLP' || serie == 'PLS'){

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

</script>
