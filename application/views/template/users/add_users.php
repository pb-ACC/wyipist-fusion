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
                        <li class="breadcrumb-item active"><i class="fa-solid fa-user-plus"></i>  Adicionar</li>
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
                    <h3 class="card-title">Adicionar Novo Utilizadores</h3>
                </div>
                <form id="userForm" accept-charset="utf-8"  enctype="multipart/form-data">
                <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputName1">Nome</label>
                                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputContact1">Contacto</label>
                                    <input type="text" class="form-control" id="contacto" name="contacto" placeholder="Telefone/Telemóvel">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputFunc1">Cargo</label>
                                    <input type="text" class="form-control" id="cargo" name="cargo" placeholder="Cargo">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputUser1">Utilizador</label>
                                    <input type="text" class="form-control" id="user" name="user" placeholder="Utilizador" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="user_type">Tipo de Utilizador</label>
                                    <select id="user_type" class="form-control" name="user_type">
                                        <option value="1">Super Admin</option>
                                        <option value="2">Admin</option>
                                        <option value="3">Comercial</option>
                                        <option value="4">Dep. Técnico</option>
                                        <option value="5">Dep. Financeiro</option>
                                        <option value="6">Produção AF</option>
                                        <option value="7">Produção CL</option>
                                    </select>
                                </div>
                            </div>    
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="exampleInputUser2">Utilizador GPAC</label>
                                    <input type="text" class="form-control" id="userGPAC" name="userGPAC" placeholder="Utilizador GPAC" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="empresa_type">Empresa</label>
                                    <select id="empresa_type" class="form-control" name="empresa_type">
                                        <option value="1">Ceragni</option>
                                        <option value="2">Certeca</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><a href=""><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleInputFile">Fotografia</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="file" name="file">
                                            <label class="custom-file-label" for="exampleInputFile">Escolher ficheiro</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>


                    <div class="card-footer" style="background-color:transparent;">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" class="btn btn-primary">Gravar</button>
                            </div>
                        </div>
                    </div>
                </form>   
            </div>
        </div>
    </section>
</div>
<script>
        $(document).ready(function() {
            <?php if (isset($_SESSION['message'])) { ?>
                var type = "<?php echo $_SESSION['message']['type']; ?>";
                var text = "<?php echo $_SESSION['message']['text']; ?>";
                toastr[type](text);
                <?php unset($_SESSION['message']); ?>
            <?php } ?>
        });
    </script>
<script src="<?=base_url();?>js/users/add_user.js"></script>