$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax
$("#sector").hide();
$("#menu-stocks01").addClass("menu-is-opening menu-open");
$("#menu-stocks02").addClass("active");
$('#menu-stocks03').attr("style", "display: block;" );
$("#menu-list01").addClass("menu-is-opening menu-open");

$("#opcoes-list01").addClass("menu-is-opening menu-open");
$('#opcoes-list01').attr("style", "display: block;" );

$("#list-stock01").addClass("active");
$("#list-stock02").addClass("active");

let tablePaletes, tableSelPaletes, tableExtrato, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let palets=[], paletsOG=[], marosca=[];
let no_change=0;

toastr.clear();
toastr["info"]("A carregar stock...");

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarPaletes/getPalets_stock",
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
            
            getSetores(Object.values(data['setores']));      
            
            const selectDiv = document.getElementById('select');
            const codeBarDiv = document.getElementById('sectores');
        
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
            {title:"QTD.", field:"Quantidade", align:"center",headerFilter:"input",bottomCalc:"sum", bottomCalcParams:{precision:2}},
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

/*SETORES*/
function getSetores(data){    
    // Limpa qualquer opção existente
    $('#sector').empty();        

    // Adiciona as novas opções
    for (let i = 0; i < data.length; i++) {
        let value = {
            id: data[i]['Codigo'],
            text: data[i]['Descricao']
        };        
        let option = new Option(value.text, value.id, false, false);
        $('#sector').append(option);
    }        
    // Inicializa o Select2 com o placeholder
    $('#sector').select2({
        placeholder: 'Selecione setor(es)',
        allowClear: true // Adiciona a opção de limpar a seleção
    });
    $("#sector").show();

    $("#sector").on("select2:close", function () {
        toastr.clear();
        toastr["info"]("A carregar movimentos...");
        refactor_new_table();
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
    $('#sector').prop('disabled', true);
      
    $.ajax({
        type: "GET",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_lista/"+emp,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {    
                getPalets([]);
                paletsOG=Object.values(data['paletes']);
                getPalets(Object.values(data['paletes']));
                getSetores(Object.values(data['setores']));           
                tablePaletes.clearAlert();
                $('#empresasDP').prop('disabled', false);
                $('#sector').prop('disabled', false);
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
            //console.log(names)
            sector_values(names);
        }
    }else{
        getPalets(paletsOG);
        toastr.clear();
        toastr["success"]("Stock carregado com sucesso.");
        toastr.clear();
    }
}

function sector_values(names){

    tablePaletes.alert("A processar...");
    $('#empresasDP').prop('disabled', true);
    $('#sector').prop('disabled', true);

    $.ajax({
        type: "GET",
        url: "http://127.0.0.1/wyipist-fusion/stocks/listas/GetStock/filter_by_sectors",
        dataType: "json",
        data:{
            sectors : names
        },
        success: function (data) {        
            getPalets([]);
            //alert(data);
            getPalets(Object.values(data));
            tablePaletes.clearAlert();
            $('#empresasDP').prop('disabled', false);
            $('#sector').prop('disabled', false);
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

/*MODAL PL*/
function open_extrato(palete){

    if(palete !=''){        
        setTimeout(function () {  
            $("#extrato-table").modal("show");
            $(".modal-title").text("Extrato Palete - "+palete);
        }, 350);

        $.ajax({
            type: "GET",
            url: "http://127.0.0.1/wyipist-fusion/stocks/listas/GetStock/filter_by_pallet/"+palete,
            dataType: "json",
            success: function (data) {                
                listExtrato(Object.values(data));
                tablePaletes.deselectRow(); //deselect row with id of 1     
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
        data:data, //assign data to table          
        selectableRows:true, //make rows selectable
        headerSort:false, //disable header sort for all columns
        placeholder:"Sem Dados Disponíveis",   
        layout:"fitColumns",      //fit columns to width of table
        responsiveLayout:"hide",  //hide columns that don't fit on the table        
        pagination:"local",       //paginate the data
        paginationSize:10,         //allow 7 rows per page of data        
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
}
