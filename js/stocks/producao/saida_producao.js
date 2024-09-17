$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-stocks01").addClass("menu-is-opening menu-open");
$("#menu-stocks02").addClass("active");
$('#menu-stocks03').attr("style", "display: block;" );
$("#saida-prod01").addClass("active");
$("#saida-prod02").addClass("active");

let tablePaletes, tableSelPaletes, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let palets=[], paletsOG=[], marosca=[];
selectedPalets(data=[]);

toastr.clear();
toastr["info"]("A carregar paletes...");
//$("#choose_palets").prop("disabled",true);

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarPaletes/getPalets",
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
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarSetores/getZonas",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {
            dt = Object.values(data);
            dtt = Object.values(data);
            listLocal(Object.values(data));
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
            {title:"UNI.", field:"Unidade", align:"center", visible:false},
            {title:"Sector", field:"Sector", align:"center", visible:false},                
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
            {title:"UNI.", field:"Unidade", align:"center", visible:false},
            {title:"Sector", field:"Sector", align:"center", visible:false},                
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

/*ZONAS*/
function listLocal(data){
       
    dataFB=data;
    for (let i = 0; i < dataFB.length; i++) {
        if(dataFB[i]['Identificador'] === 'CL'){ 
            dataFB.splice(i, 1);
            i--; // Ajustar o índice após a remoção
        }                        
    }    
    tableLocal_fabric= new Tabulator("#local-table-fabric", {
        data:dataFB, //assign data to table            
        selectableRows:true, //make rows selectable
        headerSort:false, //disable header sort for all columns
        placeholder:"Sem Dados Disponíveis",   
        pagination:"local",
            paginationSize:25,
            paginationSizeSelector:[25,50,75,100],
        layout:"fitColumns", //fit columns to width of table (optional)
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
            {title:"Zona", field:"Zona", align:"center",visible:true,headerFilter:"input"},
            {title:"Celula", field:"Celula", align:"center",visible:true,headerFilter:"input"},
            {title:"CodigoBarras", field:"CodigoBarras", align:"center",visible:true,headerFilter:"input"},      
            {title:"Sector", field:"Sector", align:"center", visible:false},
            {title:"Identificador", field:"Identificador", align:"center", visible:false},
            {title:"Sel", field:"Sel", align:"center", visible:false},
            {title:"id", field:"id", align:"center", visible:false}
        ]
    });
    
    dataCL=dt;
    for (let i = 0; i < dataCL.length; i++) {
            if(dataCL[i]['Identificador'] === 'FB'){ 
                dataCL.splice(i, 1);
                i--; // Ajustar o índice após a remoção
            }                        
        }
        tableLocal_logistic= new Tabulator("#local-table-logistic", {
            data:dataCL, //assign data to table            
            selectableRows:true, //make rows selectable
            headerSort:false, //disable header sort for all columns
            placeholder:"Sem Dados Disponíveis",   
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
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
                {title:"Zona", field:"Zona", align:"center",visible:true,headerFilter:"input"},
                {title:"Celula", field:"Celula", align:"center",visible:true,headerFilter:"input"},
                {title:"CodigoBarras", field:"CodigoBarras", align:"center",visible:true,headerFilter:"input"},  
                {title:"Sector", field:"Sector", align:"center", visible:false},    
                {title:"Identificador", field:"Identificador", align:"center", visible:false},
                {title:"Sel", field:"Sel", align:"center", visible:false},
                {title:"id", field:"id", align:"center", visible:false}
            ]
        });     

    
    dataAR=dtt;
    for (let i = 0; i < dataAR.length; i++) {
            if(dataAR[i]['Identificador'] != 'CT'){ 
                dataAR.splice(i, 1);
                i--; // Ajustar o índice após a remoção
            }                        
        }
        tableLocal_warehouse= new Tabulator("#local-table-warehouse", {
            data:dataAR, //assign data to table            
            selectableRows:true, //make rows selectable
            headerSort:false, //disable header sort for all columns
            placeholder:"Sem Dados Disponíveis",   
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
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
                {title:"Armazém", field:"Sector", align:"center",visible:true,headerFilter:"input"},
                {title:"Setor", field:"Codigo", align:"center",visible:true,headerFilter:"input"},
                {title:"Fila", field:"Fila", align:"center",visible:true,headerFilter:"input"},
                {title:"Posicao", field:"Posicao", align:"center",visible:true,headerFilter:"input"},
                {title:"CodigoBarras", field:"CodigoBarras", align:"center",visible:true,headerFilter:"input"},   
                {title:"Identificador", field:"Identificador", align:"center", visible:false},
                {title:"Sel", field:"Sel", align:"center", visible:false},
                {title:"id", field:"id", align:"center", visible:false}
            ]
        });     
}

function pick_local_fabric() {
    let rows = tableLocal_fabric.searchRows("CodigoBarras", "=", $("#localCB").val());

    if (rows.length > 0) {
        let tbl = rows[0].getData();
        let localOG = tableLocal_fabric.getData();
        let picadas = [];

        if (user_type == 1 || count2 < 1) {
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
            tableLocal_fabric.setData(reorderedData);

            // Limpar seleção atual    
            tableLocal_fabric.deselectRow();

            // Selecionar apenas as paletes marcadas como selecionadas
            setTimeout(() => { // Adicionado um pequeno delay para garantir que a tabela seja renderizada antes de selecionar as linhas
                tableLocal_fabric.getData().forEach(local => {
                    if (local.Sel == 1) {
                        tableLocal_fabric.selectRow(local.id); // Selecionar a linha da palete
                    }
                });
            }, 100);

            tableLocal_fabric.clearFilter(true);
            $("#localCB").val('');

            if (user_type != 1) {
                count2++;
            }
        } else {
            toastr["error"]("Só pode picar um local!");
        }
    } else {
        toastr["error"]("Localização inexistente!");
        $("#localCB").val('');
        $('#localCB').trigger('focus');    
    }
}

function pick_local_logistic() {
    let rows = tableLocal_logistic.searchRows("CodigoBarras", "=", $("#localCB").val());

    if (rows.length > 0) {
        let tbl = rows[0].getData();
        let localOG = tableLocal_logistic.getData();
        let picadas = [];

        if (user_type == 1 || count3 < 1) {
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
            tableLocal_logistic.setData(reorderedData);

            // Limpar seleção atual    
            tableLocal_logistic.deselectRow();

            // Selecionar apenas as paletes marcadas como selecionadas
            setTimeout(() => { // Adicionado um pequeno delay para garantir que a tabela seja renderizada antes de selecionar as linhas
                tableLocal_logistic.getData().forEach(local => {
                    if (local.Sel == 1) {
                        tableLocal_logistic.selectRow(local.id); // Selecionar a linha da palete
                    }
                });
            }, 100);

            tableLocal_logistic.clearFilter(true);
            $("#localCB").val('');

            if (user_type != 1) {
                count3++;
            }
        } else {
            toastr["error"]("Só pode picar um local!");
        }
    } else {
        toastr["error"]("Localização inexistente!");
        $("#localCB").val('');
        $('#localCB').trigger('focus');    
    }
}

function pick_local_warehouse() {
    let rows = tableLocal_warehouse.searchRows("CodigoBarras", "=", $("#localCB").val());

    if (rows.length > 0) {
        let tbl = rows[0].getData();
        let localOG = tableLocal_warehouse.getData();
        let picadas = [];

        if (user_type == 1 || count4 < 1) {
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
            tableLocal_warehouse.setData(reorderedData);

            // Limpar seleção atual    
            tableLocal_warehouse.deselectRow();

            // Selecionar apenas as paletes marcadas como selecionadas
            setTimeout(() => { // Adicionado um pequeno delay para garantir que a tabela seja renderizada antes de selecionar as linhas
                tableLocal_warehouse.getData().forEach(local => {
                    if (local.Sel == 1) {
                        tableLocal_warehouse.selectRow(local.id); // Selecionar a linha da palete
                    }
                });
            }, 100);

            tableLocal_warehouse.clearFilter(true);
            $("#localCB").val('');

            if (user_type != 1) {
                count4++;
            }
        } else {
            toastr["error"]("Só pode picar um local!");
        }
    } else {
        toastr["error"]("Localização inexistente!");
        $("#localCB").val('');
        $('#localCB').trigger('focus');    
    }
}

function clearLocal_fabric(){
    tableLocal_fabric.deselectRow();
    count2=0;
}

function clearLocal_logistic(){
    tableLocal_logistic.deselectRow();
    count3=0;
}

function clearLocal_warehouse(){
    tableLocal_warehouse.deselectRow();
    count4=0;
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

function send_to_factory(){

    sizeofTBL=tableSelPaletes.getData();   
    if(sizeofTBL.length>0){
        setTimeout(function () {  
            $("#escolha_local_fab").modal("show");
        }, 350);

        setTimeout(function () {            
            $('#localCB').trigger('focus');  
        }, 850);
    }
    else{
        toastr["error"]("Não foi picada nenhuma palete!");
    }
}

function send_to_logistic(){
    sizeofTBL=tableSelPaletes.getData();   

    if(sizeofTBL.length>0){
        setTimeout(function () {  
            $("#escolha_local_log").modal("show");             
        }, 350);

        setTimeout(function () {            
            $('#localCB_logistic').trigger('focus');       
        }, 850);
    }
    else{
        toastr["error"]("Não foi picada nenhuma palete!");
    }
}

function send_to_warehouse(){

    sizeofTBL=tableSelPaletes.getData();   
    if(sizeofTBL.length>0){
        setTimeout(function () {  
            $("#escolha_local_arm").modal("show");
        }, 350);

        setTimeout(function () {            
            $('#localCB').trigger('focus');  
        }, 850);
    }
    else{
        toastr["error"]("Não foi picada nenhuma palete!");
    }
}

function save_local_fabric(){
    type='success';
    title='Tem a certeza que pretende continuar?';
    text2='';
    action='save_local_fabric';
    xposition='center';
    tblPL=tableSelPaletes.getData();    
    
    let selected=[];
    sel = tableLocal_fabric.getSelectedData();
    if(sel.length>0){
        selected=sel;
    }
    else{        
        let sel = tableLocal_fabric.getData();        
        for (let i = 0; i < sel.length; i++) {
            if (sel[i]['Sel'] == 1) {
                selected.push(sel[i]);
            }
        }
    }
    tblLoc=selected;

    tblLote=[];
    tblAfet=[];
    fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
}

function save_local_logistic(){
    
    type='success';
    title='Tem a certeza que pretende continuar?';
    text2='';
    action='save_local_logistic';
    xposition='center';
    tblPL=tableSelPaletes.getData();    
    
    let selected=[];
    sel = tableLocal_logistic.getSelectedData();
    if(sel.length>0){
        selected=sel;
    }
    else{        
        let sel = tableLocal_logistic.getData();        
        for (let i = 0; i < sel.length; i++) {
            if (sel[i]['Sel'] == 1) {
                selected.push(sel[i]);
            }
        }
    }
    tblLoc=selected;

    tblLote=[];
    tblAfet=[];
    fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
}

function save_local_warehouse(){
    
    type='success';
    title='Tem a certeza que pretende continuar?';
    text2='';
    action='save_local_warehouse';
    xposition='center';
    tblPL=tableSelPaletes.getData();    
    
    let selected=[];
    sel = tableLocal_warehouse.getSelectedData();
    if(sel.length>0){
        selected=sel;
    }
    else{        
        let sel = tableLocal_warehouse.getData();        
        for (let i = 0; i < sel.length; i++) {
            if (sel[i]['Sel'] == 1) {
                selected.push(sel[i]);
            }
        }
    }
    tblLoc=selected;
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
        tblLote=[];
        tblAfet=[];
        fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
    }else{
        confirm_changeEmpresa();
    }
}

function confirm_changeEmpresa(){

    //empp = $("#empresasDP option:selected").text();
    //emp = $.trim(empp);  
    
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


    //alert(emp.toUpperCase());
    //$("#choose_palets").prop("disabled",true);    
    toastr.clear();
    toastr["info"]("A carregar paletes...");
    tableSelPaletes.alert("A processar...");
    $('#empresasDP').prop('disabled', true);
    $("#buttons button").attr("disabled", true);    
    
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn/"+emp,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {    
                
                paletsOG=Object.values(data['paletes']);
                getPalets(Object.values(data['paletes']));
                
                
                    dt = Object.values(data['zonas']);
                    dtt = Object.values(data['zonas']);
                    listLocal(Object.values(data['zonas']));

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

/*GRAVAR DADOS NA BD*/
function confirm_save(tblPL,tblLoc){
    empp = $("#empresasDP option:selected").text();
    emp = $.trim(empp);    

    if (user_type == 1 || user_type == 2){
        empp = $("#empresasDP option:selected").text();
        emp = $.trim(empp);  
        if(emp === 'Certeca'){
            newSector=tblLoc[0]['Sector'];
        }else{
            newSector='ST015';
        }
    }
    else{
        if(codigoempresa == 1){
            newSector='ST015';
        }else{
            newSector=tblLoc[0]['Sector'];
        }
    }  

    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/stocks/producao/Gravar_SaidaProducao/save_production",
        dataType: "json",
        data:{
            palete: tblPL,
            //setor: newSector,
            setor: tblLoc[0]['Sector'],
            local: tblLoc[0]['CodigoBarras']
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