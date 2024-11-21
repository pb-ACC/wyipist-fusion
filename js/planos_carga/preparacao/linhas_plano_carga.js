$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-planos01").addClass("menu-is-opening menu-open");
$("#menu-planos02").addClass("active");
$('#menu-planos03').attr("style", "display: block;" );
$("#prep-carg01").addClass("active");
$("#prep-carg02").addClass("active");

let tableplanoGG, tableSelplanoGG, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let planoCarga=[], planoCargaOG=[], marosca=[];

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/planos_carga/preparacao/GetLinhasGG/getLinhasGG/"+plano+"/"+serie,
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {    
            
            planoCargaOG=Object.values(data);
            linhasPlanoCarga(Object.values(data));

            tableplanoGG.clearAlert();
            $('#empresasDP').prop('disabled', false);

            toastr.clear();
            toastr["success"]("Planos de carga carregados com sucesso.");
            toastr.clear();       
  
            valida_fechaGG();

        }
    },
    error: function (e) {
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
});

function linhasPlanoCarga(data){    
    tableplanoGG= new Tabulator("#plano-carga-table", {
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
        columns:[
            {title:"Encomenda", field:"NumeroDocumento", align:"center", headerFilter:"input",width:140},  
            {title:"Referência", field:"Referencia", align:"center", headerFilter:"input",width:160},  
            {title:"Descrição", field:"DescricaoArtigo", align:"center", headerFilter:"input"},  
            {title:"Quantidade", field:"Quantidade", align:"center", headerFilter:"input",width:140},  
            {title:"Paletizado", field:"QtdPaletizada", align:"center", headerFilter:"input",width:140},
            {title:"Falta", field:"QtdFalta", align:"center", headerFilter:"input",width:140},
            {title:"Uni.", field:"Unidade", align:"center", headerFilter:"input", width:78},  
            {title:"Apontamentos", field:"Descricao", align:"center", headerFilter:"input",visible:false},
            {title:"Artigo", field:"Artigo", align:"center",sorter:"date", headerFilter:"input",visible:false},  
            {title:"LinhaEN", field:"NumeroLinha", align:"center", visible:false},
            {title:"NumeroLinha", field:"NumeroLinha", align:"center", visible:false},
            {title:"DocumentoCarga", field:"DocumentoCarga", align:"center", visible:false},
            {title:"Ordem", field:"Ordem", align:"center", visible:false}
        ]
    });
    tableplanoGG.on("rowClick", function(e, row){
        //e - the click event object
        //row - row component
        //alert(row.getData().DocumentoCarga);
        window.location.href = row.getData().DocumentoCarga+"/"+row.getData().NumeroDocumento+"/"+row.getData().NumeroLinha+"/"+row.getData().Referencia;
    });
}

function valida_fechaGG() {
    setTimeout(() => {
        totalQtd = 0, totalPL = 0 ; // Reinicializa totalQtd para evitar somas cumulativas

        tableplanoGG.getData().forEach(row => {
            let quantidade = parseFloat(row.Quantidade);            
            // Verifica se o valor é um número válido
            if (!isNaN(quantidade)) { 
                totalQtd += quantidade;                
            }

            let paletizado = parseFloat(row.QtdPaletizada);            
            // Verifica se o valor é um número válido
            if (!isNaN(paletizado)) { 
                totalPL += paletizado;                
            }
        });
        // Verifica se totalQtd e totalPL são iguais e exibe os alertas se forem
        if (totalQtd === totalPL) {
            type='success';
            title='Carga totalmente paletizada';
            text2='Pretende marcá-la como pronta?';
            action='gg_ready';
            xposition='center';
            tblPL=[];    
            tblLoc=[];
            tblLote=[]
            tblAfet=[];
            fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet); 
        } 
    }, 300);
}

function confirm_gg_ready(){
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/planos_carga/preparacao/PaletizarCarga/fecharGG/"+plano,
        dataType: "json",
        success: function (data) {
            if (data === "kick") {
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {                        
                window.history.back();           
            }
        },
        error: function (e) {
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }
    });
}
