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
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>stocks/preparacao_carga"><i class="fas fa-dolly"></i> Preparação de Cargas</a></li>    
                        <li class="breadcrumb-item active"> <?php echo $_SESSION['PlanoGG']; ?></li>                    
                        
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
                   <h3 class="card-title">Linhas do Plano de Carga - <?php echo $_SESSION['PlanoGG']; ?> </h3>
                </div>

                <div class="card-body">
                    <div id="radioButtons" class="form-group">
                    </div>
                    <div id="linhasgg-table" class="table table-striped"  style="margin-top: 35px;box-shadow: 5px 10px 18px #888888;">
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
            url: "<?php echo base_url();?>stocks/prep_carga/criacao/PreparacaoCarga/list_linhasGG",
            dataType: "json",


            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {                    
                    linhasGG(data);
                }

            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }

        });
    });

    function linhasGG(data){

        tableGG= new Tabulator("#linhasgg-table", {
            //data:data, //assign data to table            
            
            rowClick:function(e, row){
                valida_lotes_afetados(row.getData().LinhaEN,row.getData().DocEN,row.getData().NumeroLinha,row.getData().DocumentoCarga,row.getData().Artigo,row.getData().Referencia,row.getData().DescricaoArtigo);
            },            
            selectable:true,
            headerSort:false, //disable header sort for all columns
            placeholder:"No Data Available",
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
            columns:[
                {title:"Quantidade", field:"Quantidade", align:"center", headerFilter:"input"},  
                {title:"Uni.", field:"Unidade", align:"center", headerFilter:"input"},  
                {title:"Artigo", field:"Artigo", align:"center",sorter:"date", headerFilter:"input"},  
                {title:"Referência", field:"Referencia", align:"center", headerFilter:"input"},  
                {title:"Descrição", field:"DescricaoArtigo", align:"center", headerFilter:"input"},  
                {title:"Apontamentos", field:"Descricao", align:"center", headerFilter:"input"},                  
                {title:"LinhaEN", field:"LinhaEN", align:"center", visible:false},
                {title:"Encomenda", field:"DocEN", align:"center", headerFilter:"input"},  
                {title:"NumeroLinha", field:"NumeroLinha", align:"center", visible:false},
                {title:"DocumentoCarga", field:"DocumentoCarga", align:"center", visible:false},
                {title:"Qtd. PL", field:"QtdPL", align:"center", headerFilter:"input"},  
                {title:"Ordem", field:"Ordem", align:"center", visible:false}
            ]
        });

        tableGG.setData(data);
        tableGG.redraw(true);
    }


    function valida_lotes_afetados(LinhaEN,DocEN,NumeroLinha,DocumentoCarga,Artigo,Referencia,DescricaoArtigo){

        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>stocks/prep_carga/criacao/PreparacaoCarga/valida_lotes_afetados",
            dataType: "json",
            data:{
                DocEN: DocEN,
                Artigo: Artigo,
                Referencia: Referencia
            },
            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {                    
                    if(data.length>0){
                        desc01=DescricaoArtigo.replace(",", "%2C");
                        desc02=desc01.replace(",", "%2C");                        
                        desc03=desc02.replace(".", "%2E");
                        window.location.href = "<?=base_url();?>stocks/preparacao_carga/"+DocumentoCarga+"/"+NumeroLinha+"/referencia/"+Referencia+"/"+desc03+"/"+DocEN+"/"+LinhaEN;     
                    }
                    else{
                        toastr["error"]("Esta referência desta Encomenda/Carga ainda não tem lotes afetados!");
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

    

</script>