$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-stocks01").addClass("menu-is-opening menu-open");
$("#menu-stocks02").addClass("active");
$('#menu-stocks03').attr("style", "display: block;" );
$("#menu-list01").addClass("menu-is-opening menu-open");

$("#opcoes-list01").addClass("menu-is-opening menu-open");
$('#opcoes-list01').attr("style", "display: block;" );

$("#list-stock01").addClass("active");
$("#list-stock02").addClass("active");

let tablePaletes, tableSelPaletes, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[];
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
}