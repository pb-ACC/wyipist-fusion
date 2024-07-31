$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI); // para bloquear a pagina com o ajax load sempre que houver um pedido ajax

$("#menu-planos01").addClass("menu-is-opening menu-open");
$("#menu-planos02").addClass("active");
$('#menu-planos03').attr("style", "display: block;" );
$("#anula-carg01").addClass("active");
$("#anula-carg02").addClass("active");