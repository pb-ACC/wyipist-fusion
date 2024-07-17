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
                        <li class="breadcrumb-item"><i class="fa fa-users"></i>  Utilizadores</li>
                        <li class="breadcrumb-item active"><i class="fa fa-magnifying-glass"></i>  Consultar</li>
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
                        <button class="btn btn-light btn-sm btn-flat" title="Novo Utilizador" onclick="location.href='<?=base_url();?>users/add-user';" style="margin-right: 10px;float: right">Novo Utilizador</button>
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
            <form role="form">                
                <div class="modal-body">
                    <div class="box-body">                        
    
                                <div class="form-group" style="display: none">
                                    <label for="id_user">ID</label>
                                    <input type="number" class="form-control" id="id_user" placeholder="id" value="">
                                </div>
                                <div class="form-group">
                                    <label for="name">Nome</label>
                                    <input type="text" class="form-control" id="name" placeholder="Nome" value="" required>
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
                                    <input type="text" class="form-control" id="user" placeholder="Utilizador" value="" required>
                                    <p class="hidden" style="color: red">*Campo Obrigatório</p>
                                </div>
                                <div class="form-group">
                                    <label for="user_type">Tipo de Utilizador</label>
                                    <select id="user_type" class="form-control">
                                        <option value="1">Super Admin</option>
                                        <option value="2">Admin</option>
                                        <option value="3">Comercial</option>
                                        <option value="4">Dep. Técnico</option>
                                        <option value="5">Dep. Financeiro</option>
                                        <option value="6">Produção AF</option>
                                        <option value="7">Produção CL</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="user_gpac">Utilizador GPAC</label>
                                    <input type="text" class="form-control" id="user_gpac" placeholder="Utilizador GPAC" value="" required>
                                    <p class="hidden" style="color: red">*Campo Obrigatório</p>
                                </div>
                                <div class="form-group">
                                    <label for="empresa_type">Empresa</label>
                                    <select id="empresa_type" class="form-control">
                                        <option value="1">Ceragni</option>
                                        <option value="2">Certeca</option>
                                    </select>
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
            </form> 
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script src="<?=base_url();?>js/users/get_users.js"></script>