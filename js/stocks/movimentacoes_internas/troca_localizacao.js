$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-stocks01").addClass("menu-is-opening menu-open");
$("#menu-stocks02").addClass("active");
$('#menu-stocks03').attr("style", "display: block;" );
$("#menu-mov01").addClass("menu-is-opening menu-open");
$("#opcoes-mov01").addClass("menu-is-opening menu-open");
$('#opcoes-mov01').attr("style", "display: block;" );
$("#troca-loc01").addClass("active");
$("#troca-loc02").addClass("active");

let tablePaletes, tableSelPaletes, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[];
let palets=[], paletsOG=[], marosca=[];
selectedPalets(data=[]);

toastr.clear();
toastr["info"]("A carregar paletes...");
$("#choose_palets").prop("disabled",true);


$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarPaletes/getPalets_trocaLocal",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {                                            
            if (data['select'].length > 0) 
                $("#select").append(data['select']);                
                $('#empresasDP').select2();
                $("#select").show();
            if (data['radio'].length > 0) 
                $("#radioButtons").append(data['radio']);                                    
                $("#radioButtons").show();
            if (data['button'].length > 0) 
                $("#buttons").append(data['button']); 
            //alert(data);
            paletsOG=Object.values(data['paletes']);
            //getPalets(Object.values(data['paletes']));
        }
    },
    error: function (e) {
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
});

function selectedPalets(data){
    tableSelPaletes= new Tabulator("#selected-palets-table", {
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
        columns:[
            {title:"Palete", field:"DocPL", align:"center",headerFilter:"input"},
            {title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                
            {title:"Referencia", field:"Referencia", align:"center"},
            {title:"Artigo", field:"Artigo", align:"center", visible:false},
            {title:"Descrição", field:"DescricaoArtigo", align:"center"},
            {title:"QTD.", field:"Quantidade", align:"center"},
            {title:"UNI.", field:"Unidade", align:"center", visible:false},
            {title:"Sector", field:"Sector", align:"center", visible:false},                
            {title:"Formato", field:"Formato", align:"center", visible:false},
            {title:"Qual", field:"Qual", align:"center", visible:false},
            {title:"TipoEmbalagem", field:"TipoEmbalagem", align:"center", visible:false},
            {title:"Superficie", field:"Superficie", align:"center", visible:false},
            {title:"Decoracao", field:"Decoracao", align:"center", visible:false},
            {title:"RefCor", field:"RefCor", align:"center", visible:false},
            {title:"Lote", field:"Lote", align:"center", visible:false},
            {title:"Nivel", field:"Nivel", align:"center", visible:false},
            {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},                                
            {title:"Calibre", field:"Calibre", align:"center", visible:false},                
            {title:"Sel", field:"Sel", align:"center", visible:false},
            {title:"Id", field:"Id", align:"center", visible:false}
        ]
    });
}

/*FILTRO*/
function changeEmpresa(){
    sizeofTBL=tableSelPaletes.getData();   
    if(sizeofTBL.length>0){
        type='success';
        title='Tem a certeza que pretende continuar?';
        text2='';
        action='changeEmpresa';
        xposition='center';
        tblPL=[];
        tblLoc=[];
        fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc); 
    }else{
        confirm_changeEmpresa();
    }
}

function confirm_changeEmpresa(){

    empp = $("#empresasDP option:selected").text();
    emp = $.trim(empp);    
    //alert(emp.toUpperCase());
    $("#choose_palets").prop("disabled",true);    
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn/"+emp,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {    
                toastr.clear();
                toastr["info"]("A carregar paletes...");

                if(emp === 'Certeca'){
                    $("#warehouse").hide();
                    $("#factory").show();
                    $("#move_logistics").show();
                }else{
                    $("#warehouse").show();
                    $("#factory").hide();
                    $("#move_logistics").hide();
                }

                paletsOG=Object.values(data['paletes']);
               // getPalets(Object.values(data['paletes']));
                
                
                    dt = Object.values(data['zonas']);
                    dtt = Object.values(data['zonas']);
                //    listLocal(Object.values(data['zonas']));

                    $("#radioButtons").empty();
                    if (data['radio'].length > 0)                         
                        $("#radioButtons").append(data['radio']);                                                            

                    if (data['button'].length > 0) 
                        $("#buttons").empty();
                        $("#buttons").append(data['button']);                         

                    toastr.clear();
                    toastr["success"]("Paletes carregadas com sucesso.");
                    toastr.clear();
                    $("#choose_palets").prop("disabled",false);          
            }
        },
        error: function (e) {
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }
    });
    
} 
