let table;
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[], tblLote=[], tblAfet=[];
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

$("#menu-gestao01").addClass("menu-is-opening menu-open");
$("#menu-gestao02").addClass("active");
$("#menu-gestao02").addClass("active");
$('#menu-user01').attr("style", "display: block;" );
$("#menu-user02").addClass("menu-is-opening menu-open");
$('#opcoes-user01').attr("style", "display: block;" );
$("#adicionar-user01").addClass("active");
$("#adicionar-user02").addClass("active");

$("#show_hide_password a").on('click', function(event) {
    event.preventDefault();
    if($('#show_hide_password input').attr("type") == "text"){
        $('#show_hide_password input').attr('type', 'password');
        $('#show_hide_password i').addClass( "fa-eye-slash" );
        $('#show_hide_password i').removeClass( "fa-eye" );
    }else if($('#show_hide_password input').attr("type") == "password"){
        $('#show_hide_password input').attr('type', 'text');
        $('#show_hide_password i').removeClass( "fa-eye-slash" );
        $('#show_hide_password i').addClass( "fa-eye" );
    }
});

$('#userForm').on('submit', function(event) {
    event.preventDefault();
    var formData = new FormData(this);
    $.ajax({
        url: 'http://127.0.0.1/wyipist-fusion/users/ManageUsers/addUsers',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            var data = JSON.parse(response);
            toastr[data.type](data.text);
            if(data.type === 'success'){
                $('#userForm')[0].reset();
            }
        },
        error: function(xhr, status, error) {
            toastr['error']('Ocorreu um erro ao gravar novo utilizador.');
        }
    });
});

document.getElementById("file").onclick = function () {

    $("#file").fileupload({
        url: "http://127.0.0.1/wyipist-fusion/standards/FileUpload/uploadEditUtilizador",
        //dataType:'json',
        autoUpload:'false',
    }).bind('fileuploadadd', function(e, data){

        var fileTypeAllowed = /.\.(jpg|png|jpeg)$/i;
        var fileName=data.files[0]["name"];
        var fileSize=data.files[0]["size"];

        if(!fileTypeAllowed.test(fileName))
            //fire_annotation_error2("Apenas podem ser carregados ficheiros de imagem!!!!","myAlertErrorUpload");
            toastr["error"]("Apenas podem ser carregados ficheiros de imagem!!!!");
        else if (fileSize>5000000)
            //fire_annotation_error2("Ficheiro demasiado Grande!!!! Máximo permitido 5MB","myAlertErrorUpload");
            toastr["error"]("Ficheiro demasiado Grande!!!! Máximo permitido 5MB");
        else{
            //$.post("<?php echo base_url();?>fileUpload_controller/", {codigo:codigo2, numeroDoc:NumeroDocumento2});
            data.submit();
            //console.log(data);
        }
    }).bind('fileuploaddone', function(e,data){

        var responseText2 = data.jqXHR.responseText;
        var jsonData=JSON.parse(responseText2);
        var status=jsonData.status;
        var path=jsonData.path1;
        //$("#bodyImagens").empty();
        //console.log(path);
       insertUserImageBD(path);
        //getImagens(codigo2,NumeroDocumento2,linhaTabela2,tipo2);

        if(status==1){
        }

    }).bind('fileuploadprogressall', function(e,data){
        var progresso=parseInt(data.loaded / data.total * 100);
        //fire_annotation_success2(progresso + " % Completo","myAlertSuccessAdicionar");
        toastr["success"](progresso + " % Completo");
    });
};

function insertUserImageBD(file){

    $.ajax({
        type: "POST",
        url: 'http://127.0.0.1/wyipist-fusion/users/ManageUsers/addUsersImageBD',
        dataType: "json",
        data: {
            file:file
        },
        success: function (data) {          
            if (data === "kick") {
                //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {

            }
        },
        error: function (e) {
            //alert(e);
            alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
            console.log(e);
        }

    });
}
