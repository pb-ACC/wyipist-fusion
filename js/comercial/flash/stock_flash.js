$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-comer01").addClass("menu-is-opening menu-open");
$("#menu-comer02").addClass("active");
$('#menu-comer03').attr("style", "display: block;" );
$("#flash01").addClass("active");
$("#flash02").addClass("active");

let tableplanoGG, tableSelplanoGG, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let planoCarga=[], planoCargaOG=[], marosca=[];

toastr.clear();
toastr["info"]("A carregar listagem de stock...");

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/comercial/flash/TerminalFlash/getStock_company/"+aVer,
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {        
            getStock(Object.values(data['dados']));

            toastr.clear();
            toastr["success"]("Listagem de Stock carregada com sucesso.");
        }
    },
    error: function (e) {
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
});


function getStock(data){    

    let seeDetails = function(cell, formatterParams){ //plain text value
        return "<i class='fas fa-eye' style='color: blue' title='Ver Detalhes'></i>";
    };

    tableplanoGG= new Tabulator("#plano-carga-table", {
        data:data, //assign data to table                 
        selectableRows:true, //make rows selectable
        headerSort:false, //disable header sort for all columns
        placeholder:"Sem Dados Disponíveis",   
        layout:"fitDataTable",      //fit columns to width of table
        renderHorizontal:"virtual",
        //responsiveLayout:"hide",  //hide columns that don't fit on the table        
        pagination:"local",       //paginate the data
        paginationSize:25,         //allow 7 rows per page of data        
        columnDefaults:{
            tooltip:true,         //show tool tips on cells
        },
        locale: true, // enable locale support
        langs: {
            "pt-pt": ptLocale
        },
        initialLocale: 'pt-pt',
        rowFormatter: function(row) {
            var data = row.getData();
            
            // Verifica se a linha deve ser marcada como selecionada
            if (data.Seleccionado == 1) {
                // Aplica classes CSS específicas para seleção
                row.getElement().classList.add("tabulator-selected");
                row.getElement().classList.add("tabulator-selectable");
            } else {
                // Remove as classes CSS de seleção se não estiver selecionada
                row.getElement().classList.remove("tabulator-selected");
                row.getElement().classList.remove("tabulator-selectable");
            }
    
            // Adiciona classes para linhas ímpares e pares automaticamente pelo Tabulator
            if (row.getIndex() % 2 === 0) {
                row.getElement().classList.add("tabulator-row-even");
                row.getElement().classList.remove("tabulator-row-odd");
            } else {
                row.getElement().classList.add("tabulator-row-odd");
                row.getElement().classList.remove("tabulator-row-even");
            }
    
            // Outros estilos ou classes podem ser adicionados conforme necessário

            /*if (data.Responsavel == '') {
                row.getElement().style.backgroundColor = "lightcoral";
            } else {
                row.getElement().style.backgroundColor = ""; // Reseta o estilo se necessário
            }*/
        },        
        columns:[
                {title:"Id", field:"Id", align:"center", headerFilter:"input",visible:false},
                {title:"Selecionado", field:"Seleccionado", align:"center", headerFilter:"input",visible:false},
                {title:"Referência", field:"Referencia", align:"center", headerFilter:"input"},
                {title:"Descrição", field:"Descricao", align:"center", headerFilter:"input"},
                {title:"Formato", field:"Formato", align:"center", headerFilter:"input"},
                {title:"Qualidade", field:"Qualidade", align:"center", headerFilter:"input"},
                {title:"Acabamento", field:"Acabamento", align:"center", headerFilter:"input"},
                {title:"Decoração", field:"Decoracao", align:"center", headerFilter:"input"},
                {title:"Coleção", field:"Coleccao", align:"center", headerFilter:"input"},
                {title:"Coleção Cer", field:"ColeccaoCer", align:"center", headerFilter:"input"},
                {title:"Qtd. Stk Armazém", field:"QtdStkArm", align:"center", headerFilter:"input"},
                {title:"Qtd. Stk Produção", field:"QtdStkProd", align:"center", headerFilter:"input"},
                {title:"Qtd. Stk Afetado", field:"QtdStkAfetado", align:"center", headerFilter:"input"},
                {title:"Qtd. Stk Enc. S/Afetar", field:"QtdStkEncSemAfe", align:"center", headerFilter:"input"},
                {title:"Qtd. Stk Pré-EN", field:"QtdStkPreEN", align:"center", headerFilter:"input"},
                {title:"Qtd. Paletizado", field:"QtdStkPaletizado", align:"center", headerFilter:"input"},
                {title:"Qtd. Reservado", field:"QtdStkReservado", align:"center", headerFilter:"input"},
                {title:"Stk Disponível", field:"StkDisponivel", align:"center", headerFilter:"input"},
                {title:"Stk em Verificação", field:"StkEmVer", align:"center", headerFilter:"input"},
                {title:"Ordem", field:"Ordem", align:"center", headerFilter:"input",visible:false},
                {title:"Nível", field:"Nivel", align:"center", headerFilter:"input",visible:false},
                {title:"Nº Linha", field:"NumeroLinha", align:"center", headerFilter:"input",visible:false},
                {title:"Linha Documento", field:"LinhaDocumento", align:"center", headerFilter:"input",visible:false},
                {title:"Documento", field:"Documento", align:"center", headerFilter:"input",visible:false},
                {title:"Cor", field:"Cor", align:"center", headerFilter:"input",visible:false},
                {title:"CG?", field:"RefCeragni", align:"center", formatter:"tickCross", headerFilter:"input"},
                {title:"CT?", field:"RefCerteca", align:"center", formatter:"tickCross", headerFilter:"input"},
                {title:" ",formatter:seeDetails, width:50, align:"center",tooltip:true,cellClick:function(e, cell){getDetails(cell.getRow().getData().NumeroLinha,cell.getRow().getData().LinhaDocumento,cell.getRow().getData().Referencia,cell.getRow().getData().Descricao)}}
        ]
    });

    tableplanoGG.on("rowClick", function(e, row){
        // Define o cookie
        getDetails(row.getData().NumeroLinha,row.getData().LinhaDocumento,row.getData().Referencia,row.getData().Descricao);
    });
}


function getDetails(NumeroLinha,LinhaDocumento,Referencia,Descricao){

    $("#modal-title").empty();
    $("#modal-title").append(Referencia,' - ',Descricao);
    //alert(NumeroLinha, ' ', LinhaDocumento);
    $("#detalhes-linha").modal("show");

    $("#detalhes-linha").on('hidden.bs.modal', function (event) {
        // Do something after modal is hidden
        tableplanoGG.deselectRow();
    });    
}


