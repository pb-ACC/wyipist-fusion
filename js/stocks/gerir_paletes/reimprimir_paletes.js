$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-stocks01").addClass("menu-is-opening menu-open");
$("#menu-stocks02").addClass("active");
$('#menu-stocks03').attr("style", "display: block;" );
$("#menu-pls01").addClass("menu-is-opening menu-open");
$("#opcoes-pls01").addClass("menu-is-opening menu-open");
$('#opcoes-pls01').attr("style", "display: block;" );
$("#reimprime-pl01").addClass("active");
$("#reimprime-pl02").addClass("active");

let tablerefs, tableSelrefs, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let refs=[], refsOG=[], marosca=[];
let no_change=0;
let gblote, gbcalibre, gbqtd, gbnome_arm, gbcodigo_arm, gbnome_lvl, gbcodigo_lvl, gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,gbserie;
let gbdatahora, gbnossaref, gbpalete, gbvossaref;

toastr.clear();
toastr["info"]("A carregar paletes...");

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarPaletes/getPalets_reimprimePL",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {                                            
            if (data['select'].length > 0) 
                $("#select").append(data['select']);                
                $('#empresasDP').select2();            
            //alert(data);
            paletsOG=Object.values(data['paletes']);
            getPalets(Object.values(data['paletes']));

            toastr.clear();
            toastr["success"]("Paletes carregadas com sucesso.");
        }
    },
    error: function (e) {
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
});

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
        },        
        columns:[
            {title:"Palete", field:"DocPL", align:"center",headerFilter:"input"},
            {title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                
            {title:"Referencia", field:"Referencia", align:"center",headerFilter:"input"},
            {title:"Artigo", field:"Artigo", align:"center", visible:false},
            {title:"Descrição", field:"DescricaoArtigo", align:"center",headerFilter:"input"},
            {title:"QTD.", field:"Quantidade", align:"center",headerFilter:"input"},
            {title:"UNI.", field:"Unidade", align:"center", visible:false},
            {title:"Sector", field:"Sector", align:"center",headerFilter:"input"},                
            {title:"Formato", field:"Formato", align:"center", headerFilter:"input"},                            
            {title:"Calibre", field:"Calibre", align:"center",headerFilter:"input"},            
            {title:"Local", field:"Local", align:"center",headerFilter:"input"},
            {title:"Serie", field:"Serie", align:"center", visible:false},
            {title:"DataHoraMOV", field:"DataHoraMOV", align:"center", visible:false},
            {title:"NossaRef", field:"NossaRef", align:"center", visible:false},
            {title:"VossaRef", field:"VossaRef", align:"center", visible:false},
            {title:"Id", field:"Id", align:"center", visible:false}
        ]
    });

    tablePaletes.on("rowClick", function(e, row){      
        gbreferencia = row.getData().Referencia+' - '+row.getData().DescricaoArtigo;
        gbcalibre = row.getData().Calibre;
        gbdatahora = row.getData().DataHoraMOV;
        gblote = 'LOTE '+row.getData().Lote;
        gbnossaref = row.getData().NossaRef; 
        gbpalete = row.getData().DocPL;
        gbqtd = row.getData().Quantidade+' '+row.getData().Unidade;
        gbvossaref = row.getData().VossaRef;
        gbserie = 'PL'+row.getData().Serie;  

        type='success';
        title='Tem a certeza que pretende continuar?';
        text2='';
        action='reimprimePalete';
        xposition='center';
        tblPL=[];
        tblLoc=[];
        tblLote=[];
        tblAfet=[];

        fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
    });
}

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
    
    //alert(emp.toUpperCase());
    toastr.clear();
    toastr["info"]("A carregar paletes...");
    tablePaletes.alert("A processar...");
    $('#empresasDP').prop('disabled', true);

    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_reimprime/"+emp+"/"+newSector,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {    

                paletsOG=Object.values(data['paletes']);
                getPalets(Object.values(data['paletes']));

                tablePaletes.clearAlert();
                $('#empresasDP').prop('disabled', false);
        
                toastr.clear();
                toastr["success"]("Paletes carregadas com sucesso.");
                toastr.clear(); 
            }
        },
        error: function (e) {
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }
    });
    
} 

function confirma_reimpressao(){
    //alert(tblPL[0]['Serie']);
    $.ajax({
        type: "GET",
        url: "http://127.0.0.1/wyipist-fusion/standards/Templates/get_report",
        dataType: "json",
        data:{
             tipodoc: gbserie
        },
        success: function (data) {
           // alert('PL'+tblPL[0]['Serie']);
            //console.log(data);

            if (data === "kick") {
                //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {
                toastr.clear();
                toastr["info"]("A recolher dados etiqueta...");                    
                going_to_create_report(gbreferencia, gbcalibre, gbdatahora, gblote, gbnossaref,  gbpalete, gbqtd, gbvossaref, gbserie, data[0]['text']);
            }
        },
        error: function (e) {
            alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
            toastr["error"]("Erro ao recolher dados da etiqueta!");
        }
    });  
}

function going_to_create_report(artigo,calibre,datahora,lote,nossaref,palete,qtduni,vossaref,serie,text){

    //console.log(text);
    newWindow = window.open();
    newWindow.document.write(text);

    /*
    console.log('lote '+lote);
    console.log('calibre '+calibre);
    console.log('palete '+palete);
    */

    if(serie == 'PL' || serie == 'PLP'){

        //if(lote ==''){
        if(lote == 'XXXXX' || lote == ''){
            //newWindow.document.getElementById('lotes').style.display = 'none';
            newWindow.document.getElementById("lote").style.display = 'none';
        }
        //if(calibre ==''){
        if(calibre == 'XXXXX' || calibre ==''){
            //newWindow.document.getElementById('calibres').style.display = 'none';
            newWindow.document.getElementById("calibre").style.display = 'none';
        }
        console.log('lables');
        newWindow.document.getElementById("palete").innerHTML=palete;
        newWindow.document.getElementById("qtd_uni").innerHTML=qtduni;
        newWindow.document.getElementById("lote").innerHTML=lote;
        newWindow.document.getElementById("artigo").innerHTML=artigo;
        newWindow.document.getElementById("calibre").innerHTML=calibre;                
        newWindow.document.getElementById("nossaref").innerHTML=nossaref;
        newWindow.document.getElementById("datahora").innerHTML=datahora;
    }
    else{
        console.log('lables');
        newWindow.document.getElementById("palete").innerHTML=palete;                
        newWindow.document.getElementById("vossaref").innerHTML=vossaref;
        newWindow.document.getElementById("nossaref").innerHTML=nossaref;   
        newWindow.document.getElementById("datahora").innerHTML=datahora;
    }
    
    
    console.log('barcode');
    JsBarcode(newWindow.document.getElementById("barcode"), newWindow.document.getElementById("palete").innerHTML, {
        format: "CODE128A",
        font: "arial",
        fontSize: 72,
        width: 6,
        height: 150,
        textMargin: 15
    });

    console.log('fechou escrita');
    newWindow.document.close();    
    
    toastr.clear();
    toastr["success"]("Etiqueta impressa com sucesso");       

    console.log('menu impressão');
    console.log('focus');
    newWindow.focus();
    console.log('print');
    newWindow.print();    
        
    setTimeout(function(){
       location.reload();
    },2500);                        
}