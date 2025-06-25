$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-comer01").addClass("menu-is-opening menu-open");
$("#menu-comer02").addClass("active");
$('#menu-comer03').attr("style", "display: block;" );
$("#flash01").addClass("active");
$("#flash02").addClass("active");

let tableplanoGG, tableSelplanoGG, tableLocal_fabric, tableLocal_logistic, tableLocal_warehouse, selectedData=[], OG, dt, dtt, count=0, count2=0, count3=0, count4=0, local='';
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
let planoCarga=[], planoCargaOG=[], marosca=[];

$.ajax({
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/comercial/flash/TerminalFlash/getStock_company/"+aVer,
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {                                                       
            console.log(data['tbl']);
        }
    },
    error: function (e) {
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
});