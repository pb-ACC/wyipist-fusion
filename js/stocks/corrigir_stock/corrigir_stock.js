$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-stocks01").addClass("menu-is-opening menu-open");
$("#menu-stocks02").addClass("active");
$('#menu-stocks03').attr("style", "display: block;" );

$("#corrige-stk01").addClass("active");
$("#corrige-stk02").addClass("active");


let tablePaletes, tableSelPaletes, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[], tblStk=[];
let palets=[], paletsOG=[], marosca=[];
let no_change=0;
let reabilitado=[], producao=[];
selectedPalets(data=[]);

    $.ajax({
        type: "GET",
        url: "http://127.0.0.1/wyipist-fusion/standards/FiltroEmpresa/correcao_stock",
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {                                            
                if (data['select'].length > 0) 
                    $("#select").append(data['select']);                
                    window.requestAnimationFrame(function() {
                        $('#empresasDP').select2();                                    
                    });
                
                if (data['button'].length > 0) 
                    console.log(data['button']['button01']);
                    $("#button01").append(data['button']['button01']); 
                    $("#button02").append(data['button']['button02']); 
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
        }
    },
    error: function (e) {
        //alert(e);
        alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
        console.log(e);
    }
});

/*PALETES*/
function selectedPalets(data){
    
    let deleteUser = function(cell, formatterParams){ //plain text value
        return "<i class='fas fa-trash-alt' style='color: red' title='Remover'></i>";
    };

    tableSelPaletes= new Tabulator("#selected-table", {
        data:data, //assign data to table                 
        //selectableRows:true, //make rows selectable
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
            {title:"Palete", field:"DocPL", align:"center",headerFilter:"input"},
            {title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                
            {title:"Referencia", field:"Referencia", align:"center",headerFilter:"input"},                
            {title:"Artigo", field:"Artigo", align:"center", visible:false},
            {title:"Descrição", field:"DescricaoArtigo", align:"center",headerFilter:"input"},                
            {title:"UNI.", field:"Unidade", align:"center", visible:false},
            {title:"Sector", field:"Sector", align:"center",headerFilter:"input"},                                
            {title:"Formato", field:"Formato", align:"center", headerFilter:"input"},                
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
            {title:"Local", field:"Local", align:"center",headerFilter:"input",headerFilter:"input"},                
            {title:"QTD.", field:"Quantidade", align:"center",headerFilter:"input"},                
            {title:"QTD. Acertada", field:"NovaQtd",  hozAlign:"center", editor:"number",formatter:function (cell) {
                let val = cell.getValue();
                let el = cell.getElement();        
                el.style.backgroundColor = "#fdfd96";        
                return val;
            }},
            {title:"Reabilitado", field:"Reabilitado", hozAlign:"center", editor:true, formatter:"tickCross"},
            {title:"Id", field:"Id", align:"center", visible:false},
            {title:" ",formatter:deleteUser, width:50, align:"center",tooltip:true, cellClick:function(e, cell){
                let row01 = cell.getRow();
                // Deleta a linha
                row01.delete();
            }}
        ]
    });

}

/*BTNS*/
function save_paletes(){   
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
    
    const elems = document.querySelectorAll('#form-palete [name]');
    const data = {};

    elems.forEach(el => {
      data[el.name] = el.value;
    });

    const temPreenchido = Object.values(data).some(value => value !== '');

    if (temPreenchido) {
        toastr.clear();
        toastr["info"]("A carregar dados...");
    
        tableSelPaletes.alert("A processar...");
        $('#empresasDP').prop('disabled', true);
        $("#button01 button").attr("disabled", true);
        $("#button02 button").attr("disabled", true);
    
        $.ajax({
            type: "POST",
            url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_corrige_stk/"+emp+"/"+newSector,
            data:{
                campos: data
            },
            dataType: "json",
            success: function (data) {
                if (data === "kick") {
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {                    
                    tableSelPaletes.clearAlert();
                    //selectedPalets(tablePaletes.getSelectedData());
                    selectedData.push(...data['paletes']); // espalha os objetos
                    console.log(selectedData);
    
                    selectedPalets(selectedData);
                    
                    $('#empresasDP').prop('disabled', false);
                    $("#button01 button").attr("disabled", false);
                    $("#button02 button").attr("disabled", false);
                    toastr.clear();
                    toastr["success"]("Dados carregados com sucesso.");
                    toastr.clear();
                }
            },
            error: function (e) {
                        alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                        console.log(e);
                    }
        });
    } else {
        toastr["error"]("Deve preencher pelo menos um dos campos!");
    }
}

function cancel_palette(){
    sizeofTBL=tableSelPaletes.getData();   
    if(sizeofTBL.length>0){
        setTimeout(function () {  
            $("#motivo_anula").modal("show");
        }, 350);
    }
    else{
        toastr["error"]("Não foi picada nenhuma palete!");
    }
}

function save_anulacao(){  
    type='success';
    title='Tem a certeza que pretende continuar?';
    text2='';
    action='corrige_stock';
    xposition='center';
    tblPL=tableSelPaletes.getData();
    tblLoc=[];
    tblStk=[];
    tblLote=[];
    tblAfet=[];
    fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
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
        tblStk=[];
        tblLote=[];
        tblAfet=[];
        fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
    }else{
        confirm_changeEmpresa();
    }
}

//estúpido eu sei, mas mantém tudo igual ao código já existente
function confirm_changeEmpresa(){
    selectedPalets(data=[]);
}

/*GRAVAR DADOS NA BD*/
function confirm_correction(tblPL){
    // Arrays para armazenar os registros com Reabilitado = 0 e Reabilitado = 1
    
    // Percorrendo o array para separar os registros
    tblPL.forEach(registo => {
        if (registo.Reabilitado == 1) {
                reabilitado.push(registo);
            } else if (registo.Reabilitado == 0) {
                producao.push(registo);
            }
    });
    send_arrays_rp(producao,reabilitado);
}

function send_arrays_rp(producao,reabilitado){
    //anll = $("#anl option:selected").text();
    //anula = $.trim(anll);
    tableSelPaletes.alert("A gravar...");
    $('#empresasDP').prop('disabled', true);
    $("#button01 button").attr("disabled", true);
    $("#button02 button").attr("disabled", true);
    $("#save_anulacao").prop("disabled",true);  
    
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/stocks/corrigir_stock/Gravar_CorrigirStock/confirm_correction",
        dataType: "json",
        data:{
            producao: producao,
            reabilitado: reabilitado,
      //      motivo_anula: anula,
            obs: document.getElementById('obs-anl').value     
        },
        success: function (data) {

            if (data === "kick") {
                //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {
                toastr["success"]("Dados gravados com Sucesso");                
                setTimeout(function(){
                    location.reload();
                },2500);
            }
        },
        error: function (e) {
            alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
            console.log(e);
        }
    });   
}

function save_motivo(){
        
    mtt = $("#mt option:selected").text();
    mt = $.trim(mtt);    
    let text = $("#paleteCB").val();

    if(text.match("PL")){

        sectorDestino='FB991';   
        $.ajax({
            type: "POST",
            url: "http://127.0.0.1/wyipist-fusion/standards/Motivo/save_motivo_and_movstock",
            dataType: "json",
            data:{
                motivo: mt,
                codigomotivo: $("#mt").val(),
                palete: $("#paleteCB").val(),
                sectorDestino: sectorDestino,
                obs: document.getElementById('obs').value
            },
            success: function (data) {
                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {
                    toastr["success"]("Dados gravados com Sucesso");                    
                    obs=document.getElementById('obs').value='';
                    $("#mt").val('');                        
                    $('#motivo').modal('hide');
                    $("#paleteCB").val('');
                    $("#paleteCB").focus();                    
                }
            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
        });

    }else{
        $.ajax({
            type: "POST",
            url: "http://127.0.0.1/wyipist-fusion/standards/Motivo/save_motivo",
            dataType: "json",
            data:{
                motivo: mt,
                codigomotivo: $("#mt").val(),
                palete: $("#paleteCB").val(),
                obs: document.getElementById('obs').value
            },
            success: function (data) {             
                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {
                    toastr["success"]("Dados gravados com Sucesso");                    
                    obs=document.getElementById('obs').value='';
                    $("#mt").val('');                        
                    $('#motivo').modal('hide');
                    $("#paleteCB").val('');
                    $("#paleteCB").focus();                  
                }
            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
        });
    }
}