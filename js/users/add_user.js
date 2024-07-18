let table;
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[];
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