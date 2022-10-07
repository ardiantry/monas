@extends('layouts.backend')
@section('title','Data Mobil')
@section('content')
<h4><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-clipboard"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path><rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect></svg>Data Mobil</h4>

<a class="btn btn-success btn-sm mb-3" id="AddMobil">Tambah Mobil</a>
<div class="table-responsive">
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>No</th> 
                <th>Plat Nomor</th>
                <th>Merk Mobil</th>
                <th>Nama Mobil</th>
                <th>Status</th> 
                <th class="text-center">Aksi</th> 
            </tr>
        </thead>
         <tbody></tbody>
    </table>
</div>
<div id="ModalForm" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title align-self-center mt-0">Tambah Mobil</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body"> 
                <form id="prosesSimpan" name="prosesSimpan">
                <div class="ms-alert"></div>
                     <div class="form-group row">
                         <label  class="col-3">Plat Nomor</label>
                         <div class="col-9">
                             <input type="text" id="plat_nomor" name="plat_nomor" class="form-control" minlength="1" maxlength="11" >
                         </div>
                     </div>
                    <div class="form-group row">
                         <label  class="col-3">Merk Mobil</label>
                         <div class="col-9">
                             <input type="text" id="merk_mobil" name="merk_mobil" class="form-control" minlength="1" maxlength="11" >
                         </div>
                     </div>
                     <div class="form-group row">
                         <label  class="col-3">Nama Mobil</label>
                         <div class="col-9">
                            <textarea class="form-control" id="nama_mobil" name="nama_mobil" ></textarea>
                         </div>
                     </div>
                     <div class="form-group">
                         <button class="btn btn-success btn-sm" type="submit">Simpan</button>
                     </div>
                    
                </form>
            </div>
        </div>
    </div>
</div> 
<script type="text/javascript">
    $(document).ready(function()
    {
        $('body').delegate('#AddMobil','click',function(e)
        {
            e.preventDefault();
            window.id_edit      =undefined;
            window.plat_nomor=undefined; 
            window.merk_mobil=undefined;
            window.nama_mobil=undefined;
            $('#ModalForm').modal({ backdrop: 'static',keyboard: false});  


        });
        $('body').delegate('#prosesSimpan','submit',function(e)
            {
                e.preventDefault();
                var this_=$(this);
                $('.ms-alert').empty();
                this_.find('button[type="submit"]').html('loading...');
                this_.find('button[type="submit"]').attr('disabled','disabled');

                const formsimpan  = document.forms.namedItem('prosesSimpan'); 
                const Form_item  = new FormData(formsimpan);
                Form_item.append('_token', '{{csrf_token()}}');  
               
                if(window.id_edit!=undefined)
                {
                    Form_item.append('id_edit', window.id_edit);   
                }
                fetch('{{url('simpan-mobil')}}', { method: 'POST',body:Form_item}).then(res => res.json()).then(data => 
                    { 
                        this_.find('button[type="submit"]').html('Simpan');
                        this_.find('button[type="submit"]').removeAttr('disabled');
                      //  console.log(data);
                        if(data.error)
                        {
                            var ky_=Object.keys(data.messages);
                            for(let l of ky_)
                            {
                            //    console.log(l);
                                  showError(l, data.messages[l]);
                            }
                        }
                        else
                        {
                               $('.ms-alert').html(showMessage('success', data.messages));
                            setTimeout(function(){
                            $('#ModalForm').modal('hide');
                               $('.ms-alert').empty();
                                 $('.table').DataTable().ajax.reload();
                            },1000);
                        } 
                    });
            });  
 


    var table = $('.table').DataTable({
    processing: true,
    serverSide: true,
    autoWidth: false,
    ajax: "{{url('show-table-data-mobil')}}",
    columns: [{
    data: 'DT_RowIndex',
    name: 'DT_RowIndex'
    },
    {
    data: 'plat_nomor',
    name: 'plat_nomor'
    },
    {
    data: 'merk_mobil',
    name: 'merk_mobil'
    },
    
     {
    data: 'nama_mobil',
    name: 'nama_mobil'
    },
    {
    data: 'status',
    name: 'status'
    },
    {
    data: 'action',
    name: 'action',
    orderable: true,
    searchable: true
    },
    ]
    }); 
        $('body').delegate('.editIcon','click',function(e)
        {
                e.preventDefault();
                window.id_edit      =$(this).data('id');
                window.plat_nomor=$(this).data('plat_nomor'); 
                window.merk_mobil=$(this).data('merk_mobil');
                window.nama_mobil=$(this).data('nama_mobil');

                $('#ModalForm').modal({ backdrop: 'static',keyboard: false});  

        });
        $('body').delegate('.HapusData','click',function(e)
        {
                e.preventDefault();
                window.id_hps      =$(this).data('id');  
                if(!confirm('hapus data ini?'))
                {
                    return
                } 
                const form_dlt  = new FormData();
                form_dlt.append('_token', '{{csrf_token()}}');   
                if(window.id_hps!=undefined)
                {
                    form_dlt.append('id_edit', window.id_hps);   
                }
                fetch('{{url('hapus-mobil')}}', { method: 'POST',body:form_dlt}).then(res => res.json()).then(data => 
                    {
                       $('.table').DataTable().ajax.reload();

                    });


        });
        $('#ModalForm').on('show.bs.modal', function (event) 
        {
            var plat_nomor=window.plat_nomor!=undefined?window.plat_nomor:'';
            var merk_mobil=window.merk_mobil!=undefined?window.merk_mobil:'';

            var nama_mobil=window.nama_mobil!=undefined?window.nama_mobil:'';

            $('*[name="plat_nomor"]').val(plat_nomor);

            $('*[name="merk_mobil"]').val(merk_mobil);

            $('*[name="nama_mobil"]').val(nama_mobil);


        });
    });
</script>
@endsection
