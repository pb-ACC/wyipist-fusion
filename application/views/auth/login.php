
<!DOCTYPE html>
<html lang="en">

<head>
    <title>WyipiStockTerminal login</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="<?=base_url();?>public/css/bootstrap/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!--<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>-->
    <script type="text/javascript" src="<?=base_url();?>public/js/bootstrap/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href='<?=base_url();?>public/css/style.css'/>
    <link href='https://fonts.googleapis.com/css?family=Exo:400,200italic' rel='stylesheet' type='text/css'>
    <!--<script src="./js/login.js"></script>-->
    <link rel="stylesheet" href="<?=base_url();?>public/plugins/fontawesome-free/css/all.min.css">
    <link rel="icon" href="<?=base_url();?>public/img/logos/wyipi.ico">
    <script>

        $(document).ready(function () {

            var data2 = <?php echo json_encode(form_error('username')); ?>;

            if (data2 != '') {
                $("#user").popover({content: data2, html: true});
                $("#user").popover('show');
                $("#user").next('.popover').addClass('popover-danger');
                $("#g1").addClass('has-error');
            }


            var data = <?php echo json_encode(form_error('password')); ?>;

            if (data != '') {

                $("#pass").popover({content: data, html: true});
                $("#pass").popover('show');
                $("#pass").next('.popover').addClass('popover-danger');
                $("#g2").addClass('has-error');
            }


        });


    </script>


</head>

<body>

<div class="container"></div>

<form role="form-horizontal" class="col-sm-6 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4"
      action="<?php echo base_url();?><?=$language?>" method="post" accept-charset="utf-8">
    <? //php echo form_open(); ?>

    <!--	<p class="form-group" id="header"> <a href="#"><img id ="logo" src="img/ACC-peq.png" class=" img-responsive"/></a>WYIPI WEB VIEWER</p> -->
    <div class="row" id="header"><img id="logo" src="public/img/logos/wyipi.png" class=" img-responsive"/></div>

    <div class="form-group" id="g1">
        <div class="input-group">
            <span class="input-group-addon" id="user-addon">
            <i class="fas fa-user"></i>
            </span>
            <input type="text" name="username" id="user" class="form-control"
                   placeholder="<?= lang('form_name_value') ?>" aria-describedby="user-addon"
                   value="<?php echo set_value('username'); ?>">

        </div>
    </div>
    <!-- /.form-group -->

    <div class="form-group" id="g2">
        <div class="input-group">
            <span class="input-group-addon" id="pass-addon">
            <i class="fas fa-lock"></i>
            </span>
            <input type="password" name="password" id="pass" class="form-control"
                   placeholder="<?= lang('form_pass_value') ?>" aria-describedby="pass-addon"
                   value="<?php echo set_value('password'); ?>">

        </div>
        <!-- /.input-group -->
    </div>
    <!-- /.form-group -->

    <div class="form-group">
        <input type="submit" value="<?= lang('login') ?>" class="form-control btn btn-primary"></input>

    </div>

    <div id="footer">
        <p>
            <a href="pt" class="btn btn-primary"><span><i class="fas fa-flag"></i></span> PT</a>
            <a href="en" class="btn btn-primary"><span><i class="fas fa-flag"></i></span> EN</a>
        </p>

        <p> Copyright &copy 2020 by ACC Systems. All Rights Reserved. </p>

        <p class="col-sm-12 ">
            <a href="http://www.accsystems.biz" target="_blank"><img src="public/img/logos/ACC-peq_sem fundo.png"></a>
        </p>
        <!--<p>  <img src="img/logos/ACC-peq.png"  </p>-->
    </div>


</form>

<div id="optimized"><a href="https://www.google.pt/chrome/browser/desktop/" target="_blank"><img
            src="public/img/logos/chrome.png" height="33" width="100"></a></div>


</div>

</body>

</html>