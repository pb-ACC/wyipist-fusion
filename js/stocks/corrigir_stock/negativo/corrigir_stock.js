$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-stocks01").addClass("menu-is-opening menu-open");
$("#menu-stocks02").addClass("active");
$('#menu-stocks03').attr("style", "display: block;" );

$("#menu-stk01").addClass("menu-is-opening menu-open");
$("#opcoes-stk1").addClass("menu-is-opening menu-open");
$('#opcoes-stk1').attr("style", "display: block;" );
$("#list-stk03").addClass("active");
$("#list-stk04").addClass("active");


let tablePaletes, tableSelPaletes, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[], tblStk=[];
let palets=[], paletsOG=[], marosca=[];
let no_change=0;
let reabilitado=[], producao=[];
selectedPalets(data=[]);

    $.ajax({
        type: "GET",
        url: "http://127.0.0.1/wyipist-fusion/standards/FiltroEmpresa/getDropdowns",
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
                if (data['button'].length > 0) 
                    $("#buttons").append(data['button']); 
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
            let select = $('#mt');    
            // Limpa qualquer opção existente
            select.empty();        
            // Adiciona as novas opções
            for (let i = 0; i < data.length; i++) {
                let value = {
                    id: data[i]['Codigo'],
                    text: data[i]['Descricao']
                };        
                let option = new Option(value.text, value.id, false, false);
                select.append(option);
            }        
            // Inicializa o Select2
            select.select2();
        }
    },
    error: function (e) {
        //alert(e);
        alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
        console.log(e);
    }
});

/*PALETES*/
function getPalets(data){    
    tablePaletes= new Tabulator("#tablePLs", {
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
            {title:"Palete", field:"DocPL", align:"center",headerFilter:"input"},
            {title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                
            {title:"Referencia", field:"Referencia", align:"center",headerFilter:"input"},
            {title:"Artigo", field:"Artigo", align:"center", visible:false},
            {title:"Descrição", field:"DescricaoArtigo", align:"center",headerFilter:"input"},
            {title:"QTD.", field:"Quantidade", align:"center",headerFilter:"input"},
            {title:"UNI.", field:"Unidade", align:"center", visible:false},
            {title:"Sector", field:"Sector", align:"center",headerFilter:"input"},
            {title:"Formato", field:"Formato", align:"center", headerFilter:"input"},
            {title:"Qual", field:"Qual", align:"center", visible:false},
            {title:"TipoEmbalagem", field:"TipoEmbalagem", align:"center", visible:false},
            {title:"Superficie", field:"Superficie", align:"center", visible:false},
            {title:"Decoracao", field:"Decoracao", align:"center", visible:false},
            {title:"RefCor", field:"RefCor", align:"center", visible:false},
            {title:"Lote", field:"Lote", align:"center", headerFilter:"input"},
            {title:"Nivel", field:"Nivel", align:"center", visible:false},                
            {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},                                
            {title:"Calibre", field:"Calibre", align:"center",headerFilter:"input"},
            {title:"Sel", field:"Sel", align:"center", visible:false},
            {title:"Local", field:"Local", align:"center",headerFilter:"input"},
            {title:"Id", field:"Id", align:"center", visible:false}
        ]
    });
}

function save_paletes(){

    let selected=[];
    sel = tablePaletes.getSelectedData();

    if(sel.length>0){
        selected=sel;
    }
    else{        
        let sel = tablePaletes.getData();        
        for (let i = 0; i < sel.length; i++) {
            if (sel[i]['Sel'] == 1) {
                selected.push(sel[i]);
            }
        }
    }

    //alert(selected);
    if(user_type == 1 || user_type == 2 || user_type == 4){
        $("#selected-table").empty();
        //selectedPalets(tablePaletes.getSelectedData());
        selectedPalets(selected);
        $('#escolha_palete').modal('hide')     
    }
    else{
        if(selected.length<=2){
            $("#selected-table").empty();
            //selectedPalets(tablePaletes.getSelectedData());
            selectedPalets(selected);
            $('#escolha_palete').modal('hide')     
        }
     else{
          toastr["error"]("Só pode picar duas paletes de cada vez!");
      } 
    }  
}

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
        paginationSize:10,         //allow 7 rows per page of data        
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
            {title:"Formato", field:"Formato", align:"center",headerFilter:"input"},
            {title:"Qual", field:"Qual", align:"center", visible:false},
            {title:"TipoEmbalagem", field:"TipoEmbalagem", align:"center", visible:false},
            {title:"Superficie", field:"Superficie", align:"center", visible:false},
            {title:"Decoracao", field:"Decoracao", align:"center", visible:false},
            {title:"RefCor", field:"RefCor", align:"center", visible:false},
            {title:"Lote", field:"Lote", align:"center",headerFilter:"input"},
            {title:"Nivel", field:"Nivel", align:"center", visible:false},                
            {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},                                
            {title:"Calibre", field:"Calibre", align:"center",headerFilter:"input"},
            {title:"Sel", field:"Sel", align:"center", visible:false},
            {title:"Local", field:"Local", align:"center",headerFilter:"input"},
            {title:"QTD.", field:"Quantidade", align:"center",headerFilter:"input"},
            {title:"QTD. Acertada", field:"NovaQtd",  hozAlign:"center", editor:"input",formatter:function (cell) {
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

function clearPaletes(){
    tablePaletes.deselectRow();
    getPalets(paletsOG);
    count=0;
    palets=[];
    $('#paleteCB').trigger('focus');
}

function pick_palete() {
    let rows = tablePaletes.searchRows("DocPL", "=", $("#paleteCB").val());

    if (rows.length > 0) {
        let tbl = rows[0].getData();
        let paletsOG = tablePaletes.getData();
        let picadas = [];

        if ( (user_type == 1 || user_type == 2 || user_type == 4) || count < 2) {
            for (let i = 0; i < paletsOG.length; i++) {
                if (paletsOG[i]['DocPL'] == tbl['DocPL']) {
                    paletsOG[i]['Sel'] = 1; // Marcar a palete como selecionada
                    picadas.push(paletsOG[i]);
                    paletsOG.splice(i, 1);
                    i--; // Ajustar o índice após a remoção
                }
            }

            // Reordenar a tabela: colocar as paletes picadas no início
            let reorderedData = picadas.concat(paletsOG);
            tablePaletes.setData(reorderedData);

            // Limpar seleção atual    
            tablePaletes.deselectRow();

            // Selecionar apenas as paletes marcadas como selecionadas
            setTimeout(() => { // Adicionado um pequeno delay para garantir que a tabela seja renderizada antes de selecionar as linhas
                tablePaletes.getData().forEach(palete => {
                    if (palete.Sel == 1) {
                        tablePaletes.selectRow(palete.Id); // Selecionar a linha da palete
                    }
                });
            }, 100);

            tablePaletes.clearFilter(true);
            $("#paleteCB").val('');

            if (user_type != 1 || user_type != 2 || user_type != 4) {
                count++;
            }
        } else {
            toastr["error"]("Só pode picar duas paletes de cada vez!");
        }
    } else {
        toastr["error"]("Não existe nenhuma Palete com o código picado!");

        setTimeout(function () {
            $("#motivo").modal();
        }, 350);

        setTimeout(function () {
            $('#obs').trigger('focus');
        }, 850);
    }
}

function select_all_paletes(){        
    tablePaletes.selectRow("visible"); //select all rows currently visible in the table viewport;     
}

/*BTNS*/
function choose_palets(){    
    if (user_type == 1 || user_type == 2){
        empp = $("#empresasDP option:selected").text();
        emp = $.trim(empp);  
        if(emp === 'Certeca'){
            newSector='FB003';
        }else{
            newSector='ST010';
        }
    }
    else{
        if(codigoempresa == 1){
            emp = "CERAGNI";
            newSector='ST010';
        }else{
            emp = "CERTECA";
            newSector='FB003';
        }
    }  
    toastr.clear();
    toastr["info"]("A carregar paletes...");

    tableSelPaletes.alert("A processar...");
    $('#empresasDP').prop('disabled', true);
    $("#buttons button").attr("disabled", true);

    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_corrige_stk_negativo/"+emp+"/"+newSector,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {                    
                paletsOG=Object.values(data['paletes']);
                getPalets(Object.values(data['paletes']));

                tableSelPaletes.clearAlert();
                $('#empresasDP').prop('disabled', false);
                $("#buttons button").attr("disabled", false);
                toastr.clear();
                toastr["success"]("Paletes carregadas com sucesso.");
                toastr.clear();

                setTimeout(function () {  
                    $("#escolha_palete").modal("show");
                    //$("#escolha_palete").modal();  
                }, 350);
            
                setTimeout(function () {            
                    $('#paleteCB').trigger('focus');
                //    tablePaletes.redraw(true);            
                }, 850);  
            }
        },
        error: function (e) {
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }
    });
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