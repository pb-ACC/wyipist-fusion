function fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc,tblLote,tblAfet){

    if(action==='motive') {

    Swal.fire({
            title: title,
            input: 'textarea',
            inputPlaceholder: text2,
            inputAttributes: {
                'aria-label': text2
            },
            showCancelButton: true
            });     
    }

    if(action==='save_local_fabric') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                //html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {                
                confirm_save(tblPL,tblLoc);
            } else if (result.isDenied) {            
            }
        })
    }

    if(action==='save_local_logistic') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                //html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {                
                confirm_save(tblPL,tblLoc);
            } else if (result.isDenied) {            
            }
        })
    }

    if(action==='save_local_warehouse') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                //html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {                
                confirm_save(tblPL,tblLoc);
            } else if (result.isDenied) {            
            }
        })
    }

    if(action==='changeEmpresa') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                //html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {    
                tableSelPaletes.setData(data=[]);            
                confirm_changeEmpresa();
            } else if (result.isDenied) {            
            }
        })
    }

    if(action==='deleteUserID') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                //html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {                
                confirm_delete(valor);
            } else if (result.isDenied) {            
            }
        })
    }

    if(action==='radioButtons') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 20px;color: #333">'+text2+'</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {                
                $('.form-check-input').prop('checked', false); // Unchecks it    
                $(valor).prop('checked', true); // Checks it              
                change_sector_emp(campo);
            } else if (result.isDenied) {
                $(valor).prop('checked', false); // Checks it   
            }
        })
    }

    if(action==='cancel_palette') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                //html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {            
                confirm_cancellation(tblPL);
            } else if (result.isDenied) {            
            }
        })
    }

    if(action==='corrige_stock') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                //html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {            
                confirm_correction(tblPL);
            } else if (result.isDenied) {            
            }
        })
    }

    if(action==='close_gg') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                //html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {            
                confirm_close(tblPL);
            } else if (result.isDenied) {            
            }
        })
    }

    if(action==='segue_para_paletizar') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                //html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {            
                confirm_paletizar(tblPL,tblLoc,tblLote,tblAfet);
            } else if (result.isDenied) {            
            }
        })
    }

    if(action==='save_confirm_palette') {
        Swal.fire({
                icon: type,
                iconHtml: '?',
                iconColor: '#f8bb86',
                title: title,
                //html: '<p  style="font-family: Arial, Helvetica, sans-serif; font-size: 14px;color: #333">Deseja continuar?</p>',
                showDenyButton: true,
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> Sim',
                denyButtonText: '<i class="fa fa-thumbs-down"></i> Não'
            }
        ).then((result) => {
            if (result.isConfirmed) {            
                confirm_palette(tblPL);
            } else if (result.isDenied) {            
            }
        })
    }
}