
<div class="content-wrapper">

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Página Inicial</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header" style="border-bottom: transparent;">                   
            </div>
        
            <div class="card-body">
                <div class="container-fluid">                    
                    <div class="row">
                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/production" class="btn btn-app bg-secondary" style="height: 95%; width: 95%;">
                                <i class="fas fa-boxes" style="font-size: x-large"></i></i>&nbsp;<p style="font-size: large;">Saída<br>Produção</p>
                            </a>
                        </div>  
                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/generate_new_palette" class="btn btn-app bg-pink" style="height: 95%; width: 95%;">
                                <i class="fas fa-file-circle-plus" style="font-size: x-large"></i>&nbsp;<p  style="font-size: large;">Gerar Nova<br>Palete</p>
                            </a>
                        </div>
                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/internal_movements/change_location" class="btn btn-app bg-primary" style="height: 95%; width: 95%;">
                                <i class="fas fa-truck-loading" style="font-size: x-large"></i>&nbsp;<p  style="font-size: large;">Movimentações<br>Internas<br>(Troca Localização)</p>
                            </a>
                        </div>  
                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/manage_palettes/cancel_palettes" class="btn btn-app bg-danger" style="height: 95%; width: 95%;">
                                <i class="fas fa-box" style="font-size: x-large"></i>&nbsp;<p  style="font-size: large;">Anular<br>Paletes</p>
                            </a>
                        </div>
                        <div class="col-lg-2 col-6" style="padding: 0;">
                            <a href="<?=base_url();?>stocks/stock_correction" class="btn btn-app bg-olive" style="height: 95%; width: 95%;">
                                <i class="fas fa-edit" style="font-size: x-large"></i>&nbsp;<p  style="font-size: large;">Correção<br>de Stock</p>
                            </a>
                        </div>                        
                    </div>
                </div>

            </div>            
            <div class="card-footer" style="display: block; background-color: transparent;">
            </div>		
        </div>
    </div>
</section>
</div>


