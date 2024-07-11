<div class="content-wrapper">

    <section class="content-header">        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Utilizadores</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>home"> Home</a></li>
                        <li class="breadcrumb-item active"><i class="fa fa-users"></i>  Utilizadores</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-info">
                
                <div class="card-header">
                    <h3 class="card-title">Lista de Utilizadores</h3>
                    <div class="pull pull-right" style="float: right;">									
                        <button class="btn btn-light btn-sm btn-flat" title="Novo" data-toggle="modal" data-target="#modal-add-user"
                           style="margin-right: 10px;float: right">Novo Utilizador</button>
					</div>
                </div>

                <div class="card-body">
                    <div id="users-table" class="table table-striped"  style="box-shadow: 5px 10px 18px #888888;">
                    </div>
                </div>
                
                <div class="card-footer" style="display: block;">
                </div>		
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modal-show-user" tabindex="-1" role="dialog" aria-labelledby="modal-show-user-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div id="alerts_edit" style="margin-top: 2%;">
                <div class="alert alert-danger hidden" id="myAlertErrorEditar" style="display: none">
                    <p></p>
                </div>
                <div class="alert alert-success hidden" id="myAlertSuccessEditar" style="display: none">
                    <p></p>
                </div>
            </div>
            <div class="modal-header">
                <h4 class="modal-title">Edição de Utilizador</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="box-body">
                        <div class="form-group" style="display: none">
                            <label for="id_user">ID</label>
                            <input type="number" class="form-control" id="id_user" placeholder="id" value="">
                        </div>
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control" id="name" placeholder="Nome" value="">
                            <p class="hidden" style="color: red">*Campo Obrigatório</p>
                        </div>
                        <div class="form-group">
                            <label for="phone">Telefone</label>
                            <input type="text" class="form-control" id="phone" placeholder="Telefone" value="">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Email" value="">
                        </div>
                        <div class="form-group">
                            <label for="user">Utilizador</label>
                            <input type="text" class="form-control" id="user" placeholder="Utilizador" value="">
                            <p class="hidden" style="color: red">*Campo Obrigatório</p>
                        </div>
                        <div class="form-group">
                            <label for="pass">Password</label>                            
                            <div class="input-group" id="show_hide_password">
                            <input type="password" class="form-control" id="pass" value="">
                                <div class="input-group-addon" style="margin-left: 10px; margin-top: 8px;">
                                    <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <p class="hidden" style="color: red">*Campo Obrigatório</p>
                            <input type="password" class="stealthy" tabindex="-1" style="display: none;">
                        </div>
                        <div class="form-group">
                        <label for="user_type">Tipo de Utilizador</label>
                        <select id="user_type" class="form-control">
                            <option value="1">Super Admin</option>
                            <option value="2">Admin</option>
                            <option value="3">Comercial</option>
                            <option value="4">Dep. Técnico</option>
                            <option value="5">Produção AF</option>
                            <option value="6">Produção CL</option>
                        </select>
                        </div>
                        <div class="form-group">
                            <label for="user_gpac">Utilizador GPAC</label>
                            <input type="text" class="form-control" id="user_gpac" placeholder="Utilizador GPAC" value="">
                            <p class="hidden" style="color: red">*Campo Obrigatório</p>
                        </div>
                        <div class="form-group">
                            <label for="photo">Fotografia</label>
                            <input type="file" id="photo" name="attachments[]" multiple>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="saveUserData();">Gravar Alterações</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-add-user" tabindex="-1" role="dialog" aria-labelledby="modal-add-user-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div id="alerts_add" style="margin-top: 2%;">
                <div class="alert alert-danger hidden" id="myAlertErrorAdicionar" style="display: none;">
                    <p></p>
                </div>
                <div class="alert alert-success hidden" id="myAlertSuccessAdicionar" style="display: none;">
                    <p></p>
                </div>  
            </div>
            <div class="modal-header">                
                <h4 class="modal-title">Novo Utilizador</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>                
            </div>
            <div class="modal-body">
                <form role="form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="name_add">Nome</label>
                            <input type="text" class="form-control"  id="name_add" placeholder="Nome" value="">
                            <p class="hidden" style="color: red">*Campo Obrigatório</p>
                        </div>
                        <div class="form-group">
                            <label for="phone_add">Telefone</label>
                            <input type="text" class="form-control" id="phone_add" placeholder="Telefone" value="">
                        </div>
                        <div class="form-group">
                            <label for="email_add">Email</label>
                            <input type="email" class="form-control" id="email_add" placeholder="Email" value="">
                        </div>
                        <div class="form-group">
                            <label for="user_add">Utilizador</label>
                            <input type="text" class="form-control" id="user_add" placeholder="Utilizador" value="">
                            <p class="hidden" style="color: red">*Campo Obrigatório</p>
                        </div>
                        <div class="form-group">
                            <label for="pass_add">Password</label>
                            <div class="input-group" id="show_hide_password">
                                <input type="password" class="form-control" id="pass_add" value="">
                                <div class="input-group-addon" style="margin-left: 10px; margin-top: 8px;">
                                    <a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
                                </div>
                            </div>
                            <p class="hidden" style="color: red">*Campo Obrigatório</p>
                            <input type="password" class="stealthy" tabindex="-1" style="display: none;" novalidate>
                        </div>
                        <div class="form-group">
                            <label for="user_type_add">Tipo de Utilizador</label>
                            <select id="user_type_add" class="form-control">                            
                                <option value="1">Super Admin</option>
                                <option value="2">Admin</option>
                                <option value="3">Comercial</option>
                                <option value="4">Dep. Técnico</option>
                                <option value="5">Produção AF</option>
                                <option value="6">Produção CL</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="user_gpac_add">Utilizador GPAC</label>
                            <input type="text" class="form-control" id="user_gpac_add" placeholder="Utilizador GPAC" value="">
                            <p class="hidden" style="color: red">*Campo Obrigatório</p>
                        </div>
                        <div class="form-group">
                            <label for="photo_add">Fotografia</label>
                            <input type="file" id="photo_add" name="attachments[]" multiple>
                        </div>
                    </div>
                    </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" onclick="saveUserData_add();">Criar Utilizador</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>

    $(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

    $(document).ready(function() {
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
    });


     $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>users/Users/listUsers",
        dataType: "json",


        success: function (data) {
            //alert(data);
            console.log(data);

            if (data === "kick") {
                //alert("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                toastr["warning"]("Outro utilizador entrou com as suas credenciais, faça login de novo.");
                window.location = "home/logout";
            } else {

                console.log(data);
                listUsers(data);

            }


        },
        error: function (e) {
            //alert(e);
            alert('Request Status: ' + e.status + ' Status Text: ' + e.statusText + ' ' + e.responseText);
            console.log(e);
        }

    });



    function listUsers(data){

        var editUser = function(cell, formatterParams){ //plain text value
            return "<i class='fas fa-edit'></i>";
        };

        var deleteUser = function(cell, formatterParams){ //plain text value
            return "<i class='fas fa-trash-alt'></i>";
        };


            var table = new Tabulator("#users-table", {
            //data:data, //assign data to table
            placeholder:"No Data Available",
            pagination:"local",
                paginationSize:25,
                paginationSizeSelector:[25,50,75,100],
            layout:"fitColumns", //fit columns to width of table (optional)
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
                {title:" ",formatter:editUser, width:50, align:"center",tooltip:true,
                    cellClick:function(e, cell){editUserDetails(cell.getRow().getData().id,cell.getRow().getData().type_id,cell.getRow().getData().Nome,cell.getRow().getData().telefone,
                        cell.getRow().getData().email,cell.getRow().getData().username,cell.getRow().getData().passwd,cell.getRow().getData().funcionario_gpac)}},
                {title:" ",formatter:deleteUser, width:50, align:"center",tooltip:true, cellClick:function(e, cell){deleteUserID(cell.getRow().getData().id)}}

            ]
        });


        table.setData(data);

    }

    function editUserDetails(id, type_id, name,phone,email,user,pass,user_gpac){

        $("#id_user").val(id);
        $("#name").val(name);
        $("#phone").val(phone);
        $("#email").val(email);
        $("#user").val(user);
        $("#pass").val(pass);
        $("#user_gpac").val(user_gpac);
        $("#user_type").val(type_id);

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
                url: "<?php echo base_url();?>users/Users/editUsers",
                dataType: "json",
                data: {
                    id: id,
                    name: name,
                    phone: phone,
                    email: email,
                    user: user,
                    pass: pass,
                    user_gpac: user_gpac,
                    type_id: type_id
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


        window.location = "<?php echo base_url();?>users/Users";

    }




    document.getElementById("photo").onclick = function () {

        $("#photo").fileupload({
            url: "<?php echo base_url();?>fileUpload_controller/uploadEditUtilizador",
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
            url: "<?php echo base_url();?>users/Users/editUsersImageBD",
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

        $('#modal-add-user').on('hidden.bs.modal', function () {
            refreshUsers();
        });

        var name_add= $("#name_add").val();
        var phone_add= $("#phone_add").val();
        var email_add= $("#email_add").val();
        var user_add= $("#user_add").val();
        var pass_add= $("#pass_add").val();
        var user_gpac_add= $("#user_gpac_add").val();
        var type_id_add= $("#user_type_add").val();

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
               url: "<?php echo base_url();?>users/Users/addUsers",
               dataType: "json",
               data: {
                   name: name_add,
                   phone: phone_add,
                   email: email_add,
                   user: user_add,
                   pass: pass_add,
                   user_gpac: user_gpac_add,
                   type_id: type_id_add
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
            url: "<?php echo base_url();?>fileUpload_controller/uploadEditUtilizador",
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
            url: "<?php echo base_url();?>users/Users/addUsersImageBD",
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
                url: "<?php echo base_url();?>users/Users/deleteUser",
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

    /*
    function fire_annotation_success2(text,elemID) {
        $('#'+elemID+' p').html(text);
        $('#'+elemID+'').removeClass('hidden');
        //$('#myAnnotationAlert').toggleClass('hidden'); // para mostrar o alerta trocando a classe hidden
        $('#'+elemID+'').fadeTo(2000, 500).slideUp(500, function() {
            //$("#myAnnotationAlert").alert('close');
            $('#'+elemID+'').addClass('hidden');
        });
    }

    function fire_annotation_error2(text,elemID) {
        $('#' + elemID + ' p').html(text);
        $('#' + elemID + '').removeClass('hidden');
        //$('#myAnnotationAlert').toggleClass('hidden'); // para mostrar o alerta trocando a classe hidden
        $('#' + elemID + '').fadeTo(2000, 500).slideUp(500, function () {
            //$("#myAnnotationAlert").alert('close');
            $('#' + elemID + '').addClass('hidden');
        });
    }
    */

</script>


