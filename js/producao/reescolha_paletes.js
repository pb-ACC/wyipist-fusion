$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-prod01").addClass("menu-is-opening menu-open");
$("#menu-prod02").addClass("active");
$('#menu-prod03').attr("style", "display: block;" );
$("#reescolha-pl01").addClass("active");
$("#reescolha-pl02").addClass("active");

let tablerefs, tableSelrefs, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let refs=[], refsOG=[], marosca=[];
let no_change=0;
let gblote, gbcalibre, gbqtd, gbnome_arm, gbcodigo_arm, gbnome_lvl, gbcodigo_lvl, gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,gbserie;

toastr.clear();
toastr["info"]("A carregar referências...");
$("#code_bar").hide();

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarReferencias/getReferencias",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {                                            
            if (data['select'].length > 0) 
                $("#select").append(data['select']);                
                $('#empresasDP').select2();                                    
            refsOG=Object.values(data['refs']);
            getRefs(Object.values(data['refs']));
            $("#code_bar").show();
            
            const selectDiv = document.getElementById('select');
            const codeBarDiv = document.getElementById('code_bar');
        
            if (!selectDiv.innerHTML.trim()) {
                codeBarDiv.classList.remove('col-sm-12', 'col-sm-push-3', 'col-md-4', 'col-md-push-4', 'col-lg-4', 'col-lg-push-4');
                codeBarDiv.classList.add('col-12'); // Faz o code_bar ocupar toda a largura
            }
        }
    },
    error: function (e) {
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
});

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarMotivos/getMotivos",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {
            //console.log(data);  
            // Limpa qualquer opção existente
            $('#mt').empty();        
            // Adiciona as novas opções
            for (let i = 0; i < data.length; i++) {
                let value = {
                    id: data[i]['Codigo'],
                    text: data[i]['Descricao']
                };        
                let option = new Option(value.text, value.id, false, false);
                $('#mt').append(option);
            }        
            // Inicializa o Select2
            $('#mt').select2();

            toastr.clear();
            toastr["success"]("Referências carregadas com sucesso.");
            toastr.clear();
            $("#choose_refs").prop("disabled",false);  
        }
    },
    error: function (e) {
        //alert(e);
        alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
        console.log(e);
    }
});

/*PALETES*/
function getRefs(data){    
    tablerefs= new Tabulator("#selected-refs-table", {
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
        rowClick: function(row){
            var data = row.getData();
            data.Sel = 1;
        },
        columns:[
            {title:"Referência", field:"Referencia", align:"center",headerFilter:"input"},
            {title:"Descrição", field:"Descricao", align:"center",headerFilter:"input"},
            {title:"Formato", field:"Formato", align:"center",headerFilter:"input"},
            {title:"Cód. Barras", field:"CodigoBarras", align:"center",headerFilter:"input"},
            {title:"Artigo", field:"Artigo", align:"center", visible:false},      
            {title:"Serie", field:"Serie", align:"center", visible:false},      
            {title:"CodigoFormato", field:"CodigoFormato", align:"center", visible:false},                                    
            {title:"Id", field:"Id", align:"center", visible:false},                            
            {title:"Sel", field:"Sel", align:"center", visible:false},     
            {title:"TipoEcra", field:"TipoEcra", align:"center", visible:false}
        ]
    });

    tablerefs.on("rowClick", function(e, row){

        if(row.getData().TipoEcra == undefined || row.getData().TipoEcra == '') toastr["error"]("Referência sem tipo de ecrã! Contacte o administador!") ;         
            else escolha_tipo_pl(row.getData().Referencia,row.getData().Descricao,row.getData().CodigoFormato,row.getData().CodigoBarras,row.getData().TipoEcra,row.getData().Serie);
    });

    $("#escolha-tipo-pl").on("hidden.bs.modal", function () {
        // put your default event here
        tablerefs.deselectRow();
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
        }
    }

    toastr.clear();
    toastr["info"]("A carregar referências...");

    tablerefs.alert("A processar...");
    $('#empresasDP').prop('disabled', true);
    $('input[name="cb"]').prop('disabled', true);
      
    $.ajax({
        type: "GET",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_referencias/"+emp,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {    
                getRefs([]);
                refsOG=Object.values(data['refs']);
                getRefs(Object.values(data['refs']));                          
                tablerefs.clearAlert();
                $('#empresasDP').prop('disabled', false);                
                $('input[name="cb"]').prop('disabled', false);
                toastr.clear();
                toastr["success"]("Referências carregadas com sucesso.");
                toastr.clear();
            }
        },
        error: function (e) {
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }
    });
    
} 

function filter_cb(){

    let rows = tablerefs.searchRows("CodigoBarras", "=", $("#cb").val());
    
    if (rows.length > 0) {
        let tbl = rows[0].getData();
        let localOG = tablerefs.getData();
        let picadas = [];
//5606667253695        
        if (user_type == 1) {
            for (let i = 0; i < localOG.length; i++) {
                if (localOG[i]['CodigoBarras'] == tbl['CodigoBarras']) {
                    localOG[i]['Sel'] = 1; // Marcar a palete como selecionada
                    picadas.push(localOG[i]);
                    localOG.splice(i, 1);
                    i--; // Ajustar o índice após a remoção
                }
            }

            // Reordenar a tabela: colocar as paletes picadas no início
            let reorderedData = picadas.concat(localOG);
            tablerefs.setData(reorderedData);

            // Limpar seleção atual    
            tablerefs.deselectRow();

            // Selecionar apenas as paletes marcadas como selecionadas
            setTimeout(() => { // Adicionado um pequeno delay para garantir que a tabela seja renderizada antes de selecionar as linhas
                tablerefs.getData().forEach(cb => {
                    if (cb.Sel == 1) {
                        //alert($("#cb").val());
                        tablerefs.selectRow(cb.Id); // Selecionar a linha da palete
                    }
                });
            }, 100);

            tablerefs.clearFilter(true);
            //$("#cb").val('');         
        }
    } else {
        toastr["error"]("Código de Barras inexistente!");
        $("#cb").val('');
    }
}

function escolha_tipo_pl(Referencia,Descricao,CodigoFormato,CodigoBarras,Ecra,Serie){
    
    document.getElementById('obs-plp').value='';
    $('#current_count').text(0);

    $('#obs-plp').keyup(function() {    
        var characterCount = $(this).val().length,
        current_count = $('#current_count'),
        maximum_count = $('#maximum_count'),
        count = $('#count');    
        current_count.text(characterCount);  
        
        if(parseInt(characterCount) > 255){
            $('#current_count').css('color', 'red');
        }else{
            $('#current_count').css('color', 'black');
        }
    });

   // sizeofTBL=tableReferences.getSelectedData();   
   tipoecra=Ecra;
   gbreferencia=Referencia;
   gbdescricao=Descricao;
   gbcodigoformato=CodigoFormato;
   gbcodigobarras=CodigoBarras;
   gbserie=Serie;
    if(Ecra=='A'){
        $("#divCalibre").show();
        $("#divLote").show();
        $("#divQtd").show();
    }
    else if(Ecra=='B'){            
        $("#divCalibre").hide();
        $("#divLote").show();
        $("#divQtd").show();
    }
    else if(Ecra=='C'){
        $("#divCalibre").hide();
        $("#divLote").hide();
        $("#divQtd").show();
    }
    
    newTitle = 'PL Produção ('+ Referencia +' - '+Descricao+')';
    $(".modal-title").text(newTitle);

    setTimeout(function () {  
        $("#escolha-tipo-pl").modal("show");               
    }, 350);
}

function savePLS(){
        
    sizeofText = document.getElementById('obs-plp').value;

    if(sizeofText.length<=255){            
      
        if(tipoecra=='A'){
            if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                if($("#lote").val() == '') toastr["error"]("Tem de indicar o Lote!"); 
                    if($("#calibre").val() == '') toastr["error"]("Tem de indicar o Calibre!"); 
            
                        else create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val(),gbserie);   
        }
        else if(tipoecra=='B'){            
            if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                if($("#lote").val() == '') toastr["error"]("Tem de indicar o Lote!"); 

                    else create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val(),gbserie);   
        }
        else if(tipoecra=='C'){
            if($("#qtd").val() == '') toastr["error"]("Tem de indicar o Quantidade!"); 
                
                else create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,$("#lote").val(),$("#calibre").val(),$("#qtd").val(),gbserie);   
        }
    }else{
          toastr["error"]("Só pode escrever até 255 caracteres!");
     }   
}
    
function create_array(gbreferencia,gbdescricao,gbcodigoformato,gbcodigobarras,lote,calibre,qtd,serie){
    tablerefs.alert("A gravar...");
    $('input[name="cb"]').prop('disabled', true);
    $('#empresasDP').prop('disabled', true);
    $("#buttons button").attr("disabled", true);   
    $("#savePLS").prop('disabled', true);
    $("#save_local").prop('disabled', true);

    gblote=lote;
    gbcalibre=calibre;
    gbqtd=qtd;

    refs.push({referencia:gbreferencia, descricao:gbdescricao, formato:gbcodigoformato, cb:gbcodigobarras, lote:lote, calibre:calibre, qtd:qtd, 
               serie:serie, nome_nivel:'Nível 1', codigo_nivel:'lvl1'});        
               
    // Obter e ajustar o nome da empresa selecionada
    emp = $.trim($("#empresasDP option:selected").text()).toUpperCase();
    // Verificar se há uma empresa selecionada
    if (emp != '') {
        if (emp === 'CERTECA') {
            serie='P';
            setor='FB001';
        } else {
            serie='';
            setor='ST010';
        }
    } else {
        // Dependendo do código da empresa, atribuir valores padrão
        if (codigoempresa == 1) {
            serie='';
            setor='ST010';
        } else if (codigoempresa == 2) {
            serie='P';
            setor='FB001';
        } else {
           serie='';
           setor='ST010';
        }
    }

    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/producao/reescolha/Gravar_ReescolhaPaletes/guardar_palete",
        dataType: "json",
        data:{
            palete: refs,
            serie: serie,
            setor: setor,
            obsPL: document.getElementById('obs-plp').value
        },
        success: function (data) {

            if (data === "kick") {
                //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {
                going_to_print();
                toastr["success"]("Dados gravados com Sucesso");                                
            }
        },
        error: function (e) {
            alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
            toastr["error"]("Erro na gravação dos dados! Tente novamente!");
            //console.log(e);
        }
    });  

}

function going_to_print(){

    $.ajax({
            type: "GET",
            url: "http://127.0.0.1/wyipist-fusion/producao/reescolha/Gravar_ReescolhaPaletes/recolhe_dados_palete",
            dataType: "json",
            success: function (data) {
                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {
                    toastr.clear();
                    toastr["info"]("A carregar dados da etiqueta...");
                   // alert(data);
                    get_report(Object.values(data));
                }
            },
            error: function (e) {
                //alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);                             
                toastr["error"]("Erro ao carregar dados da eqtiqueta!");
            }
        });   

}

function get_report(data){

    artigo = data[0]['Artigo'];
    calibre = data[0]['Calibre'];
    datahora = data[0]['DataHoraMOV'];
    lote = data[0]['Lote'];
    nossaref = data[0]['NossaRef']; 
    palete = data[0]['Numero'];
    qtduni = data[0]['QtdUni'];
    vossaref = data[0]['VossaRef'];
    serie = data[0]['Serie'];  
    
    //console.log(serie);

    $.ajax({
            type: "GET",
            url: "http://127.0.0.1/wyipist-fusion/standards/Templates/get_report",
            dataType: "json",
            data:{
                 tipodoc: data[0]['Serie']   
            },
            success: function (data) {
                //alert(data);
                //console.log(data);

                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {
                    toastr.clear();
                    toastr["info"]("A gerar etiqueta...");                    
                    going_to_create_report(artigo,calibre,datahora,lote,nossaref,palete,qtduni,vossaref,serie,data[0]['text']);
                }
            },
            error: function (e) {
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                toastr["error"]("Erro ao gerar etiqueta!");
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
        if(lote == 'XXXXX'){
            //newWindow.document.getElementById('lotes').style.display = 'none';
            newWindow.document.getElementById("lote").style.display = 'none';
        }
        //if(calibre ==''){
        if(calibre == 'XXXXX'){
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
    toastr["success"]("Etiqueta gerada com sucesso");       

    console.log('menu impressão');
    console.log('focus');
    newWindow.focus();
    console.log('print');
    newWindow.print();    
        
    setTimeout(function(){
        location.reload();
    },2500);                        
}