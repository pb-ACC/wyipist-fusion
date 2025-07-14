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
            headerTooltip:function(e, cell, onRendered){
                //e - mouseover event
                //cell - cell component
                //onRendered - onRendered callback registration function
                let el = ''; 
                if(cell.getField() === 'StkDisponivel'){
                    
                    el = document.createElement("div");

                    el.style.backgroundColor = "#ffecec";
                    el.style.color = "#a33";
                    el.style.padding = "15px 20px";
                    el.style.margin = "20px auto";
                    el.style.border = "1px solid #f5c2c2";
                    el.style.borderRadius = "10px";
                    el.style.boxShadow = "0 4px 6px rgba(0, 0, 0, 0.1)";
                    el.style.fontFamily = "Segoe UI, sans-serif";
                    el.style.fontSize = "16px";
                    el.style.maxWidth = "600px";
                    el.style.textAlign = "center";

                    el.innerText = '(Qtd. Stk Armazém + Qtd. Stk Produção) - Qtd. Stk Afetado - Qtd. Stk Enc. S/Afetar - Qtd. Stk Pré-EN - Qtd. Reservado';

                }
                return el; 
            },
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

            if (data.Cor != 0) {
                row.getElement().style.backgroundColor = "lightcoral";
            } else {
                row.getElement().style.backgroundColor = ""; // Reseta o estilo se necessário
            }
        },        
        columns:[
                {title:"Id", field:"Id", align:"center", headerFilter:"input",visible:false},
                {title:"Selecionado", field:"Seleccionado", align:"center", headerFilter:"input",visible:false},
                {title:"Referência", field:"Referencia", align:"center", headerFilter:"input"},
                {title:"Descrição", field:"Descricao", align:"center", headerFilter:"input"},
                {title:"Formato", field:"Formato", align:"center", headerFilter:"input"},
                {title:"Stk Disponível", field:"StkDisponivel", align:"center", headerFilter:"input", formatter: "money",
                 formatterParams: {
                    decimal: ".",       // separador decimal
                    thousand: " ",       // separador de milhar ("" para nenhum)
                    precision: 2        // casas decimais
                },},
                {title:"Qtd. Stk Armazém", field:"QtdStkArm", align:"center", headerFilter:"input", formatter: "money",
                 formatterParams: {
                    decimal: ".",       // separador decimal
                    thousand: " ",       // separador de milhar ("" para nenhum)
                    precision: 2        // casas decimais
                },},
                {title:"Qtd. Stk Produção", field:"QtdStkProd", align:"center", headerFilter:"input",formatter: "money",
                 formatterParams: {
                    decimal: ".",       // separador decimal
                    thousand: " ",       // separador de milhar ("" para nenhum)
                    precision: 2        // casas decimais
                },},
                {title:"Qtd. Stk Afetado", field:"QtdStkAfetado", align:"center", headerFilter:"input", formatter: "money",
                 formatterParams: {
                    decimal: ".",       // separador decimal
                    thousand: " ",       // separador de milhar ("" para nenhum)
                    precision: 2        // casas decimais
                },},
                {title:"Qtd. Stk Enc. S/Afetar", field:"QtdStkEncSemAfe", align:"center", headerFilter:"input",formatter: "money",
                 formatterParams: {
                    decimal: ".",       // separador decimal
                    thousand: " ",       // separador de milhar ("" para nenhum)
                    precision: 2        // casas decimais
                },},
                {title:"Qtd. Stk Pré-EN", field:"QtdStkPreEN", align:"center", headerFilter:"input",formatter: "money",
                 formatterParams: {
                    decimal: ".",       // separador decimal
                    thousand: " ",       // separador de milhar ("" para nenhum)
                    precision: 2        // casas decimais
                },},
                {title:"Qtd. Reservado", field:"QtdStkReservado", align:"center", headerFilter:"input",formatter: "money",
                 formatterParams: {
                    decimal: ".",       // separador decimal
                    thousand: " ",       // separador de milhar ("" para nenhum)
                    precision: 2        // casas decimais
                },},
                {title:"Stk em Verificação", field:"StkEmVer", align:"center", headerFilter:"input",formatter: "money",
                 formatterParams: {
                    decimal: ".",       // separador decimal
                    thousand: " ",       // separador de milhar ("" para nenhum)
                    precision: 2        // casas decimais
                },},
                {title:"Qtd. Paletizado", field:"QtdStkPaletizado", align:"center", headerFilter:"input",formatter: "money",
                 formatterParams: {
                    decimal: ".",       // separador decimal
                    thousand: " ",       // separador de milhar ("" para nenhum)
                    precision: 2        // casas decimais
                },},
                {title:"Qualidade", field:"Qualidade", align:"center", headerFilter:"input"},
                {title:"Acabamento", field:"Acabamento", align:"center", headerFilter:"input"},
                {title:"Decoração", field:"Decoracao", align:"center", headerFilter:"input"},
                {title:"Coleção", field:"Coleccao", align:"center", headerFilter:"input"},
                {title:"Coleção Cer", field:"ColeccaoCer", align:"center", headerFilter:"input"},
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
    $('#lotes-table').empty();
    $('#paletes-table').empty();
    $('#enc-table').empty();
    $('#preenc-table').empty();
    
    $("#modal-title").empty();
    $("#modal-title").append(Referencia,' | ',Descricao);
    //alert(NumeroLinha, ' ', LinhaDocumento);
    $("#detalhes-linha").modal("show");

    $("#detalhes-linha").on('hidden.bs.modal', function (event) {
        // Do something after modal is hidden
        tableplanoGG.deselectRow();
        $('#lotes-table').empty();
        $('#paletes-table').empty();
        $('#enc-table').empty();
        $('#preenc-table').empty();
    });    

    
    toastr.clear();
    toastr["info"]("A carregar detalhes...");
    document.getElementById('modal-blocker').style.display = 'block';

    $.ajax({
        type: "GET",
        url: "http://127.0.0.1/wyipist-fusion/comercial/flash/TerminalFlash/filtra_dados_modals",
        data:{
            iLinha: NumeroLinha,
            iDocL: LinhaDocumento,
            refp: Referencia
        },
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {        
                toastr.clear();
                toastr["success"]("Detalhes carregados com sucesso!");
                document.getElementById('modal-blocker').style.display = 'none';                
                drawTable(Object.values(data['lote']),Object.values(data['palete']),Object.values(data['enc']),Object.values(data['preenc']));
            }
        },
        error: function (e) {
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }
    });
}

function drawTable(lote,palete,enc,preenc){

    tableSelplanoGG = new Tabulator("#enc-table", {
        data:enc, //assign data to table                 
        selectableRows:true, //make rows selectable
        headerSort:false, //disable header sort for all columns
        placeholder:"Sem Dados Disponíveis",   
        layout:"fitDataTable",      //fit columns to width of table
        renderHorizontal:"virtual",
        //responsiveLayout:"hide",  //hide columns that don't fit on the table        
        pagination:"local",       //paginate the data
        paginationSize:25,         //allow 7 rows per page of data        
        columnDefaults:{
            tooltip:true
        },
        locale: true, // enable locale support
        langs: {
            "pt-pt": ptLocale
        },
        initialLocale: 'pt-pt',
        columns:[
                {title:"Numero", field:"Numero", align:"center", headerFilter:"input"},
                {title:"Cliente", field:"Nome", align:"center", headerFilter:"input"},
                {title: "Data", field: "Data", headerFilter:"input", 
                            formatter: function(cell) { 
                                const date = new Date(cell.getValue()); 
                                return date.toLocaleString("pt-PT", { day: "2-digit", month: "2-digit", year: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"}).replace(',', '');}},               
                {title:"Carga", field:"DocumentoCarga", align:"center", headerFilter:"input"},
                {title:"Quantidade", field:"Quantidade", align:"center", headerFilter:"input"},
                {title:"Qtd Afetada", field:"QtdAfetada", align:"center", headerFilter:"input"},
                {title:"Qtd Paletizada", field:"QtdPaletizada", align:"center", headerFilter:"input"},
                {title:"Qtd Falta", field:"QtdFalta", align:"center", headerFilter:"input"},
                {title:"Uni", field:"Unidade", align:"center", headerFilter:"input"},
                {title: "Entrega", field: "DataEntrega", headerFilter:"input",
                    formatter: function(cell) { 
                        const date = new Date(cell.getValue()); 
                        return date.toLocaleString("pt-PT", { day: "2-digit", month: "2-digit", year: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"}).replace(',', '');}},
                {title:"Ref. Enc. Cliente", field:"VossaRef", align:"center", headerFilter:"input"},
                {title:"Estado", field:"DescricaoEstado", align:"center", headerFilter:"input"}
        ]
    });

    tableLocal_fabric= new Tabulator("#lotes-table", {
        data:lote, //assign data to table                 
        selectableRows:true, //make rows selectable
        headerSort:false, //disable header sort for all columns
        placeholder:"Sem Dados Disponíveis",   
        layout:"fitDataTable",      //fit columns to width of table
        renderHorizontal:"virtual",
        //responsiveLayout:"hide",  //hide columns that don't fit on the table        
        pagination:"local",       //paginate the data
        paginationSize:25,         //allow 7 rows per page of data        
        columnDefaults:{
            tooltip:true
        },
        locale: true, // enable locale support
        langs: {
            "pt-pt": ptLocale
        },
        initialLocale: 'pt-pt',       
        columns:[
                {title:"Lote", field:"Lote", align:"center", headerFilter:"input"},
                {title:"Calibre", field:"Calibre", align:"center", headerFilter:"input"},
                {title:"Stock Arm", field:"QtdStkArm", align:"center", headerFilter:"input"},
                {title:"Stock Prod", field:"QtdStkProd", align:"center", headerFilter:"input"},
                {title:"Afetado", field:"QtdStkAfetado", align:"center", headerFilter:"input"},
                {title:"StkReservado", field:"QtdStkReservado", align:"center", headerFilter:"input"},
                {title:"Paletizado", field:"QtdStkPaletizado", align:"center", headerFilter:"input"},                
                {title:"Disponível", field:"StkDisponivel", align:"center", headerFilter:"input"},
                {title: "Inicio", field: "Inicio", headerFilter:"input", 
                    formatter: function(cell) { 
                        const date = new Date(cell.getValue()); 
                        return date.toLocaleString("pt-PT", { day: "2-digit", month: "2-digit", year: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"}).replace(',', '');}},
                {title: "Fim", field: "Fim", headerFilter:"input", 
                    formatter: function(cell) { 
                        const date = new Date(cell.getValue()); 
                        return date.toLocaleString("pt-PT", { day: "2-digit", month: "2-digit", year: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"}).replace(',', '');}}
        ]
    });

    tableLocal_logistic = new Tabulator("#paletes-table", {
        data:palete, //assign data to table                 
        selectableRows:true, //make rows selectable
        headerSort:false, //disable header sort for all columns
        placeholder:"Sem Dados Disponíveis",   
        layout:"fitDataStretch",      //fit columns to width of table
        renderHorizontal:"virtual",
        //responsiveLayout:"hide",  //hide columns that don't fit on the table        
        pagination:"local",       //paginate the data
        paginationSize:25,         //allow 7 rows per page of data        
        columnDefaults:{
            tooltip:true
        },
        locale: true, // enable locale support
        langs: {
            "pt-pt": ptLocale
        },
        initialLocale: 'pt-pt',
        columns:[
                {title:"Palete", field:"Palete", align:"center", headerFilter:"input"},
                {title:"Sector", field:"Sector", align:"center", headerFilter:"input"},
                {title:"Local", field:"Local", align:"center", headerFilter:"input"},
                {title:"Lote", field:"Lote", align:"center", headerFilter:"input"},
                {title:"Calibre", field:"Calibre", align:"center", headerFilter:"input"},
                {title:"Quantidade", field:"Quantidade", align:"center", headerFilter:"input"},
                {title: "Inicio", field: "Inicio", headerFilter:"input", 
                    formatter: function(cell) { 
                        const date = new Date(cell.getValue()); 
                        return date.toLocaleString("pt-PT", { day: "2-digit", month: "2-digit", year: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"}).replace(',', '');}},
                {title: "Fim", field: "Fim", headerFilter:"input",
                    formatter: function(cell) { 
                        const date = new Date(cell.getValue()); 
                        return date.toLocaleString("pt-PT", { day: "2-digit", month: "2-digit", year: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"}).replace(',', '');}}
        ]
    });

    tableLocal_warehouse = new Tabulator("#preenc-table", {
        data:preenc, //assign data to table                 
        selectableRows:true, //make rows selectable
        headerSort:false, //disable header sort for all columns
        placeholder:"Sem Dados Disponíveis",   
        layout:"fitDataStretch",      //fit columns to width of table
        renderHorizontal:"virtual",
        //responsiveLayout:"hide",  //hide columns that don't fit on the table        
        pagination:"local",       //paginate the data
        paginationSize:25,         //allow 7 rows per page of data        
        columnDefaults:{
            tooltip:true
        },
        locale: true, // enable locale support
        langs: {
            "pt-pt": ptLocale
        },
        initialLocale: 'pt-pt',
        columns:[
                {title:"Numero", field:"Numero", align:"center", headerFilter:"input"},
                {title: "Data", field: "Data", headerFilter:"input",
                    formatter: function(cell) { 
                        const date = new Date(cell.getValue()); 
                        return date.toLocaleString("pt-PT", { day: "2-digit", month: "2-digit", year: "numeric", hour: "2-digit", minute: "2-digit", second: "2-digit"}).replace(',', '');}},
                {title:"Cliente", field:"Cliente", align:"center", headerFilter:"input"},
                {title:"VossaRef", field:"VossaRef", align:"center", headerFilter:"input"},
                {title:"Uni", field:"Unidade", align:"center", headerFilter:"input"},
                {title:"Quantidade", field:"Quantidade", align:"center", headerFilter:"input"},
                {title:"Falta Importar", field:"QtdFaltaImportar", align:"center", headerFilter:"input"}

        ]
    });

}