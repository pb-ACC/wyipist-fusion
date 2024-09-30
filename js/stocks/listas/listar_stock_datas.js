$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax
$("#sector").hide();
$("#menu-stocks01").addClass("menu-is-opening menu-open");
$("#menu-stocks02").addClass("active");
$('#menu-stocks03').attr("style", "display: block;" );
$("#menu-list01").addClass("menu-is-opening menu-open");

$("#opcoes-list01").addClass("menu-is-opening menu-open");
$('#opcoes-list01').attr("style", "display: block;" );

$("#list-stock03").addClass("active");
$("#list-stock04").addClass("active");

let tablePaletes, tableSelPaletes, tableExtrato, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let palets=[], paletsOG=[], marosca=[];
let no_change=0;
let start, end, idate, fdate;

toastr.clear();
toastr["info"]("A carregar stock...");
$("#date_range").hide();

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarPaletes/getPalets_stock_datas",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {                                            
            if (data['select'].length > 0) 
                $("#select").append(data['select']);                
                $('#empresasDP').select2();
            if (data['button'].length > 0) 
                $("#buttons").append(data['button']); 
            //alert(data);
            paletsOG=Object.values(data['paletes']);
            getPalets(Object.values(data['paletes']));
            
            datepicker();                

            $('input[name="daterange"]').on('hide.daterangepicker', function(ev, picker) {
                //do something, like clearing an input   
                idate=picker.startDate.format('YYYY-MM-DD');
                fdate=picker.endDate.format('YYYY-MM-DD');
                
                movimentos_entre_datas(picker.startDate.format('YYYY-MM-DD'),picker.endDate.format('YYYY-MM-DD'));
            });

            $("#date_range").show();

            const selectDiv = document.getElementById('select');
            const codeBarDiv = document.getElementById('date_range');
        
            if (!selectDiv.innerHTML.trim()) {
                codeBarDiv.classList.remove('col-sm-12', 'col-sm-push-3', 'col-md-4', 'col-md-push-4', 'col-lg-4', 'col-lg-push-4');
                codeBarDiv.classList.add('col-12'); // Faz o code_bar ocupar toda a largura
            }

            toastr.clear();
            toastr["success"]("Stock carregado com sucesso.");
            toastr.clear();
        }
    },
    error: function (e) {
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
});


/*PALETES*/
function getPalets(data){    
    tablePaletes= new Tabulator("#selected-palets-table", {
        data:data, //assign data to table                 
        selectableRows:true, //make rows selectable
        headerSort:false, //disable header sort for all columns
        placeholder:"Sem Dados Disponíveis",   
        layout:"fitColumns",      //fit columns to width of table
        responsiveLayout:"hide",  //hide columns that don't fit on the table        
        pagination:"local",       //paginate the data
        paginationSize:25,         //allow 7 rows per page of data        
        columnDefaults:{
            tooltip:true,         //show tool tips on cells
        },
        locale: true, // enable locale support
        langs: {
            "pt-pt": ptLocale
        },
        initialLocale: "pt-pt",
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
            {title:"Nível", field:"Tipo", align:"center",headerFilter:"input"},               
            {title:"Id", field:"Id", align:"center",headerFilter:"input",visible:false}                              
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

    tablePaletes.on("rowClick", function(e, row){
        open_extrato(row.getData().DocPL);
    });
}

/*DATEPICKER*/
function datepicker(){

    $('input[name="daterange"]').daterangepicker({
        opens: 'right',
        locale: {
            format: 'YYYY-MM-DD'
          }
      }, function(start, end, label) {
        idate=start.format('YYYY-MM-DD');
        fdate=end.format('YYYY-MM-DD');
        console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
      });
}

/*FILTRO*/
function changeEmpresa(){    
    // Obter e ajustar o nome da empresa selecionada
    emp = $.trim($("#empresasDP option:selected").text()).toUpperCase();
    // Verificar se há uma empresa selecionada
    if (emp != '') {
        if (emp === 'CERTECA') {
            newSector = 'FB003';
        } else {
            newSector = 'ST010';
        }
    } else {
        // Dependendo do código da empresa, atribuir valores padrão
        if (codigoempresa == 1) {
            emp = "CERAGNI";
            newSector = 'ST010';
        } else if (codigoempresa == 2) {
            emp = "CERTECA";
            newSector = 'FB003';
        } else {
            emp = "CERAGNI";
            newSector = 'ST010';
        }
    }

    toastr.clear();
    toastr["info"]("A carregar stock...");

    tablePaletes.alert("A processar...");
    $('#empresasDP').prop('disabled', true);
    $('input[name="daterange"]').prop('disabled', true);
      
    $.ajax({
        type: "GET",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_lista_datas/"+emp,
        data:{
            idate: idate,
            fdate: fdate            
        },
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {    
                getPalets([]);
                paletsOG=Object.values(data['paletes']);
                getPalets(Object.values(data['paletes']));
                
                tablePaletes.clearAlert();
                
                $('#empresasDP').prop('disabled', false);
                $('input[name="daterange"]').prop('disabled', false);

                toastr.clear();
                toastr["success"]("Stock carregado com sucesso.");
                toastr.clear();
            }
        },
        error: function (e) {
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }
    });
    
} 

function movimentos_entre_datas(idate,fdate){
    // Obter e ajustar o nome da empresa selecionada
    emp = $.trim($("#empresasDP option:selected").text()).toUpperCase();
    // Verificar se há uma empresa selecionada
    if (emp != '') {
        if (emp === 'CERTECA') {
            newSector = 'FB003';
        } else {
            newSector = 'ST010';
        }
    } else {
        // Dependendo do código da empresa, atribuir valores padrão
        if (codigoempresa == 1) {
            emp = "CERAGNI";
            newSector = 'ST010';
        } else if (codigoempresa == 2) {
            emp = "CERTECA";
            newSector = 'FB003';
        } else {
            emp = "CERAGNI";
            newSector = 'ST010';
        }
    }
    
    toastr.clear();
    toastr["info"]("A carregar stock...");

    tablePaletes.alert("A processar...");
    $('#empresasDP').prop('disabled', true);
    $('input[name="daterange"]').prop('disabled', true);

    $.ajax({
        type: "GET",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_lista_datas/"+emp,
        dataType: "json",
        data:{
            idate : idate,
            fdate : fdate
        },
        success: function (data) {            
            getPalets([]);
            paletsOG=Object.values(data['paletes']);
            getPalets(Object.values(data['paletes']));
            
            tablePaletes.clearAlert();
            
            $('#empresasDP').prop('disabled', false);
            $('input[name="daterange"]').prop('disabled', false);

            toastr.clear();
            toastr["success"]("Stock carregado com sucesso.");
            toastr.clear();
        },
        error: function (e) {
            //alert(e);
            alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
            console.log(e);
        }
    });
}