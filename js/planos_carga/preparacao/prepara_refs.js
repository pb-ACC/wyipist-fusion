$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-planos01").addClass("menu-is-opening menu-open");
$("#menu-planos02").addClass("active");
$('#menu-planos03').attr("style", "display: block;" );
$("#prep-carg01").addClass("active");
$("#prep-carg02").addClass("active");

let tablePaletes, tableSelPaletes, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, tableLinha, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[];
let palets=[], paletsOG=[], marosca=[];

$(".card-body .m-0").hide();
$(".card-footer .row").hide();

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarPaletes/getPalets_prepararefs/"+serie+"/"+linha+"/"+refp,
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {                                            

            palets=Object.values(data['linhaGG']);
            linhaGG(Object.values(data['linhaGG']));
            selectedPalets(data=[]);
            $(".card-body .m-0").show();
            $(".card-footer .row").show();
        }
    },
    error: function (e) {
        //alert(e);
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
            //$("#choose_palets").prop("disabled",false);  
        }
    },
    error: function (e) {
        //alert(e);
        alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
        console.log(e);
    }
});

//$("#selected-palets-table").show();

/*LINHA EN*/
function linhaGG(data){
    tableLinha= new Tabulator("#line-table", {
        data:data, //assign data to table          
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
        columns:[
            {title:"Responsável", field:"Responsavel", align:"center", headerFilter:"input",width:140},  
            {title:"Encomenda", field:"NumeroDocumento", align:"center", headerFilter:"input",width:140},  
            {title:"Qtd.", field:"Quantidade", align:"center", headerFilter:"input",width:140},  
            {title:"c/Pal.", field:"QtdPaletizada", align:"center", headerFilter:"input",width:140},  
            {title:"Falta", field:"QtdFalta", align:"center", headerFilter:"input",width:140},  
            {title:"Uni.", field:"Unidade", align:"center", headerFilter:"input", width:78},  
            {title:"Referência", field:"Referencia", align:"center", headerFilter:"input",width:160},  
            {title:"Descrição", field:"DescricaoArtigo", align:"center", headerFilter:"input"},  
            {title:"Apontamentos", field:"Descricao", align:"center", headerFilter:"input"},
            {title:"Ref. Cliente", field:"VossaRef", align:"center", headerFilter:"input"},
            {title:"QtdLinhaEN", field:"QtdLinhaEN", align:"center", headerFilter:"input",visible:false},  
            {title:"Artigo", field:"Artigo", align:"center",sorter:"date", headerFilter:"input",visible:false},  
            {title:"LinhaEN", field:"NumeroLinha", align:"center", visible:false},
            {title:"NumeroLinha", field:"NumeroLinha", align:"center", visible:false},
            {title:"DocumentoCarga", field:"DocumentoCarga", align:"center", visible:false},
            {title:"Id", field:"Id", align:"center", visible:false}
        ]
    });
}

function selectedPalets(data){
    tableSelPaletes= new Tabulator("#selected-palets-table", {
        data:data, //assign data to table          
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
            {title:"Referencia", field:"Referencia", align:"center"},
            {title:"Artigo", field:"Artigo", align:"center", visible:false},
            {title:"Descrição", field:"DescricaoArtigo", align:"center"},
            {title:"QTD.", field:"Quantidade", align:"center"},
            {title:"QTD. a Usar", field:"NovaQtd",  hozAlign:"center", editor:"input",formatter:function (cell) {
                let val = cell.getValue();
                let el = cell.getElement();        
                el.style.backgroundColor = "#fdfd96";        
                return val;
            },cellEdited: function(cell) {
                // Obter os dados da célula editada
                let editedData = cell.getData();
            
                // Inicializar o valor QtdPaletizada em tableLinha
                let row = tableLinha.getRowFromPosition(1); // Ajuste para a linha específica conforme necessário
                row.update({ QtdPaletizada: 0 });
                row.update({ QtdFalta: 0 });
            
                // Calcular o novo valor de QtdPaletizada
                let k = 0;
                let tableSelPaletesData = tableSelPaletes.getData(); // Obter todos os dados de tableSelPaletes
                for (let i = 0; i < tableSelPaletesData.length; i++) {
                    k = parseFloat(k) + parseFloat(tableSelPaletesData[i]['NovaQtd']);
                }
            
                // Atualizar a linha em tableLinha com o novo valor de QtdPaletizada
                row.update({QtdPaletizada: k});
                row.update({QtdFalta: parseFloat(tableLinha.getData()[0]['Quantidade']) - parseFloat(tableLinha.getData()[0]['QtdPaletizada']) });
            },
            },
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

/*BTNS*/
function choose_palets(){  
    toastr.clear();
    toastr["info"]("A carregar paletes...");  

    $.ajax({
        type: "GET",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarPaletes/getPalets_prepararefs/"+serie+"/"+linha+"/"+refp,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {                                            
   
                paletsOG=Object.values(data['paletes']);
                getPalets(Object.values(data['paletes']));
                
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

function close_gg(){
    if(tableLinha.getData()[0]['Unidade'] === 'UNI'){
        type='success';
        title='Tem a certeza que pretende continuar?';
        text2='';
        action='close_gg';
        xposition='center';
        tblPL=tableLinha.getData();
        tblLoc=[];
        fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc); 
    }
    else{
        toastr["error"]("A conclusão manual apenas pode ser feita para linhas cuja Unidade=UNI!");
    }
}

function confirm_close(tblPL){
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/planos_carga/preparacao/ConclusaoManual/concluir_linha_manualmente",
        dataType: "json",
        data:{
            encomenda: tblPL
        },
        success: function (data) {
            if (data === "kick") {
                //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {
                toastr["success"]("Linha da encomenda concluída com sucesso");      
                let baseUrl = "http://127.0.0.1/wyipist-fusion/load_plans/load_preparation/";
                let dynamicUrl = baseUrl + serie + "/" + plano;
                // Redireciona para a URL construída
                window.location.href = baseUrl;
            }
        },
        error: function (e) {            
            //alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
            toastr["error"]("Erro ao concluir a linha da encomenda");                                   
        }
    });
}

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
            {title:"NovaQtd", field:"NovaQtd", align:"center", visible:false},   
            {title:"Calibre", field:"Calibre", align:"center", visible:false},                
            {title:"Sel", field:"Sel", align:"center", visible:false},
            {title:"Id", field:"Id", align:"center", visible:false}
        ]
    });

    setTimeout(function () {  
        $("#escolha_palete").modal("show");
        //$("#escolha_palete").modal();  
    }, 350);

    setTimeout(function () {            
        $('#paleteCB').trigger('focus');
    //    tablePaletes.redraw(true);            
    }, 850);
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
    if(user_type == 1){
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
    atualiza_qtdPL(selected);      
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

        if (user_type == 1 || count < 2) {
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

            if (user_type != 1) {
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

/*ATUALIZA QTDS*/
function atualiza_qtdPL(selected){    
    let row=tableLinha.getRowFromPosition(1);
    row.update({QtdPaletizada: 0});
    row.update({QtdFalta: 0});
    
    let k = tableLinha.getData()[0]['QtdPaletizada'];
    for (let i = 0; i < selected.length; i++) {
        k=k+selected[i]['NovaQtd'];
    }    
    
    row.update({QtdPaletizada: k});
    let j = tableLinha.getData()[0]['Quantidade'];
    x=j-k;
    row.update({QtdFalta: x});
}

/*GRAVAR DADOS NA BD*/
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