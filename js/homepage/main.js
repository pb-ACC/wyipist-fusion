$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

$.ajax({                
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/users/GetUsers/countUsers",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {        
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {            
            $("#totUsers").html(data[0]['total']);            
        }
    },
    error: function (e) {
        alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
        console.log(e);
    }
});

//força o reload da página de 5 em 5 minutos        
setTimeout(() => {
    document.location.reload();
}, 500000);