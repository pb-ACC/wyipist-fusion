<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Listas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home"> Home</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-industry"></i> Stocks</li>                        
                        <li class="breadcrumb-item active"><i class="fas fa-clipboard-list"></i> Listas</li>
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
                        <h3 class="card-title">Dynamic Title</h3>
                        <div class="pull pull-right" style="float: right;">									
                            <div class="input-group">
                                <select id="option" class="form-control" style="width: 100%;"" name="options" tabindex="-1" aria-hidden="true" required>
									<option value="1">Consulta Stock</option>
                                    <option value="2">Movimentos efetuados entre datas</option>
								</select>
                            </div>
						</div>	
                    </div>
                    <div class="card-body">
                        <div>
                            <div id="consulta">
                                <div class="input-group">									
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-warehouse"></i>
                                        </span>
                                    </div>
                                    <select id="sector" class="form-control col-lg-8 col-xs-12" name="sectors[]" multiple="multiple" tabindex="-1" aria-hidden="true" required>
                                    <!--<select id="sector" class="form-control col-lg-8 col-xs-12" name="sectors[]" tabindex="-1" aria-hidden="true" required> -->
                                        <option></option>
                                    </select>
                                </div>

                                <div id="consulta-table" class="table table-striped"  style="margin-top:15px; box-shadow: 15px 10px 18px #888888;">
                                </div>                
                            </div>
                            <div id="movimentos" style="display: none;">
                                <div id="reportrange" class="col-lg-2 col-xs-12" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span>&nbsp;&nbsp; <i class="fa fa-caret-down"></i>
                                </div>
                                <div id="movimentos-table" class="table table-striped"  style="margin-top:15px; box-shadow: 5px 10px 18px #888888;">
                                </div>
                            </div>                            
                        </div>    

                    </div>
                    <div class="card-footer" style="display: block;">                    
                    </div>
                </div>
        </div>
    </section>
</div>

<div id="extrato-table" class="modal fade bd-example-modal-lg" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="width:auto; max-width: 95%;">
    <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Extrato</h5>
				<button id="btnclose" type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
                <div id="extrato-palete-table" class="table table-striped"></div>
			</div>
			<div class="modal-footer">		                            
			</div>    
    </div>
  </div>
</div>


<script>

    let tablePaletes, tableExtrato, tableMov;
    let start, end, idate, fdate;

$(document).ready(function(){

        $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);
        $(".card-title").text("Consulta Stock");

        $("#option").select2();
        $("#option").on("select2:close", function () {

            texti = $("#option option:selected").text();
		    text = $.trim(texti);
            $(".card-title").text(text);
            
            check=$("#option").val();    
            if(check==='1'){                
                $("#consulta").show();
                $("#movimentos").hide();
            }
            else if(check==='2'){
                
                setTimeout(function () {  
                    $("#consulta").hide();
                }, 250); 
                
                
                setTimeout(function () {  
                    $("#movimentos").show();
                }, 350);        

                setTimeout(function () {            
                    tableMov.redraw(true);
                }, 850); 
            }
                    
        });

       // dataa=[];
        //listPalets(dataa);
       
        toastr.clear();
        toastr["info"]("A carregar movimentos...");        
        
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/Listas/listPalets",
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
                    listPalets(data);

                }


            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });
        
        
        

       // getPalets();
   
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>standards/Dropdowns/listSectors",
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
                    dropdown_sectors(data);
                }

            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });


        $("#sector").select2({
            closeOnSelect: false,
            placeholder: "Selecione..."
		});
        $("#sector").on("select2:close", function () {

            toastr.clear();
            toastr["info"]("A carregar movimentos...");

            refactor_new_table();

        });
       
        $('#extrato-table').on('hidden.bs.modal', function (e) {
            tablePaletes.deselectRow();
        });


        $(function() {

            start = moment();
            end = moment();

            function cb(start, end) {
                idate=start.format('YYYY-MM-DD');
                fdate=end.format('YYYY-MM-DD');


                $('#reportrange span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
            }

            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

        });

        iS = moment();
        iF = moment();
        datai = iS.format('YYYY-MM-DD');
        dataf = iF.format('YYYY-MM-DD');
        console.log(datai);
        movimentos_entre_datas(datai,dataf);

        $('#reportrange').on('hide.daterangepicker', function(ev, picker) {
            //do something, like clearing an input            
            movimentos_entre_datas(idate,fdate);
        });


    });

    function dropdown_sectors(data){

		for(i=0;i<data.length;i++) {

            let value = {

                id: data[i]['Codigo'],
                text: data[i]['Descricao']

            };

            
            let tmp = '<option value="'+value['id']+'">'+
                         value['text']+
                      '</option>';
            
            let html = $.tmpl(tmp,value);
            
            $("#sector").append(html);
        }
    }

    function listPalets(data){

        //if(data.length > 0){
            toastr.clear();
            toastr["success"]("Movimentos carregados com sucesso.");
            toastr.clear();
        //}
        
        $("#consulta-table").empty();

        tablePaletes= new Tabulator("#consulta-table", {
            //data:data, //assign data to table      
            rowClick:function(e, row){
				open_extrato(row.getData().DocPL);
			},      
            selectable:true,            
            placeholder:"No Data Available",
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
            columns:[
                {title:"Sector", field:"Sector", align:"center",headerFilter:"input"},  
                {title:"Local", field:"Local", align:"center",headerFilter:"input"},
                {title:"Palete", field:"DocPL", align:"center",headerFilter:"input"},
                {title:"Referencia", field:"Referencia", align:"center",headerFilter:"input"},
                {title:"Descrição", field:"DescricaoArtigo", align:"center",headerFilter:"input"},
                {title:"Formato", field:"Formato", align:"center",headerFilter:"input"},
                {title:"QTD.", field:"Quantidade", align:"center",headerFilter:"input"},
                {title:"UNI.", field:"Unidade", align:"center", visible:false},                              
                {title:"Lote", field:"Lote", align:"center",headerFilter:"input"},
                {title:"Calibre", field:"Calibre", align:"center",headerFilter:"input"}, 
                {title:"Nível", field:"Tipo", align:"center",headerFilter:"input"}               
                /*{title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                                
                {title:"Artigo", field:"Artigo", align:"center", visible:false},                                               
                {title:"Qual", field:"Qual", align:"center", visible:false},
                {title:"TipoEmbalagem", field:"TipoEmbalagem", align:"center", visible:false},
                {title:"Superficie", field:"Superficie", align:"center", visible:false},
                {title:"Decoracao", field:"Decoracao", align:"center", visible:false},
                {title:"RefCor", field:"RefCor", align:"center", visible:false},                
                {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},                                                
                {title:"Operador", field:"Operador", align:"center", visible:false},
                {title:"Sel", field:"Sel", align:"center", visible:false},
                {title:"Descrição", field:"Descricao", align:"center",headerFilter:"input",visible:false}*/                    
                
            ]
        });

        
        tablePaletes.setData(Object.values(data));
        tablePaletes.redraw(true);
    }


    function refactor_new_table(){

        check = $('#sector').select2('data');

        if(check.length > 0) {
            let sizeT = $('#sector').select2('data');
            selectedT = [];

            for (let i = 0; i < sizeT.length; i++) {
                selectedT.push(sizeT[i]['id']);                
            }
            
            if (selectedT.length > 0) {

                let names='';
                for(let i=0;i<selectedT.length;i++){
                    names=names+'\',\''+selectedT[i];                
                }
                
                names = names.slice(1);
                names = names.slice(2);
                names= '\''+names+'\'';                
                console.log(names)
                sector_values(names);
            }
        }else{
            listPalets(OG);
        }

        

    }

    function sector_values(names){

        $.ajax({
			type: "POST",
			url: "<?php echo site_url();?>stocks/Listas/filter_by_sectors",
			dataType: "json",
			data:{
				sectors : names
			},
			success: function (data) {
			
                listPalets(data)

			},
			error: function (e) {
				//alert(e);
				alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
				console.log(e);
			}
		});
    }

    function open_extrato(palete){


        if(palete !=''){
            
            setTimeout(function () {  
                $("#extrato-table").modal("show");
                $(".modal-title").text("Extrato Palete - "+palete);
            }, 350);

            $.ajax({
                type: "POST",
                url: "<?php echo site_url();?>stocks/Listas/filter_by_pallet/"+palete,
                dataType: "json",
                success: function (data) {
                    
                    listExtrato(data);        
                },
                error: function (e) {
                    //alert(e);
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }
            });
        }
        else{
            tablePaletes.deselectRow(); //deselect row with id of 1
            toastr.clear();
            toastr["error"]("Linha sem código de Palete preenchida!");
        }

    }


    function listExtrato(data){

        $("#extrato-palete-table").empty();

        let typeMOV = function(cell, formatterParams){
            var value = cell.getValue();
            if(value < 0){
                return "<span style='color:#dc3545;'>" + value + "</span>";
            }else{
                return "<span style='color:#28a745;'>" + value + "</span>";
            }
        };

        tableExtrato= new Tabulator("#extrato-palete-table", {
            //data:data, //assign data to table          
            selectable:true,            
            placeholder:"No Data Available",
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
            /*rowFormatter:function(row){
                if(parseFloat(row.getData().Quantidade) < 0){
                    row.getElement().style.backgroundColor = "#dc3545";
                }
                else if(parseFloat(row.getData().Quantidade) >= 0){
                    row.getElement().style.backgroundColor = "#28a745";
                }
            },*/
            columns:[
                {title:"Sector", field:"Sector", align:"center",headerFilter:"input"},  
                {title:"Local", field:"Local", align:"center",headerFilter:"input"},                
                {title:"Referencia", field:"Referencia", align:"center",headerFilter:"input"},
                {title:"Descrição", field:"Descricao", align:"center",headerFilter:"input"},                                
                {title:"Lote", field:"Lote", align:"center",headerFilter:"input"},
                {title:"Calibre", field:"Calibre", align:"center",headerFilter:"input"},      
                {title:"Nível", field:"Nivel", align:"center",headerFilter:"input"},    
                {title:"Descrição Nível", field:"DescricaoNivel", align:"center",headerFilter:"input",visible:false},    
                {title:"QTD.", field:"Quantidade", align:"center",headerFilter:"input",bottomCalc:"sum", bottomCalcParams:{precision:2},formatter:typeMOV},
                {title:"Acumulado", field:"Acumulado", align:"center",headerFilter:"input",bottomCalc:"sum", bottomCalcParams:{precision:2},formatter:typeMOV},
                {title:"NumeroDocumento", field:"NumeroDocumento", align:"center",headerFilter:"input"},      
                {title:"NumeroLinha", field:"NumeroLinha", align:"center",headerFilter:"input"},    
                {title:"Operador", field:"OperadorMOV", align:"center",headerFilter:"input"},    
                {title:"DataHora", field:"DataHoraMOV", align:"center",headerFilter:"input"},    
                {title:"Ordem", field:"Ordem", align:"center",visible:false}
            ]
        });

        tableExtrato.setData(data);

        setTimeout(function () {            
            tableExtrato.redraw(true);  
        }, 850); 
      
        
    }

    

    function movimentos_entre_datas(idate,fdate){

        $.ajax({
			type: "POST",
			url: "<?php echo site_url();?>stocks/Listas/movimentos_entre_datas",
			dataType: "json",
			data:{
				idate : idate,
                fdate : fdate
			},
			success: function (data) {
			    listMovimentos(data);
			},
			error: function (e) {
				//alert(e);
				alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
				console.log(e);
			}
		});

    }


    function listMovimentos(data){

        $("#movimentos-table").empty();

        let typeMOV = function(cell, formatterParams){
            var value = cell.getValue();
            if(value < 0){
                return "<span style='color:#dc3545;'>" + value + "</span>";
            }else{
                return "<span style='color:#28a745;'>" + value + "</span>";
            }
        };

        tableMov= new Tabulator("#movimentos-table", {
            //data:data, //assign data to table          
            selectable:true,            
            placeholder:"No Data Available",
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
            /*rowFormatter:function(row){
                if(parseFloat(row.getData().Quantidade) < 0){
                    row.getElement().style.backgroundColor = "#dc3545";
                }
                else if(parseFloat(row.getData().Quantidade) >= 0){
                    row.getElement().style.backgroundColor = "#28a745";
                }
            },*/
            columns:[
               // {title:"Sector", field:"Sector", align:"center",headerFilter:"input"},  
                //{title:"Local", field:"Local", align:"center",headerFilter:"input"},                
                //{title:"Referencia", field:"Referencia", align:"center",headerFilter:"input"},
                /*{title:"Descrição", field:"Descricao", align:"center",headerFilter:"input"},                                
                {title:"Lote", field:"Lote", align:"center",headerFilter:"input"},
                {title:"Calibre", field:"Calibre", align:"center",headerFilter:"input"},      
                {title:"Nível", field:"Tipo", align:"center",headerFilter:"input"},                
                {title:"QTD.", field:"Quantidade", align:"center",headerFilter:"input",bottomCalc:"sum", bottomCalcParams:{precision:2},formatter:typeMOV},
                {title:"Acumulado", field:"Acumulado", align:"center",headerFilter:"input",bottomCalc:"sum", bottomCalcParams:{precision:2},formatter:typeMOV},                
                */
               {title:"Palete", field:"DocPL", align:"center",headerFilter:"input"}, 
               /* {title:"NumeroDocumento", field:"NumeroDocumento", align:"center",headerFilter:"input"},      
                {title:"NumeroLinha", field:"NumeroLinha", align:"center",headerFilter:"input"},    
                {title:"OperadorMOV", field:"OperadorMOV", align:"center",headerFilter:"input"},    
                {title:"DataHoraMOV", field:"DataHoraMOV", align:"center",headerFilter:"input"},   
                {title:"Ordem", field:"Ordem", align:"center",visible:false},
                {title:"Descrição", field:"DescricaoNivel", align:"center",headerFilter:"input",visible:false}
                */
            ]
        });

//        tableMov.setData(data);
        tableMov.setData(Object.values(data));
        setTimeout(function () {            
           tableMov.redraw(true);
        }, 850); 
        
    }


</script>


