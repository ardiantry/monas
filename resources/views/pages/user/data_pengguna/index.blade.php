@extends('layouts.backend')
@section('title','Data MoPnegguna')
@section('content')
<h4><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>Data Pengguna</h4>

<a class="btn btn-success btn-sm mb-3" id="Add">Tambah Pengguna</a>
<div class="table-responsive">
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>No</th> 
                <th>Nama</th> 
                <th>Merk Mobil</th>
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
                <h5 class="modal-title align-self-center mt-0">Tambah Pengguna</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body"> 
                <form id="prosesSimpan" name="prosesSimpan">
                <div class="ms-alert"></div>
                     <div class="form-group row">
                         <label  class="col-3">Nama</label>
                         <div class="col-9">
                             <input type="text" id="nama_pengguna" name="nama_pengguna" class="form-control" minlength="1" maxlength="11" >
                         </div>
                     </div>
                    <div class="form-group row">
                         <label  class="col-3">Nomor Plat Mobil</label>
                         <div class="col-9">
                             <select class="form-control" name="id_mobil">
                                 
                             </select>
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
        $('body').delegate('#Add','click',function(e)
        {
            e.preventDefault();
            window.id_edit      =undefined;
            window.nama_pengguna=undefined; 
            window.id_mobil=undefined; 
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
                fetch('{{url('simpan-pengguna')}}', { method: 'POST',body:Form_item}).then(res => res.json()).then(data => 
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
    ajax: "{{url('show-table-data-pengguna')}}",
    columns: [{
    data: 'DT_RowIndex',
    name: 'DT_RowIndex'
    },
    {
    data: 'nama_pengguna',
    name: 'nama_pengguna'
    },
    {
    data: 'plat_nomor',
    name: 'plat_nomor'
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
                window.nama_pengguna=$(this).data('nama_pengguna');
                window.id_mobil=$(this).data('id_mobil');




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
                fetch('{{url('hapus-pengguna')}}', { method: 'POST',body:form_dlt}).then(res => res.json()).then(data => 
                    {
                       $('.table').DataTable().ajax.reload();

                    });


        });
        $('#ModalForm').on('show.bs.modal', function (event) 
        {
            var id_edit         =window.id_edit!=undefined?window.id_edit:'';
            var nama_pengguna   =window.nama_pengguna!=undefined?window.nama_pengguna:''; 
            var id_mobil        =window.id_mobil!=undefined?window.id_mobil:'';
            $('*[name="nama_pengguna"]').val(nama_pengguna); 
            getmobil(id_mobil);


        });
        function getmobil(id_mobil='')
        {
            var id=id_mobil!=''?'?id_mobil='+id_mobil:'';
             fetch('{{url('data-mobil-list')}}'+id, { method: 'GET'}).then(res => res.json()).then(data => 
                    { 
                        var lis='';
                        for(let k of data.datamobil)
                        {
                            var selected=k.id.toString()==id_mobil.toString()?'selected="selected"':'';
                            lis+=`<option value="`+k.id+`" `+selected+`>`+k.plat_nomor+`(`+k.nama_mobil+`)</option>`;
                        }
                        $('select[name="id_mobil"]').html(lis);
                    });
        }
    });
</script>
@endsection
