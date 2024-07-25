$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-stocks01").addClass("menu-is-opening menu-open");
$("#menu-stocks02").addClass("active");
$('#menu-stocks03').attr("style", "display: block;" );
$("#menu-mov01").addClass("menu-is-opening menu-open");
$("#opcoes-mov01").addClass("menu-is-opening menu-open");
$('#opcoes-mov01').attr("style", "display: block;" );
$("#rec_mat01").addClass("active");
$("#rec_mat02").addClass("active");
