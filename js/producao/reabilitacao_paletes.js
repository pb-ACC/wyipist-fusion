$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-prod01").addClass("menu-is-opening menu-open");
$("#menu-prod02").addClass("active");
$('#menu-prod03').attr("style", "display: block;" );
$("#reabilita-pl01").addClass("active");
$("#reabilita-pl02").addClass("active");


let tablePaletes, tableSelPaletes, tableRefs, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let palets=[], paletsOG=[], marosca=[];
let idValue;
selectedPalets(data=[]);

toastr.clear();
toastr["info"]("A carregar paletes...");
//$("#choose_palets").prop("disabled",true);

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarPaletes/getPalets_prodReabilitaPL",
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

            if (data['button'].length > 0) 
                $("#buttons").append(data['button']); 
            //alert(data);
            paletsOG=Object.values(data['paletes']);
            getPalets(Object.values(data['paletes']));
        }
    },
    error: function (e) {
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
});

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarReferencias/getReferencias_segunda",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {                                      
            getRefs2(Object.values(data['refs']));
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
            toastr["success"]("Paletes carregadas com sucesso.");
            toastr.clear();
            //$("#choose_palets").prop("disabled",false);  
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
        paginationSize:10,         //allow 7 rows per page of data        
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
            {title:"QTD. OK", field:"Qtd_OK",  hozAlign:"center", editor:"input", formatter:function (cell) {
                    let val = cell.getValue();
                    let el = cell.getElement();        
                    el.style.backgroundColor = "#fdfd96";        
                    return val;                
            },cellEdited: function(cell) {
                atualiza_qtdPL(data,tablePaletes);  
            }
            },
            {title:"QTD. NOK", field:"Qtd_NOK",  hozAlign:"center", editor:"input", formatter:function (cell) {
                let val = cell.getValue();
                let el = cell.getElement();        
                el.style.backgroundColor = "#fdfd96";        
                return val;
            },cellEdited: function(cell) {
                atualiza_qtdPL(data,tablePaletes);  
            }
            },
            {title:"Qtd. Caco", field:"Qtd_Caco", align:"center", formatter:function (cell) {
                let val = cell.getValue();
                let el = cell.getElement();        
                el.style.backgroundColor = "#fdfd96";        
                return val;
            }
            },
            {title:"RefSeg", field:"RefSeg", align:"center", visible:false},
            {title:"DescRefSeg", field:"DescRefSeg", align:"center", visible:false},
            {title:"UNI.", field:"Unidade", align:"center", visible:false},
            {title:"Local", field:"Local", align:"center"},                
            {title:"Sector", field:"Sector", align:"center", visible:false},                
            {title:"Formato", field:"Formato", align:"center",headerFilter:"input"},
            {title:"Qual", field:"Qual", align:"center", visible:false},
            {title:"TipoEmbalagem", field:"TipoEmbalagem", align:"center", visible:false},
            {title:"Superficie", field:"Superficie", align:"center", visible:false},
            {title:"Decoracao", field:"Decoracao", align:"center", visible:false},
            {title:"RefCor", field:"RefCor", align:"center", visible:false},
            {title:"Lote", field:"Lote", align:"center",headerFilter:"input"},
            {title:"Nivel", field:"Nivel", align:"center", visible:false},
            {title:"Serie", field:"Serie", align:"center", visible:false},
            {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},                                
            {title:"Calibre", field:"Calibre", align:"center",headerFilter:"input"},                
            {title:"Sel", field:"Sel", align:"center", visible:false},
            {title:"Id", field:"Id", align:"center", visible:false}
        ]
    });

    //tablePaletes.setData(data);
   // tablePaletes.redraw(true);            
    //tablePaletes.setData(Object.values(data));
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
        $("#selected-palets-table").empty();
        //selectedPalets(tablePaletes.getSelectedData());
        selectedPalets(selected);
        $('#escolha_palete').modal('hide')     
    }
    else{
        if(selected.length<=2){
            $("#selected-palets-table").empty();
            //selectedPalets(tablePaletes.getSelectedData());
            selectedPalets(selected);
            $('#escolha_palete').modal('hide')     
        }
     else{
          toastr["error"]("Só pode picar duas paletes de cada vez!");
      } 
    }  
    atualiza_qtdPL(selected,tablePaletes);  
}

function selectedPalets(data){
    let deleteUser = function(cell, formatterParams){ //plain text value
        return "<i class='fas fa-trash-alt' style='color: red' title='Remover'></i>";
    };

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
        locale: true, // enable locale support
        langs: {
            "pt-pt": ptLocale
        },
        initialLocale: "pt-pt",
        columns:[
            {title:"Palete", field:"DocPL", align:"center",headerFilter:"input"},
            {title:"LinhaPL", field:"LinhaPL", align:"center", visible:false},                
            {title:"Referencia", field:"Referencia", align:"center",headerFilter:"input"},
            {title:"Artigo", field:"Artigo", align:"center", visible:false},
            {title:"Descrição", field:"DescricaoArtigo", align:"center",headerFilter:"input"},
            {title:"QTD.", field:"Quantidade", align:"center",headerFilter:"input"},
            {title:"QTD. OK", field:"Qtd_OK",  hozAlign:"center", editor:"input", formatter:function (cell) {
                let val = cell.getValue();
                let el = cell.getElement();        
                el.style.backgroundColor = "#fdfd96";        
                return val;
            },cellEdited: function(cell) {
                atualiza_qtdPL(data,tableSelPaletes);  
            },
            },
            {title:"QTD. NOK", field:"Qtd_NOK",  hozAlign:"center", editor:"input", formatter:function (cell) {
                let val = cell.getValue();
                let el = cell.getElement();        
                el.style.backgroundColor = "#fdfd96";        
                return val;
            },cellEdited: function(cell) {
                atualiza_qtdPL(data,tableSelPaletes);  
            },
            },
            {title:"Qtd. Caco", field:"Qtd_Caco", align:"center", formatter:function (cell) {
                let val = cell.getValue();
                let el = cell.getElement();        
                el.style.backgroundColor = "#fdfd96";        
                return val;
            }
            },
            {title:"Ref. Segunda", field:"RefSeg", align:"center", formatter:function (cell) {
                let val = cell.getValue();
                let el = cell.getElement();        
                el.style.backgroundColor = "#fdfd96";        
                return val;
            }
            },
            {title:"DescRefSeg", field:"DescRefSeg", align:"center", visible:false},
            {title:"UNI.", field:"Unidade", align:"center", visible:false},
            {title:"Local", field:"Local", align:"center"},                
            {title:"Sector", field:"Sector", align:"center", visible:false},                
            {title:"Formato", field:"Formato", align:"center",headerFilter:"input"},
            {title:"Qual", field:"Qual", align:"center", visible:false},
            {title:"TipoEmbalagem", field:"TipoEmbalagem", align:"center", visible:false},
            {title:"Superficie", field:"Superficie", align:"center", visible:false},
            {title:"Decoracao", field:"Decoracao", align:"center", visible:false},
            {title:"RefCor", field:"RefCor", align:"center", visible:false},
            {title:"Lote", field:"Lote", align:"center",headerFilter:"input"},
            {title:"Nivel", field:"Nivel", align:"center", visible:false},
            {title:"Serie", field:"Serie", align:"center", visible:false},
            {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},                                
            {title:"Calibre", field:"Calibre", align:"center",headerFilter:"input"},                
            {title:"Sel", field:"Sel", align:"center", visible:false},
            {title:"Id", field:"Id", align:"center", visible:false},
            {title:" ",formatter:deleteUser, width:50, align:"center",tooltip:true, cellClick:function(e, cell){
                let row01 = cell.getRow();
                // Deleta a linha
                row01.delete();
            }}
        ]
    });

    tableSelPaletes.on("cellDblClick", function(e, cell){ 
        // Verificar se a célula pertence a uma coluna específica
        if (cell.getColumn().getField() === "RefSeg") {
            // Obter o valor da célula
            let cellValue = cell.getValue();
                // Obter os dados da linha correspondente
            let rowData = cell.getRow().getData();    
            // Obter o valor da coluna "Id" nessa linha
            idValue = rowData.Id + 1;
            $("#escolha_segref").modal("show");
        }
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

/*Refs*/
function getRefs2(data){    
    tableRefs= new Tabulator("#tableRefs2", {
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
        },
        rowClick: function(row){
            var data = row.getData();
            data.Sel = 1;
        },
        columns:[                
            {title:"Artigo", field:"Artigo", align:"center", visible:false},
            {title:"Referencia", field:"Referencia", align:"center",headerFilter:"input"},            
            {title:"Descrição", field:"Descricao", align:"center",headerFilter:"input"},            
            {title:"Sel", field:"Sel", align:"center", visible:false},
            {title:"Id", field:"Id", align:"center", visible:false}
        ]
    });

    //tablePaletes.setData(data);
   // tablePaletes.redraw(true);            
    //tablePaletes.setData(Object.values(data));
}

function save_refs(){

    let selected=[];
    sel = tableRefs.getSelectedData();

    if(sel.length>0){
        selected=sel;
    }
    else{        
        let sel = tableRefs.getData();        
        for (let i = 0; i < sel.length; i++) {
            if (sel[i]['Sel'] == 1) {
                selected.push(sel[i]);
            }
        }
    }

    if(selected.length<=1){            
        let row=tableSelPaletes.getRowFromPosition(idValue);
        row.update({RefSeg: selected[0]['Referencia'], DescRefSeg: selected[0]['Descricao']});
        $('#escolha_segref').modal('hide')     
    }
     else{
          toastr["error"]("Só pode picar uma referência de cada vez!");
      } 
}

/*BTNS*/
function choose_palets(){    
    setTimeout(function () {  
        $("#escolha_palete").modal("show");
        //$("#escolha_palete").modal();  
    }, 350);

    setTimeout(function () {            
        $('#paleteCB').trigger('focus');
    //    tablePaletes.redraw(true);            
    }, 850);
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
        tblLote=[];
        tblAfet=[];
        fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
    }else{
        confirm_changeEmpresa();
    }
}

function confirm_changeEmpresa(){

    // Obter e ajustar o nome da empresa selecionada
    emp = $.trim($("#empresasDP option:selected").text()).toUpperCase();
    // Verificar se há uma empresa selecionada
    if (emp != '') {
        if (emp === 'CERTECA') {
            newSector = 'FB001';
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
            newSector = 'FB001';
        } else {
            emp = "CERAGNI";
            newSector = 'ST010';
        }
    }

    //$("#choose_palets").prop("disabled",true);    
    toastr.clear();
    toastr["info"]("A carregar paletes...");
    tableSelPaletes.alert("A processar...");
    $('#empresasDP').prop('disabled', true);
    $("#buttons button").attr("disabled", true);    
    
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_reabilitapalete_prod/"+emp,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {    
                
                paletsOG=Object.values(data['paletes']);
                getPalets(Object.values(data['paletes']));
                
                    if (data['button'].length > 0) 
                        $("#buttons").empty();
                        $("#buttons").append(data['button']);    

                tableSelPaletes.clearAlert();
                $('#empresasDP').prop('disabled', false);
                $("#buttons button").attr("disabled", false);                

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

/*ATUALIZA QTDS*/
function atualiza_qtdPL(selected,table){        
    for(i=1; i<=selected.length; i++){        
        let row=table.getRowFromPosition(i);        

        if(parseFloat(table.getData()[i-1]['Qtd_OK']) < 0 || parseFloat(table.getData()[i-1]['Qtd_NOK']) < 0){
            toastr["error"]("Não pode colocar quantidades negativas!");
            row.update({Qtd_OK:0, Qtd_NOK:0, Qtd_Caco: 0}); 
        }
        else{            
            k = parseFloat(table.getData()[i-1]['Quantidade']) - (parseFloat(table.getData()[i-1]['Qtd_OK'])+parseFloat(table.getData()[i-1]['Qtd_NOK']));        
            if(k < 0){
                toastr["error"]("Está a tentar colocar linhas cuja Qtd OK + Qtd NOK é superior à quantidade da palete!");
                row.update({Qtd_OK:0, Qtd_NOK:0, Qtd_Caco: 0});                    
            }else{
                row.update({Qtd_Caco: parseFloat(k)});        
            }
        }        
    }
}

/*GRAVAR DADOS NA BD*/
function send_to_rehabilitates(setor){
    
    let tbl = tableSelPaletes.getData();    
    let hasEmptyRefSeg = tbl.some((_, i) => 
        !tableSelPaletes.getData()[i]['RefSeg'] && tableSelPaletes.getData()[i]['Qtd_NOK'] > 0
    );        
    if (hasEmptyRefSeg) {
        toastr.error("Há linhas em que pretende colocar Qtd NOK e a referência de segunda não foi selecionada!");
    } else {
        valite_sending_to_rehabilitates(setor,tbl);
    }             
}

function valite_sending_to_rehabilitates(setor,tbl){
    if(tbl.length>0){
        type='success';
        title='Tem a certeza que pretende continuar?';
        text2='';
        action='valida_reabilitados';
        xposition='center';
        tblPL=tableSelPaletes.getData();
        tblLoc=[];
        tblLote=[];
        tblAfet=[];
        valor=setor;
        fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
    }else{
        toastr["error"]("Não picou nenhuma palete!");
    }     
}

function confirm_save_rehabilitates(tblPL,valor){
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/producao/reabilitacao/Gravar_RebilitacaoPaletes/save_rehabilitation",
        dataType: "json",
        data:{
            paletes: tblPL,
            setor: valor
        },
        success: function (data) {
            if (data === "kick") {
                //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {
                toastr["success"]("Dados gravados com Sucesso");                    
                location.reload();
            }
        },
        error: function (e) {
            //alert(e);
            alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
            toastr["error"]("Erro ao gravar dados");    
            //console.log(e);
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