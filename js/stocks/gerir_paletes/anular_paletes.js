$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-stocks01").addClass("menu-is-opening menu-open");
$("#menu-stocks02").addClass("active");
$('#menu-stocks03').attr("style", "display: block;" );
$("#menu-pls01").addClass("menu-is-opening menu-open");
$("#opcoes-pls01").addClass("menu-is-opening menu-open");
$('#opcoes-pls01').attr("style", "display: block;" );
$("#anula-pl01").addClass("active");
$("#anula-pl02").addClass("active");

let tablePaletes, tableSelPaletes, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let palets=[], paletsOG=[], marosca=[];
let no_change=0;
selectedPalets(data=[]);

toastr.clear();
toastr["info"]("A carregar paletes...");

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarPaletes/getPalets_anulaPL",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {                                            
            if (data['select'].length > 0) 
                $("#select").append(data['select']);                
                $('#empresasDP').select2();
            if (data['radio'].length > 0) 
                $("#radioButtons").append(data['radio']);                                    
            radioButtons()                 
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

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/standards/ListarMotivos/getMotivos_anula",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {
            //console.log(data);  
            // Limpa qualquer opção existente
            $('#anl').empty();        
            // Adiciona as novas opções
            for (let i = 0; i < data.length; i++) {
                let value = {
                    id: data[i]['Codigo'],
                    text: data[i]['Descricao']
                };        
                let option = new Option(value.text, value.id, false, false);
                $('#anl').append(option);
            }        
            // Inicializa o Select2
            $('#anl').select2();

            toastr.clear();
            toastr["success"]("Paletes carregadas com sucesso.");
            toastr.clear();
            $("#choose_palets").prop("disabled",false);  
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
            {title:"Lote", field:"Lote", align:"center",headerFilter:"input"},
            {title:"Nivel", field:"Nivel", align:"center", visible:false},                
            {title:"TabEspessura", field:"TabEspessura", align:"center", visible:false},                                
            {title:"Calibre", field:"Calibre", align:"center",headerFilter:"input"},
            {title:"Sel", field:"Sel", align:"center", visible:false},
            {title:"Local", field:"Local", align:"center",headerFilter:"input"},
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

function select_all_paletes(){        
    tablePaletes.selectRow("visible"); //select all rows currently visible in the table viewport;     
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
    action='cancel_palette';
    xposition='center';
    tblPL=tableSelPaletes.getData();
    tblLoc=[];
    tblLote=[];
    tblAfet=[];
    fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
}

/*RADIO BTNS*/
function radioButtons(){
    $('#radioButtons input:radio').click(function() {

        if ($(this).val() === '1') {
            sizeofTBL=tableSelPaletes.getData();        
            if(sizeofTBL.length>0){          
                type='success';
                title='Dados da tabela serão perdidos';
                text2='Tem a certeza que pretende continuar?';
                action='radioButtons';
                xposition='center';            
                campo='FB003';
                valor=this;
                tblPL=[];
                tblLoc=[];
                tblLote=[];
                tblAfet=[];
                fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
            }else{
                no_change=1;
                $('.form-check-input').prop('checked', false); // Unchecks it    
                $(this).prop('checked', true); // Checks it              
                newSector='FB003';  
                change_sector_emp(newSector);    
                no_change=0;                                
            }                
        }else if ($(this).val() === '2') {
            sizeofTBL=tableSelPaletes.getData();        
            if(sizeofTBL.length>0){            
                type='success';
                title='Dados da tabela serão perdidos';
                text2='Tem a certeza que pretende continuar?';
                action='radioButtons';
                xposition='center';            
                campo='CL001';
                valor=this;
                tblPL=[];
                tblLoc=[];
                tblLote=[];
                tblAfet=[];
                fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
            }else{
                no_change=1;
                $('.form-check-input').prop('checked', false); // Unchecks it    
                $(this).prop('checked', true); // Checks it             
                newSector='CL001';
                change_sector_emp(newSector);    
                no_change=0;                
            }                
        }else if ($(this).val() === '3') {
    
            sizeofTBL=tableSelPaletes.getData();        
            if(sizeofTBL.length>0){
                
                type='success';
                title='Dados da tabela serão perdidos';
                text2='Tem a certeza que pretende continuar?';
                action='radioButtons';
                xposition='center';            
                campo='ST555';
                valor=this;
                tblPL=[];
                tblLoc=[];
                tblLote=[];
                tblAfet=[];
                fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
            }else{
                no_change=1;
                $('.form-check-input').prop('checked', false); // Unchecks it    
                $(this).prop('checked', true); // Checks it             
                newSector='ST555';
                change_sector_emp(newSector); 
                no_change=0;                                   
            }                
        }else if ($(this).val() === '4') {
    
            sizeofTBL=tableSelPaletes.getData();        
            if(sizeofTBL.length>0){
                
                type='success';
                title='Dados da tabela serão perdidos';
                text2='Tem a certeza que pretende continuar?';
                action='radioButtons';
                xposition='center';            
                campo='FB001';
                valor=this;
                tblPL=[];
                tblLoc=[];
                tblLote=[];
                tblAfet=[];
                fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
            }else{
                no_change=1;
                $('.form-check-input').prop('checked', false); // Unchecks it    
                $(this).prop('checked', true); // Checks it             
                newSector='FB001';
                change_sector_emp(newSector); 
                no_change=0;                                   
            }                
        }                  
    });    
}

function change_sector_emp(newSector){
    // Obter e ajustar o nome da empresa selecionada
    emp = $.trim($("#empresasDP option:selected").text()).toUpperCase();
    // Verificar se há uma empresa selecionada
    if (emp == '') {
        // Dependendo do código da empresa, atribuir valores padrão
        if (codigoempresa == 1) {
            emp = "CERAGNI";
        } else if (codigoempresa == 2) {
            emp = "CERTECA";
        } else {
            emp = "CERAGNI";
        }
    } 
    
    toastr.clear();
    toastr["info"]("A carregar paletes...");
    tableSelPaletes.alert("A processar...");
    $('#empresasDP').prop('disabled', true);
    $("#buttons button").attr("disabled", true);
    $("input[type=radio]").attr('disabled', true);

    $("#open_picking_modal").prop("disabled",true);
    
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_anula/"+emp+"/"+newSector,
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
                $("input[type=radio]").attr('disabled', false);

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
    tableSelPaletes.alert("A processar...");
    $('#empresasDP').prop('disabled', true);
    $("#buttons button").attr("disabled", true);
    $("input[type=radio]").attr('disabled', true);

    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/standards/ListarFiltro/filtraPlZn_emanuel_anula/"+emp+"/"+newSector,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {    

                paletsOG=Object.values(data['paletes']);
                getPalets(Object.values(data['paletes']));

                    $("#radioButtons").empty();
                    if (data['radio'].length > 0)                         
                        $("#radioButtons").append(data['radio']); 
                    radioButtons();                    

                    if (data['button'].length > 0) 
                        $("#buttons").empty();
                        $("#buttons").append(data['button']);                         

                        tableSelPaletes.clearAlert();
                        $('#empresasDP').prop('disabled', false);
                        $("#buttons button").attr("disabled", false);
                        $("input[type=radio]").attr('disabled', false);
        
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
function confirm_cancellation(tblPL){
    
    tableSelPaletes.alert("A gravar...");
    $('#empresasDP').prop('disabled', true);
    $("#buttons button").attr("disabled", true);   
    $("input[type=radio]").attr('disabled', true);
    $("#save_anulacao").prop('disabled', true);

    anll = $("#anl option:selected").text();
    anula = $.trim(anll);
    $("#save_anulacao").prop("disabled",true);  
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/stocks/gerir_paletes/Gravar_AnulacaoPalete/save_cancellation",
        dataType: "json",
        data:{
            palete: tblPL,
            motivo_anula: anula,
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