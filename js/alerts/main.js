function fire_annotation(type,title,text2,action,xposition,campo,valor,tblPL,tblLoc){

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

}