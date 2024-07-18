let table;
let type='', title='', text='', text1='', text2='', action='', xposition='', campo='',valor='',tblPL=[], tblLoc=[];
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

$("#menu-gestao01").addClass("menu-is-opening menu-open");
$("#menu-gestao02").addClass("active");
$("#menu-gestao02").addClass("active");
$('#menu-user01').attr("style", "display: block;" );
$("#menu-user02").addClass("menu-is-opening menu-open");
$('#opcoes-user01').attr("style", "display: block;" );
$("#consultar-user01").addClass("active");
$("#consultar-user02").addClass("active");

$.ajax({                
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/users/GetUsers/getUsers",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {        
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {            
            listUsers(data);
        }
    },
    error: function (e) {
        alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
        console.log(e);
    }
});

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

function listUsers(data){
        let editUser = function(cell, formatterParams){ //plain text value
            return "<i class='fas fa-edit' style='color: blue' title='Editar'></i>";
        };
        let deleteUser = function(cell, formatterParams){ //plain text value
            return "<i class='fas fa-trash-alt' style='color: red' title='Remover'></i>";
        };

        table = new Tabulator("#users-table", {
            data:data, //assign data to table                 
            //selectableRows:true, //make rows selectable
            headerSort:false, //disable header sort for all columns
            placeholder:"Sem Dados Disponíveis",   
            layout:"fitColumns",      //fit columns to width of table
            responsiveLayout:"hide",  //hide columns that don't fit on the table        
            pagination:"local",       //paginate the data
            paginationSize:15,         //allow 7 rows per page of data        
            columnDefaults:{
                tooltip:true,         //show tool tips on cells
            },
            columns:[
                {title:"#", field:"id", width:"5%", align:"center"},
                {title:"User", field:"username", align:"center"},
                {title:"Nome", field:"Nome", align:"center"},
                {title:"E-mail", field:"email", align:"center"},
                {title:"Telefone", field:"telefone", align:"center"},
                {title:"Tipo", field:"type", align:"center"},
                {title:"id_tipo", field:"type_id", align:"center", visible:false},
                {title:"Func. GPAC", field:"funcionario_gpac", align:"center"},
                {title:"passwd", field:"passwd", align:"center", visible:false},
                {title:"client", field:"client", align:"center", visible:false},
                {title:" ",formatter:editUser, width:50, align:"center",tooltip:true,
                    cellClick:function(e, cell){editUserDetails(cell.getRow().getData().id,cell.getRow().getData().type_id,cell.getRow().getData().Nome,cell.getRow().getData().telefone,
                        cell.getRow().getData().email,cell.getRow().getData().username,cell.getRow().getData().client,cell.getRow().getData().funcionario_gpac)}},
                {title:" ",formatter:deleteUser, width:50, align:"center",tooltip:true, cellClick:function(e, cell){deleteUserID(cell.getRow().getData().id)}}

            ]
        });
}

function editUserDetails(id,type_id,name,phone,email,user,client,user_gpac){

    $("#id_user").val(id);
    $("#name").val(name);
    $("#phone").val(phone);
    $("#email").val(email);
    $("#user").val(user);
    $("#user_gpac").val(user_gpac);
    $("#user_type").val(type_id);
    $("#empresa_type").val(client);

    $("#modal-show-user").modal('show');
    $('#modal-show-user').on('hidden.bs.modal', function () {
        refreshUsers();
    });
}

function saveUserData(){
    
    let id=$("#id_user").val(), name= $("#name").val(), phone= $("#phone").val(), email= $("#email").val(), user= $("#user").val();
    let user_gpac= $("#user_gpac").val(), type_id= $("#user_type").val(), empresa_type= $("#empresa_type").val();
    
    $.ajax({
        type: "POST",
        url: "http://127.0.0.1/wyipist-fusion/users/ManageUsers/editUsers",
        dataType: "json",
        data: {
            id: id,
            name: name,
            phone: phone,
            email: email,
            user: user,
            user_gpac: user_gpac,
            type_id: type_id,
            empresa_type: empresa_type
        },


        success: function (data) {
            //alert(data);
            console.log(data);

            if (data === "kick") {
                //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else if (data === "Utilizador inserido já existe!") {

                //$("#myAlertErrorEditar").show();
                //fire_annotation_error2("Utilizador inserido já existe!", "myAlertErrorEditar");
                toastr["error"]("Utilizador inserido já existe!")

            } else if (data === "Utilizador GPAC inserido já existe!") {

                //$("#myAlertErrorEditar").show();
                //fire_annotation_error2("Utilizador GPAC inserido já existe!", "myAlertErrorEditar");
                toastr["error"]("Utilizador GPAC inserido já existe!");

            } else {

                //$("#myAlertSuccessEditar").show();
                //fire_annotation_success2("Dados gravados com Sucesso", "myAlertSuccessEditar");
                toastr["success"]("Dados gravados com Sucesso");
                /*
                setTimeout(() => {
                   location.reload();
                }, 1000);
                */
            }


        },
        error: function (e) {
            //alert(e);
            alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
            console.log(e);
        }

    });

}

function deleteUserID(id){

    type='success';
    title='Tem a certeza que pretende continuar?';
    text2='';
    action='deleteUserID';
    xposition='center';
    tblPL=[];
    tblLoc=[];
    valor=id;

    fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc); 
}

function confirm_delete(id){    
    $.ajax({
            type: "POST",
            url: "http://127.0.0.1/wyipist-fusion/users/ManageUsers/deleteUser",
            dataType: "json",
            data: {
                id : id
            },
            success: function (data) {                
                if (data === "kick") {
                    //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                    window.location = "home/logout";
                } else {
                    toastr["success"]("Utilizador removido com sucesso");
                    setTimeout(() => { 
                        refreshUsers();
                    }, 1000);
                    
                }
            },
            error: function (e) {
                //alert(e);
                alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                console.log(e);
            }
        });
}

function refreshUsers(){
    window.location = "http://127.0.0.1/wyipist-fusion/users/all-users";
}