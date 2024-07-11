<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Preparação de Cargas</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home"> Home</a></li>
                        <li class="breadcrumb-item"><i class="nav-icon fas fa-industry"></i> Stocks</li>                        
                        <li class="breadcrumb-item active"><i class="fas fa-dolly"></i> Preparação de Cargas</li>
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
                   <h3 class="card-title">Lista de Planos de Carga</h3>
                </div>

                <div class="card-body">
                    <div id="radioButtons" class="form-group" style="display: none;">
                    </div>
                    <div id="gg-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
                    </div>
                </div>
                
                <div class="card-footer" style="display: block;">
                    <div class="row">
                    </div>	
                </div>

            </div>

        </div>
    </section>
</div>

<script>    
    let tableGG;   

    $(document).ready(function(){

        $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/prep_carga/criacao/PreparacaoCarga/listGG",
            dataType: "json",


            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {                    
                    listGG(data);
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
            url: "<?php echo base_url();?>stocks/prep_carga/criacao/PreparacaoCarga/radioButton",
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
       
    });

    
    function listGG(data){

        tableGG= new Tabulator("#gg-table", {
            //data:data, //assign data to table            
            rowClick:function(e, row){
                window.location.href = "<?=base_url();?>stocks/preparacao_carga/"+row.getData().Numero;
            },
            selectable:true,
            headerSort:false, //disable header sort for all columns
            placeholder:"No Data Available",
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
            columns:[
                {title:"Plano Carga", field:"Numero", align:"center", headerFilter:"input"},  
                {title:"Data", field:"Data", align:"center",sorter:"date", headerFilter:"input"},  
                {title:"Data Prevista", field:"DataPrevista", align:"center",sorter:"date", headerFilter:"input"},  
                {title:"Responsável", field:"Responsavel", align:"center", headerFilter:"input"},  
                {title:"Cliente", field:"Cliente", align:"center", headerFilter:"input"},  
                {title:"Obs1", field:"Obs1", align:"center"},
                {title:"Obs2", field:"Obs2", align:"center"},
                {title:"Obs3", field:"Obs3", align:"center"},
                {title:"Ordem", field:"Ordem", align:"center", visible:false},                
                {title:"Operador", field:"Operador", align:"center", visible:false}
            ]
        });

        tableGG.setData(data);
        tableGG.redraw(true);
    }

    function listRadioButtons(data){

        $("#radioButtons").append(data);                         
        $('#radioButtons input:radio').click(function() {
            if ($(this).val() === '1') {

                    $('.form-check-input').prop('checked', false); // Unchecks it    
                    $(this).prop('checked', true); // Checks it  
                    
                    newSector='FB001';  
                    changeSector(newSector);                    
                                            

            } else if ($(this).val() === '2') {

                    $('.form-check-input').prop('checked', false); // Unchecks it    
                    $(this).prop('checked', true); // Checks it 
                    
                    newSector='CL005';
                    changeSector(newSector);  
            }           
        });

    }

    function changeSector(newSector){

            $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/prep_carga/criacao/PreparacaoCarga/listGG_changeOP/"+newSector,
            dataType: "json",
            success: function (data) {
                //alert(data);
               // console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {
                    tableGG.destroy();
                    listGG(data);    
                }

            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });

    }

</script>
