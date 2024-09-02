$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-planos01").addClass("menu-is-opening menu-open");
$("#menu-planos02").addClass("active");
$('#menu-planos03').attr("style", "display: block;" );
$("#prep-carg01").addClass("active");
$("#prep-carg02").addClass("active");

let tableplanoGG, tableSelplanoGG, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let planoCarga=[], planoCargaOG=[], marosca=[];
//selectedplanoCarga(data=[]);

toastr.clear();
toastr["info"]("A carregar planos de carga...");
//$("#choose_planoCarga").prop("disabled",true);

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/planos_carga/preparacao/PreparacaoCarga/getPlanoCarga",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {                                            
            if (data['select'].length > 0) 
                $("#radioButtons").append(data['select']);                
                $('#empresasDP').select2();
                $("#radioButtons").show();
            //alert(data);
            planoCargaOG=Object.values(data['carga']);
            getPlanoCarga(Object.values(data['carga']));
            
            toastr.clear();
            toastr["success"]("Planos de carga carregados com sucesso.");
            toastr.clear();
        }
    },
    error: function (e) {
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
});

/*PALETES*/
function getPlanoCarga(data){    
    tableplanoGG= new Tabulator("#plano-carga-table", {
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
            {title:"Plano Carga", field:"DocumentoCarga", align:"center", headerFilter:"input",width:170},  
            {title:"Data Prevista", field:"Data", align:"center",sorter:"date",headerFilter:"input",width:150},  
            {title:"Responsável", field:"Responsavel", align:"center", headerFilter:"input",width:150},
            {title:"Cliente", field:"Cliente", align:"center", headerFilter:"input"},  
            {title:"Vossa Ref.", field:"VossaRef", align:"center", headerFilter:"input",visible:false},     
            {title:"DocGG", field:"DocumentoCarga", align:"center", visible:false},     
            {title:"Serie", field:"Serie", align:"center", visible:false},     
            {title:"DocEN", field:"NumeroDocumento", align:"center", visible:false},     
            {title:"NumeroLinha", field:"NumeroLinha", align:"center", visible:false},     
        ]
    });
    tableplanoGG.on("rowClick", function(e, row){
        //e - the click event object
        //row - row component
        //alert(row.getData().DocumentoCarga);
        window.location.href = "load_preparation/"+row.getData().Serie+"/"+row.getData().DocumentoCarga;        
    });
}

/*FILTRO*/
function changeEmpresa(){
    empp = $("#empresasDP option:selected").text();
    emp = $.trim(empp);    
    //alert(emp.toUpperCase());
    //$("#choose_planoCarga").prop("disabled",true);    
    toastr.clear();
    toastr["info"]("A planos de carga...");
    tableplanoGG.alert("A processar...");
    $('#empresasDP').prop('disabled', true);
    
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_planoscarga/"+emp,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {    
                
                planoCargaOG=Object.values(data['carga']);
                getPlanoCarga(Object.values(data['carga']));

                tableplanoGG.clearAlert();
                $('#empresasDP').prop('disabled', false);

                toastr.clear();
                toastr["success"]("Planos de carga carregados com sucesso.");
                toastr.clear();
            }
        },
        error: function (e) {
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }
    });
    
} 