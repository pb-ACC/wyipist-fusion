let table;
$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

$.ajax({                
    type: "GET",
    url: "http://127.0.0.1/wyipist-fusion/users/Users/listUsers",
    dataType: "json",
    success: function (data) {
        if (data === "kick") {
            //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
            window.location = "home/logout";
        } else {
            //console.log(data);
            listUsers(data);
        }
    },
    error: function (e) {
        //alert(e);
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

        var editUser = function(cell, formatterParams){ //plain text value
            return "<i class='fas fa-edit'></i>";
        };

        var deleteUser = function(cell, formatterParams){ //plain text value
            return "<i class='fas fa-trash-alt'></i>";
        };


        table = new Tabulator("#users-table", {
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
                        cell.getRow().getData().email,cell.getRow().getData().username,cell.getRow().getData().passwd,cell.getRow().getData().client,cell.getRow().getData().funcionario_gpac)}},
                {title:" ",formatter:deleteUser, width:50, align:"center",tooltip:true, cellClick:function(e, cell){deleteUserID(cell.getRow().getData().id)}}

            ]
        });

    }

    function editUserDetails(id, type_id, name,phone,email,user,pass,client,user_gpac){

        $("#id_user").val(id);
        $("#name").val(name);
        $("#phone").val(phone);
        $("#email").val(email);
        $("#user").val(user);
        $("#pass").val(pass);
        $("#user_gpac").val(user_gpac);
        $("#user_type").val(type_id);
        $("#empresa_type").val(client);

        $("#modal-show-user").modal('show');

        $('#modal-show-user').on('hidden.bs.modal', function () {
            refreshUsers();
        });

    }


    function saveUserData(){

        var id=$("#id_user").val();
        var name= $("#name").val();
        var phone= $("#phone").val();
        var email= $("#email").val();
        var user= $("#user").val();
        var pass= $("#pass").val();
        var user_gpac= $("#user_gpac").val();
        var type_id= $("#user_type").val();
        var empresa_type= $("#empresa_type").val();


        if(name === ""){

            console.log("estou vazio");

            $("#name").next('p').removeClass('hidden');

        }else if(user === ""){


            console.log("estou vazio");

            $("#user").next('p').removeClass('hidden');

        }else if(pass === ""){


            console.log("estou vazio");

            $("#pass").next('p').removeClass('hidden');

        }else if(user_gpac === ""){


            console.log("estou vazio");

            $("#user_gpac").next('p').removeClass('hidden');

        }else {


            $.ajax({
                type: "POST",
                url: "http://127.0.0.1/wyipist-fusion/users/Users/editUsers",
                dataType: "json",
                data: {
                    id: id,
                    name: name,
                    phone: phone,
                    email: email,
                    user: user,
                    pass: pass,
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
                        //$("#modal-show-user").modal('show');
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


   function refreshUsers(){
        window.location = "http://127.0.0.1/wyipist-fusion/users/Users";
    }




    document.getElementById("photo").onclick = function () {

        $("#photo").fileupload({
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
            updateUserImageBD(path);

            //getImagens(codigo2,NumeroDocumento2,linhaTabela2,tipo2);


            if(status==1){



            }




        }).bind('fileuploadprogressall', function(e,data){
            var progresso=parseInt(data.loaded / data.total * 100);
            //fire_annotation_success2(progresso + " % Completo","myAlertSuccessEditar");
            toastr["success"](progresso + " % Completo");




        });





    };




    function updateUserImageBD(file){


        var id=$("#id_user").val();

        $.ajax({
            type: "POST",
            url: "http://127.0.0.1/wyipist-fusion/users/Users/editUsersImageBD",
            dataType: "json",
            data: {
                id : id,
                file:file
            },


            success: function (data) {
                //alert(data);
                console.log(data);

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



    function saveUserData_add(){

        alert('sdasd');
        
        var name_add= $("#name_add").val();
        var phone_add= $("#phone_add").val();
        var email_add= $("#email_add").val();
        var user_add= $("#user_add").val();
        var pass_add= $("#pass_add").val();
        var user_gpac_add= $("#user_gpac_add").val();
        var type_id_add= $("#user_type_add").val();
        var empresa_type_add= $("#empresa_type_add").val();

       if(name_add === ""){

            console.log("estou vazio");

           $("#name_add").next('p').removeClass('hidden');

       }else if(user_add === ""){


           console.log("estou vazio");

           $("#user_add").next('p').removeClass('hidden');

       }else if(pass_add === ""){


           console.log("estou vazio");

           $("#pass_add").next('p').removeClass('hidden');

       }else if(user_gpac_add === ""){


           console.log("estou vazio");

           $("#user_gpac_add").next('p').removeClass('hidden');

       }else {


           $.ajax({
               type: "POST",
               url: "http://127.0.0.1/wyipist-fusion/users/Users/addUsers",
               dataType: "json",
               data: {
                   name: name_add,
                   phone: phone_add,
                   email: email_add,
                   user: user_add,
                   pass: pass_add,
                   user_gpac: user_gpac_add,
                   type_id: type_id_add,
                   empresa_type: empresa_type
               },


               success: function (data) {
                   //alert(data);
                   console.log(data);

                   if (data === "kick") {
                       //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                       toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                       window.location = "home/logout";
                   } else if (data==="Utilizador inserido já existe!") {

                    //$("#myAlertErrorAdicionar").show();
                    toastr["error"]("Utilizador inserido já existe!");
                    //fire_annotation_error2("Utilizador inserido já existe!", "myAlertErrorAdicionar");

                   }else if(data==="Utilizador GPAC inserido já existe!"){


                    toastr["error"]("Utilizador GPAC inserido já existe!");
                    //$("#myAlertErrorAdicionar").show();
                    //fire_annotation_error2("Utilizador GPAC inserido já existe!", "myAlertErrorAdicionar");

                   } else{

                    toastr["success"]("Utilizador criado com Sucesso");
                    //$("#myAlertSuccessAdicionar").show();
                    //fire_annotation_success2("Utilizador criado com Sucesso", "myAlertSuccessAdicionar");
                    
                    /*
                    setTimeout(function(){
                        $("#modal-add-user").modal('hide');
                    },1200);
                    */
                        $('#modal-add-user').on('hidden.bs.modal', function () {
                            refreshUsers();
                        });
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


    document.getElementById("photo_add").onclick = function () {



        $("#photo_add").fileupload({
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
            url: "http://127.0.0.1/wyipist-fusion/users/Users/addUsersImageBD",
            dataType: "json",
            data: {
                file:file
            },


            success: function (data) {
                //alert(data);
                console.log(data);

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



    function deleteUserID(id){
        //var result = confirm("Tem a certeza que pretende apagar o Utilizador?");
        //if (result) {}

        toastr["warning"]("<div><h6>Tem a certeza que pretende apagar o Utilizador?</h6></div>"+
                            "<div>"+
                            "<button type='button' id='okBtn' onclick='removeUser("+id+");' class='btn btn-success btn-sm btn-flat' style='margin: 0 8px 0 8px'> Confirmar </button>"+
                            "<button type='button' id='surpriseBtn' class='btn btn-danger btn-sm btn-flat' style='margin: 0 8px 0 8px'> Cancelar </button>"+
                            "</div>");
    }

function removeUser(id){
        $.ajax({
                type: "POST",
                url: "http://127.0.0.1/wyipist-fusion/users/Users/deleteUser",
                dataType: "json",
                data: {
                    id : id
                },


                success: function (data) {
                    //alert(data);
                    console.log(data);

                    if (data === "kick") {
                        //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                        window.location = "home/logout";
                    } else {


                        refreshUsers();

                    }


                },
                error: function (e) {
                    //alert(e);
                    alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
                    console.log(e);
                }

            });
}