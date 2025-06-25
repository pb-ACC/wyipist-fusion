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
toastr["info"]("A carregar empresas...");
//$("#choose_planoCarga").prop("disabled",true);

$( document ).ready(function() {
    const data = [
    { id: 1, nome: "Ceragni" },
    { id: 2, nome: "Certeca" },
    { id: 3, nome: "Ambas" }
    ];
    
    tableplanoGG= new Tabulator("#empresas-table", {
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
        initialLocale: 'pt-pt',
        rowFormatter: function(row) {
            var data = row.getData();
            
            // Verifica se a linha deve ser marcada como selecionada
            if (data.Sel == 1) {
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

            if (data.Responsavel == '') {
                row.getElement().style.backgroundColor = "lightcoral";
            } else {
                row.getElement().style.backgroundColor = ""; // Reseta o estilo se necessário
            }
        },        
        columns:[
            {title:"", field:"nome", align:"center", headerFilter:"input"},  
            {title:"ID", field:"id", align:"center", headerFilter:"input",visible:false},     
        ]
    });

    toastr.clear();
    toastr["success"]("Empresas carregadas com sucesso.");
    
    tableplanoGG.on("rowClick", function(e, row){
        // Define o cookie

        if(row.getData().nome == 'Ambas'){
            emp='Ceragni-Certeca'
        }else{
            emp=row.getData().nome;
        }

        document.cookie = `empresa=${emp}; path=/`;
        window.location.href = "terminal_flash/"+emp;
    });
});