<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Receção Interna de Material</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home"> Home</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-industry"></i> Stocks</li>                        
                        <li class="breadcrumb-item active"><i class="fas fa-box-open"></i> Receção Interna de Material</li>
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
                    <div id="radioButtons" class="form-group">
                    </div>
                    <div id="selected-palets-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                    </div>
                </div>
                
                <div class="card-footer" style="display: block;">
                    <div class="row">
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4" >
                            <button id="open_picking_modal" onclick="open_picking_modal()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                        </div>
                        <div id="factory" class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4" >
                            <button onclick="confirm_reception()" type="button" class="btn btn-info" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Confirmar</button>
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


            <div id="palets-table" class="table table-striped"></div>
			</div>
			<div class="modal-footer">		                
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>		
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
                <button id="save_local" onclick="save_local()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Confirmar</button>	
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

    let tablePaletes, tableSelPaletes, tableLocal,  tableLocal_logistic, selectedData=[], OG, dt, count=0, count2=0, count3=0, local='';
    let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='';
    let palets=[], paletsOG=[], marosca=[];
    let user_type=<?php echo $user_type;?>;

    $(document).ready(function(){

        $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

        
        $("#paleteCB").focus();

        datta=[];
        selectedPalets(datta);

        toastr.clear();
        toastr["info"]("A carregar paletes...");
        $("#open_picking_modal").prop("disabled",true);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/rececao_material/RececaoMaterial/listPalets",
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
                    OG=data;
                    listPalets(data);
                    toastr.clear();
                    toastr["success"]("Paletes carregadas com sucesso.");
                    toastr.clear();
                    $("#open_picking_modal").prop("disabled",false);

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
            url: "<?php echo base_url();?>stocks/rececao_material/RececaoMaterial/radioButton",
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
                    listRadioButtons(data);    
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
            url: "<?php echo base_url();?>stocks/rececao_material/RececaoMaterial/localizacaoCB",
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

    function listRadioButtons(data){

        $("#radioButtons").append(data);                         
        $('#radioButtons input:radio').click(function() {
            //console.log($(this).val());
            
            toastr.clear();
            toastr["info"]("A carregar paletes...");
            $("#open_picking_modal").prop("disabled",true);

            if ($(this).val() == '1') {

                sizeofTBL=tableSelPaletes.getData();        
                if(sizeofTBL.length>0){
                  
                    type='success';
                    title='Dados da tabela serão perdidos';
                    text2='Tem a certeza que pretende continuar?';
                    action='confirmPL';
                    xposition='center';
                    campo='FB005';

                    valor=this;
                    fire_annotation(type,title,text2,action,xposition,campo,valor);
                }else{

                    $('.form-check-input').prop('checked', false); // Unchecks it    
                    $(this).prop('checked', true); // Checks it  
                    
                    newSector='FB005';  
                    changeSector(newSector);                   

                }                

            } else if ($(this).val() == '2') {

                sizeofTBL=tableSelPaletes.getData();        
                if(sizeofTBL.length>0){
                    
                    type='success';
                    title='Dados da tabela serão perdidos';
                    text2='Tem a certeza que pretende continuar?';
                    action='confirmPL';
                    xposition='center';
                    campo='CL005';

                    valor=this;
                    fire_annotation(type,title,text2,action,xposition,campo,valor);
                }else{
                    $('.form-check-input').prop('checked', false); // Unchecks it    
                    $(this).prop('checked', true); // Checks it 
                    
                    newSector='CL005';
                    changeSector(newSector);  
                }                
  
            }           
        });

    }
    
    function changeSector(newSector){

            $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/rececao_material/RececaoMaterial/listPalets_changeOP/"+newSector,
            dataType: "json",
            success: function (data) {
                //alert(data);
               // console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {

                    //console.log(data);
                    $("#selected-palets-table").empty();
                    datta=[];
                    selectedPalets(datta);

                    tablePaletes.destroy();
                    listPalets(data);    

                    toastr.clear();
                    toastr["success"]("Paletes carregadas com sucesso.");
                    toastr.clear();
                    $("#open_picking_modal").prop("disabled",false);
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
            url: "<?php echo base_url();?>stocks/rececao_material/RececaoMaterial/localizacaoCB_changeOP/"+newSector,
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


    }


    function listPalets(data){

        tablePaletes= new Tabulator("#palets-table", {
            //data:data, //assign data to table            
            selectable:true,
            headerSort:false, //disable header sort for all columns
            placeholder:"No Data Available",
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
            columns:[
                {title:"Palete", field:"DocPL", align:"center",headerFilter:"input"},
                {title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                
                {title:"Referencia", field:"Referencia", align:"center"},
                {title:"Artigo", field:"Artigo", align:"center", visible:false},
                {title:"Descrição", field:"DescricaoArtigo", align:"center"},
                {title:"QTD.", field:"Quantidade", align:"center"},
                {title:"UNI.", field:"Unidade", align:"center", visible:false},
                {title:"Sector", field:"Sector", align:"center", visible:false},                
                {title:"Formato", field:"Formato", align:"center", visible:false},
                {title:"Qual", field:"Qual", align:"center", visible:false},
                {title:"TipoEmbalagem", field:"TipoEmbalagem", align:"center", visible:false},
                {title:"Superficie", field:"Superficie", align:"center", visible:false},
                {title:"Decoracao", field:"Decoracao", align:"center", visible:false},
                {title:"RefCor", field:"RefCor", align:"center", visible:false},
                {title:"Lote", field:"Lote", align:"center", visible:false},
                {title:"Nivel", field:"Nivel", align:"center", visible:false},
                {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},                                
                {title:"Calibre", field:"Calibre", align:"center", visible:false},                
                {title:"Operador", field:"Operador", align:"center", visible:false},
                {title:"Sel", field:"Sel", align:"center", visible:false}
            ]
        });

        //tablePaletes.setData(data);
        tablePaletes.setData(Object.values(data));
        tablePaletes.redraw(true);
    }

    function selectedPalets(data){
        tableSelPaletes= new Tabulator("#selected-palets-table", {
            //data:data, //assign data to table
            headerSort:false, //disable header sort for all columns
            placeholder:"No Data Available",
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
            columns:[                
                {title:"Palete", field:"DocPL", align:"center"},
                {title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                
                {title:"Referencia", field:"Referencia", align:"center"},
                {title:"Artigo", field:"Artigo", align:"center", visible:false},
                {title:"Descrição", field:"DescricaoArtigo", align:"center"},
                {title:"QTD.", field:"Quantidade", align:"center"},
                {title:"UNI.", field:"Unidade", align:"center", visible:false},
                {title:"Sector", field:"Sector", align:"center", visible:false},                
                {title:"Formato", field:"Formato", align:"center", visible:false},
                {title:"Qual", field:"Qual", align:"center", visible:false},
                {title:"TipoEmbalagem", field:"TipoEmbalagem", align:"center", visible:false},
                {title:"Superficie", field:"Superficie", align:"center", visible:false},
                {title:"Decoracao", field:"Decoracao", align:"center", visible:false},
                {title:"RefCor", field:"RefCor", align:"center", visible:false},
                {title:"Lote", field:"Lote", align:"center", visible:false},
                {title:"Nivel", field:"Nivel", align:"center", visible:false},
                {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},                                
                {title:"Calibre", field:"Calibre", align:"center", visible:false},                
                {title:"Operador", field:"Operador", align:"center", visible:false},
                {title:"Sel", field:"Sel", align:"center", visible:false}
                
            ]

        });

        tableSelPaletes.setData(data);

        setTimeout(function () {
            tableSelPaletes.redraw();
		}, 100);        

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

    function open_picking_modal(){

        setTimeout(function () {  
            $("#escolha_palete").modal("show");
            //$("#escolha_palete").modal();  
        }, 350);

        setTimeout(function () {            
            $('#paleteCB').trigger('focus');
            tablePaletes.redraw(true);            
        }, 850);

        /*sizeOfTBL01=tablePaletes.getData();  
        sizeOfTBL02=tableSelPaletes.getData();  
        tablePaletes.deselectRow();
        for(i=0;i<sizeOfTBL02.length;i++){                   
            for(j=0;j<sizeOfTBL01.length;j++){
                console.log(tableSelPaletes.getData()[i]['DocPL']);             
                tablePaletes.setFilter("DocPL",  "=", tableSelPaletes.getData()[i]['DocPL']);
                tablePaletes.selectRow("active"); 
                tablePaletes.clearFilter(true);
            }
            

        }
        $("input").focus();*/
    }                          

    function pick_palete(){


        let rows = tablePaletes.searchRows("DocPL", "=", $("#paleteCB").val());//get row components for all rows with an age greater than 12        

        if(rows.length>0){            
            
            tbl = rows[0].getData();    
            paletsOG=tablePaletes.getData();                
            sizeOfTBL=tablePaletes.getData();       

            if(user_type == 1){

                for(i=0;i<sizeOfTBL.length;i++){                    
                        if(tablePaletes.getData()[i]['DocPL']==tbl['DocPL']){                                                                           
                            tablePaletes.setFilter("DocPL",  "=", $("#paleteCB").val());
                            tablePaletes.selectRow("active"); 
                            //palets.push(tablePaletes.getSelectedData());                        
                            palets=tablePaletes.getSelectedData();                            
                            tablePaletes.setData([]);
                            //console.log(paletsOG);
                            for(j=0;j<palets.length;j++){                            
                                //for(k=0;k<palets[j].length;k++){    
                                    for(x=0;x<paletsOG.length;x++){
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
                                    tablePaletes.setData(palets);                                   
                                    tablePaletes.selectRow("all");                               
                            }                           
                            tablePaletes.addRow(paletsOG, false);                  
                            tablePaletes.clearFilter(true);
                            $("#paleteCB").val('');                               
                        }                                               
                    }
                    
                    count++;  

            }else{

                if(count<2){                
            
                    for(i=0;i<sizeOfTBL.length;i++){                    
                        if(tablePaletes.getData()[i]['DocPL']==tbl['DocPL']){                                                                           
                            tablePaletes.setFilter("DocPL",  "=", $("#paleteCB").val());
                            tablePaletes.selectRow("active"); 
                            //palets.push(tablePaletes.getSelectedData());                        
                            palets=tablePaletes.getSelectedData();                            
                            tablePaletes.setData([]);
                            //console.log(paletsOG);
                            for(j=0;j<palets.length;j++){                            
                                //for(k=0;k<palets[j].length;k++){    
                                    for(x=0;x<paletsOG.length;x++){
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
                                    tablePaletes.setData(palets);                                   
                                    tablePaletes.selectRow("all");                               
                            }                           
                            tablePaletes.addRow(paletsOG, false);                  
                            tablePaletes.clearFilter(true);
                            $("#paleteCB").val('');                               
                        }                                               
                    }
                    
                    count++;        
                }
                else{
                    toastr["error"]("Só pode picar duas paletes de cada vez!");
                } 

            }           
        }        
        else{

            toastr["error"]("Não existe nenhuma Palete com o código picado!");

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
        tablePaletes.deselectRow();
        listPalets(OG);
        count=0;
        palets=[];
        $('#paleteCB').trigger('focus');
    }
    

    function listLocal(data){

        console.log(data);

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
                {title:"CodigoBarras", field:"CodigoBarras", align:"center",visible:true,headerFilter:"input"},      
                {title:"Codigo", field:"Codigo", align:"center", visible:false},
                {title:"Operador", field:"Operador", align:"center", visible:false}
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
    
    function save_paletes(){

        sel = tablePaletes.getSelectedData();
        if(user_type == 1){
            $("#selected-palets-table").empty();
            selectedPalets(tablePaletes.getSelectedData());
            $('#escolha_palete').modal('hide');     
        }
        else{
            if(sel.length<=2){
                $("#selected-palets-table").empty();
                selectedPalets(tablePaletes.getSelectedData());
                $('#escolha_palete').modal('hide');     
            }
            else{
                toastr["error"]("Só pode picar duas paletes de cada vez!");
            }   
        }

    }

    function confirm_reception(){

        sizeofTBL=tableSelPaletes.getData();   

        if(sizeofTBL.length>0){


            setTimeout(function () {  
                $("#escolha_local").modal("show");
                //$("#escolha_palete").modal();  
            }, 350);

            setTimeout(function () {            
                $('#localCB').trigger('focus');
                tableLocal.redraw(true);            
            }, 850);

            /*
            $("#escolha_local").modal();   
            listLocal(dt);

            sizeOfTBL01=tableLocal.getData();  
            tableLocal.deselectRow();
            for(j=0;j<sizeOfTBL01.length;j++){
                console.log(tableLocal.getData()[i]['CodigoBarras']);             
                tableLocal.setFilter("CodigoBarras",  "=", local);
                tableLocal.selectRow("active"); 
                tableLocal.clearFilter(true);
            }

            setTimeout(function () {
                tableLocal.redraw();
            }, 10);

            $("input").focus();
            */
        }
        else{
            toastr["error"]("Não foi picada nenhuma palete!");
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


    function save_local(){
      
        sel = tableLocal.getSelectedData();

        if(sel.length<=1){
            
            $("#save_local").prop("disabled",true);        

            $.ajax({
                type: "POST",
                url: "<?php echo base_url();?>stocks/rececao_material/RececaoMaterial/save_movement",
                dataType: "json",
                data:{
                    tbl: tableSelPaletes.getData(),
                    local : tableLocal.getSelectedData()[0]['CodigoBarras'],
                    sectorDestino : tableLocal.getSelectedData()[0]['Codigo']
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
                    //alert(e);
                    $("#save_local").prop("disabled",false);        
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }

            });   
        }
         else{
              toastr["error"]("Só pode picar um local de cada vez!");
          }        

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

				} else if (result.isDenied) {
                    $(valor).prop('checked', false); // Checks it   
				}
			})
        }


    }


</script>


