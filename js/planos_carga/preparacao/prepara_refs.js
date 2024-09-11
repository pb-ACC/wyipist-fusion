$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-planos01").addClass("menu-is-opening menu-open");
$("#menu-planos02").addClass("active");
$('#menu-planos03').attr("style", "display: block;" );
$("#prep-carg01").addClass("active");
$("#prep-carg02").addClass("active");

let tablePaletes, tableSelPaletes, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, tableLinha, tableAfetada, tableLotes, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
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
            linha_afetada(Object.values(data['linha_afetada']));
            paletsOG=Object.values(data['linha_afetada']);
            lotes_gastos(Object.values(data['lotes_gastos']));
            marosca=Object.values(data['lotes_gastos']);
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

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarMotivos/getMotivo_stock",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {
            //console.log(data);  
            let select = $('#mt-stk');    
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

function linha_afetada(data){
    tableAfetada= new Tabulator("#afetado_encomenda-table", {
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
            {title:"Lote", field:"Lote", align:"center", headerFilter:"input"},  
            {title:"Calibre", field:"Calibre", align:"center", headerFilter:"input"},  
            {title:"Quantidade", field:"Quantidade", align:"center", headerFilter:"input",bottomCalc:"sum", bottomCalcParams:{precision:2}},
            {title:"Unidade", field:"Unidade", align:"center", headerFilter:"input"},
            {title:"LinhaDocumento", field:"LinhaDocumento", align:"center", visible:false},
            {title:"Documento", field:"Documento", align:"center", visible:false},
            {title:"Ordem", field:"Ordem", align:"center", visible:false},
            {title:"NumSeq", field:"NumSeq", align:"center", visible:false}
        ]
    });
}

function lotes_gastos(data){
    tableLotes= new Tabulator("#lotes_consumidos-table", {
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
            {title:"Lote", field:"Lote", align:"center", headerFilter:"input"},  
            {title:"Calibre", field:"Calibre", align:"center", headerFilter:"input"},  
            {title:"Quantidade", field:"Quantidade", align:"center", headerFilter:"input",bottomCalc:"sum", bottomCalcParams:{precision:2}},
            {title:"Unidade", field:"Unidade", align:"center", headerFilter:"input"}
            
        ]
    });
}

function selectedPalets(data){
    let deleteUser = function(cell, formatterParams){ //plain text value
        return "<i class='fas fa-trash-alt' style='color: red' title='Remover'></i>";
    };

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
            {title:"Lote", field:"Lote", align:"center"},
            {title:"Calibre", field:"Calibre", align:"center"},                
            {title:"QTD.", field:"Quantidade", align:"center",bottomCalc:"sum", bottomCalcParams:{precision:2}},
            {title:"QTD. a Usar", field:"NovaQtd",  hozAlign:"center", editor:"input",bottomCalc:"sum", bottomCalcParams:{precision:2},formatter:function (cell) {
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

                // 2. Combine ogData and selected into a single array
                let combinedArray = [...marosca, ...tableSelPaletesData];    
               // alert(combinedArray);            
                // 3. Create an object to store the grouped and summed data
                let groupedData = {};
                combinedArray.forEach(row => {
                    let key = row.Lote + '_' + row.Calibre; // Create a unique key based on "lote" and "calibre"
                    if (!groupedData[key]) {
                        groupedData[key] = {
                            Lote: row.Lote,
                            Calibre: row.Calibre,
                            Quantidade: 0,
                            Unidade: row.Unidade,
                        };
                    }
                    // 3. Sum the values
                    groupedData[key].Quantidade = parseFloat(groupedData[key].Quantidade) + parseFloat(row.NovaQtd);
                });
                
                // Convert groupedData object back to an array if needed
                let result = Object.values(groupedData);
                
                // Now 'result' contains the summed values grouped by "lote" and "calibre"
                lotes_gastos(result);
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
            {title:"Nivel", field:"Nivel", align:"center", visible:false},
            {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},                                            
            {title:"Sel", field:"Sel", align:"center", visible:false},
            {title:"Id", field:"Id", align:"center", visible:false},
            {title:" ",formatter:deleteUser, width:50, align:"center",tooltip:true, cellClick:function(e, cell){
                let row01 = cell.getRow();
                // Deleta a linha
                row01.delete();

                let editedData = cell.getData();
            
                // Inicializar o valor QtdPaletizada em tableLinha
                let row02 = tableLinha.getRowFromPosition(1); // Ajuste para a linha específica conforme necessário
                row02.update({ QtdPaletizada: 0 });
                row02.update({ QtdFalta: 0 });
            
                // Calcular o novo valor de QtdPaletizada
                let k = 0;
                let tableSelPaletesData = tableSelPaletes.getData(); // Obter todos os dados de tableSelPaletes
                for (let i = 0; i < tableSelPaletesData.length; i++) {
                    k = parseFloat(k) + parseFloat(tableSelPaletesData[i]['NovaQtd']);
                }
            
                // Atualizar a linha em tableLinha com o novo valor de QtdPaletizada
                row02.update({QtdPaletizada: k});
                row02.update({QtdFalta: parseFloat(tableLinha.getData()[0]['Quantidade']) - parseFloat(tableLinha.getData()[0]['QtdPaletizada']) });    

                // 2. Combine ogData and selected into a single array
                let combinedArray = [...marosca, ...tableSelPaletesData];                
                // 3. Create an object to store the grouped and summed data
                let groupedData = {};
                combinedArray.forEach(row => {
                    let key = row.Lote + '_' + row.Calibre; // Create a unique key based on "lote" and "calibre"
                    if (!groupedData[key]) {
                        groupedData[key] = {
                            Lote: row.Lote,
                            Calibre: row.Calibre,
                            Quantidade: 0,
                            Unidade: row.Unidade,
                        };
                    }
                    // 3. Sum the values
                    groupedData[key].Quantidade = parseFloat(groupedData[key].Quantidade) + parseFloat(row.NovaQtd);
                });
                
                // Convert groupedData object back to an array if needed
                let result = Object.values(groupedData);
                
                // Now 'result' contains the summed values grouped by "lote" and "calibre"
                lotes_gastos(result);

            }}
        ]
    });
    
}

/*BTNS*/
function choose_palets(){  
    toastr.clear();
    toastr["info"]("A carregar paletes...");  
    $(".card-footer .row .btn").prop("disabled", true);
    $(".card-header .btn").prop("disabled", true);

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
                $(".card-footer .row .btn").prop("disabled", false);
                $(".card-header .btn").prop("disabled", false);
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

function paletizar(){
    if (parseFloat(tableLinha.getData()[0]['QtdPaletizada']) <= 0) {
        toastr["error"]("Não colocou quantidades a paletizar!");
    } else if (parseFloat(tableLinha.getData()[0]['QtdPaletizada']) > parseFloat(tableLinha.getData()[0]['Quantidade'])) {
        toastr["error"]("Não pode colocar quantidades superiores à existente!");
    } else if (parseFloat(tableLinha.getData()[0]['QtdPaletizada']) < 0) {
        toastr["error"]("Não pode colocar quantidades negativas!");
    } else {
        segue_para_paletizar();
    }    
}

function segue_para_paletizar(){
    type='success';
    title='Tem a certeza que pretende continuar?';
    text2='';
    action='segue_para_paletizar';
    xposition='center';
    tblPL=tableSelPaletes.getData();    
    tblLoc=tableLinha.getData();
    tblLote=tableLotes.getData();
    tblAfet=tableAfetada.getData();
    fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
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
            {title:"Referencia", field:"Referencia", align:"center",headerFilter:"input"},
            {title:"Artigo", field:"Artigo", align:"center", visible:false},
            {title:"Descrição", field:"DescricaoArtigo", align:"center",headerFilter:"input"},
            {title:"Lote", field:"Lote", align:"center",headerFilter:"input"},
            {title:"Calibre", field:"Calibre", align:"center",headerFilter:"input"},
            {title:"QTD.", field:"Quantidade", align:"center"},
            {title:"UNI.", field:"Unidade", align:"center"},
            {title:"Sector", field:"Sector", align:"center",headerFilter:"input"},
            {title:"Formato", field:"Formato", align:"center", visible:false},
            {title:"Qual", field:"Qual", align:"center", visible:false},
            {title:"TipoEmbalagem", field:"TipoEmbalagem", align:"center", visible:false},
            {title:"Superficie", field:"Superficie", align:"center", visible:false},
            {title:"Decoracao", field:"Decoracao", align:"center", visible:false},
            {title:"RefCor", field:"RefCor", align:"center", visible:false},
            {title:"Nivel", field:"Nivel", align:"center", visible:false},
            {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},   
            {title:"NovaQtd", field:"NovaQtd", align:"center", visible:false},   
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
    atualiza_qtdPL(selected);      
    atualiza_lotesConsumidos(selected);      
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

function atualiza_lotesConsumidos(selected){
    // 2. Combine ogData and selected into a single array
    let combinedArray = [...marosca, ...selected];
    // 3. Create an object to store the grouped and summed data
    let groupedData = {};
    combinedArray.forEach(row => {
        let key = row.Lote + '_' + row.Calibre; // Create a unique key based on "lote" and "calibre"
        if (!groupedData[key]) {
            groupedData[key] = {
                Lote: row.Lote,
                Calibre: row.Calibre,
                Quantidade: 0,
                Unidade: row.Unidade,
            };
        }
        // 3. Sum the values
        groupedData[key].Quantidade = parseFloat(groupedData[key].Quantidade) + parseFloat(row.Quantidade);
    });
    
    // Convert groupedData object back to an array if needed
    let result = Object.values(groupedData);
    
    // Now 'result' contains the summed values grouped by "lote" and "calibre"
    lotes_gastos(result);
}

/*GRAVAR DADOS NA BD*/
function confirm_paletizar(tblPL,tblLoc,tblLote,tblAfet){    

    if (tblAfet.length > 0) {        
        tblLote.forEach(consumidoRow => {
            let lote = consumidoRow.Lote;
            let calibre = consumidoRow.Calibre;

            // Check if lote and calibre exist in "Paletes a Paletizar" and "Lotes Afetados"
            let existsInPaletes = tblPL.some(paleteRow => paleteRow.Lote === lote && paleteRow.Calibre === calibre);
            let existsInLotesAfetados = tblAfet.some(loteAfetadoRow => loteAfetadoRow.Lote === lote && loteAfetadoRow.Calibre === calibre);

            if (!existsInPaletes || !existsInLotesAfetados) {
                
                $('#current_count').text(0);

                $('#obs-stk').keyup(function() {                        
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
            
                // Handle the mismatch - for example, by logging it or highlighting the row                
                setTimeout(function () {
                    $("#lotes_diff").modal();
                }, 350);
        
                setTimeout(function () {
                    $('#obs-stk').trigger('focus');
                }, 850);
            }
        });    
    }else{
        save_palletize();
    }
}

function save_palletize(){    

    let obs = document.getElementById('obs-stk').value;
    let codigomotivo = $("#mt-stk").val();

    if(obs != '' && codigomotivo == 'MT999'){
        $(".card-footer .row").hide();
        // Inicializa a flag com um valor padrão, por exemplo, 0
        let flag = 0;
        // Obtém o valor do cliente
        let cliente = tableLinha.getData()[0]['Cliente'];
        // Verifica se o valor contém "Certeca" ou "Ceragni" e define a flag
        if (cliente.includes("Ceragni")) {
            flag = 1;
        } else if (cliente.includes("Certeca")) {
            flag = 2;
        }    
        mtt = $("#mt-stk option:selected").text();
        mt = $.trim(mtt);    

        if(serie === 'CG'){
            seriePL='PLC';
            setorDestino='ST012';
            setorCarga='CL007';
        }else{
            seriePL='PLPC';
            setorDestino='CL006';
            setorCarga='ST017';
        }
        
        $.ajax({
            type: "POST",
            url: "http://127.0.0.1/wyipist-fusion/planos_carga/preparacao/PaletizarCarga/paletizar_carga/"+serie+"/"+flag,
            dataType: "json",
            data:{
                encomenda: tblLoc,
                paletes: tblPL,
                motivo: mt,
                codigomotivo: codigomotivo,
                obs: obs,
                serie: serie,
                seriePL: seriePL,
                setorDestino: setorDestino,
                setorCarga: setorCarga 
            },
            success: function (data) {
                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {
                    toastr["success"]("Dados gravados com Sucesso");                    
                    $(".card-footer .row").show();
                    if(parseFloat(tableLinha.getData()[0]['Quantidade']) === parseFloat(tableLinha.getData()[0]['QtdPaletizada'])){
                        // Construct the URL based on the PHP code you provided
                        window.history.back();
                    }else{
                        location.reload();
                    }                
                }
            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                toastr["error"]("Erro ao gravar dados");    
                //console.log(e);
            }
        });
    }else{
        toastr["error"]("Ao escolher o motivo 'Outro' tem de preencher Observações!");    
    }
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