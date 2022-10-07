@extends('layouts.backend')
@section('title','Data Mobil')
@section('content')
<h4><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>Data</h4>

<a class="btn btn-success btn-sm mb-3" id="AddMobil">Tambah Service</a>
<div class="table-responsive">
    <table class="table table-bordered mb-4">
        <thead>
            <tr>
                <th>No</th> 
                <th>Nama Mobil</th>
                <th>Nama Driver</th>
                <th>Lokasi</th>
                <th>KM Sekarang</th> 
                <th>KM Service</th> 
                <th>Tanggal</th>
                <th>Keterangan Sevice</th> 
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
                <h5 class="modal-title align-self-center mt-0">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body"> 
                <form id="prosesSimpan" name="prosesSimpan">
                <div class="ms-alert"></div>
                     <div class="form-group row">
                         <label  class="col-3">Nama Mobil</label>
                         <div class="col-9">
                             <select name="id_mobil" class="form-control">
                                 <option >Pilih Mobil</option>

                                @php
                                $dtMobil=DB::table('tb_data_mobil')->get();
                                @endphp
                                @foreach($dtMobil as $key)
                                 <option value="{{$key->id}}">{{$key->nama_mobil.' ('.$key->plat_nomor.')'}}</option>
                                @endforeach
                             </select>
                         </div>
                     </div>
                    <div class="form-group row">
                         <label  class="col-3">Nama Driver</label>
                         <div class="col-9" >
                             <input type="text" name="driver" class="form-control" required="" disabled="">
                         </div>
                     </div>
                     <div class="form-group row">
                         <label  class="col-3">Lokasi</label>
                         <div class="col-9">
                            <input type="text" name="lokasi" class="form-control" required=""  >
                         </div>
                     </div>
                     <div class="form-group row">
                         <label  class="col-3">KM Sekarang</label>
                         <div class="col-9">
                            <input type="text" name="km_sekarang" class="form-control" required=""  >
                         </div>
                     </div>
                      <div class="form-group row">
                         <label  class="col-3">KM Service</label>
                         <div class="col-9">
                            <input type="text" name="km_service" class="form-control" required=""  >
                         </div>
                     </div>
                      <div class="form-group row">
                         <label  class="col-3">Tanggal Service</label>
                         <div class="col-9">
                            <input type="date" name="tgl_service" class="form-control" required=""  >
                         </div>
                     </div>
                      <div class="form-group row">
                         <label  class="col-3">Keterangan</label>
                         <div class="col-9">
                            <select class="form-control" name="keterangan">
                                @php
                                $ket=array('service Besar','Oli','Body Repair','Spion','Kaca','Ban');
                                @endphp
                                @foreach($ket as $l)
                                <option value="{{$l}}">{{$l}}</option>
                                @endforeach
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
        $('body').delegate('#AddMobil','click',function(e)
        {
            e.preventDefault();
            window.id_edit      =undefined; 
            window.id_mobil=undefined;
            window.driver=undefined;
            window.lokasi=undefined;
            window.km_sekarang=undefined;
            window.km_service=undefined;
            window.tgl_service=undefined;
            window.keterangan=undefined;
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
                fetch('{{url('simpan-data-service')}}', { method: 'POST',body:Form_item}).then(res => res.json()).then(data => 
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
    ajax: "{{url('show-table-data-service')}}",
    columns: [{
    data: 'DT_RowIndex',
    name: 'DT_RowIndex'
    },
    {
    data:'merk_mobil',
    name:'merk_mobil'
    },
    {
    data:'driver',
    name:'driver'
    },
    {
    data:'lokasi',
    name:'lokasi'
    },
    {
    data:'km_sekarang',
    name:'km_sekarang'
    },
    {
    data:'km_service',
    name:'km_service'
    },
    {
    data:'tgl_service',
    name:'tgl_service'
    },
    {
    data:'keterangan',
    name:'keterangan'
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
                 
                window.id_mobil     =$(this).data('id_mobil'); 
                window.driver     =$(this).data('driver'); 
                window.lokasi     =$(this).data('lokasi'); 
                window.km_sekarang     =$(this).data('km_sekarang'); 
                window.km_service     =$(this).data('km_service'); 
                window.tgl_service     =$(this).data('tgl_service'); 
                window.keterangan     =$(this).data('keterangan'); 

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
var id_mobil     =window.id_mobil!=undefined?window.id_mobil:''; 
var driver     =window.driver!=undefined?window.driver:''; 
var lokasi     =window.lokasi!=undefined?window.lokasi:''; 
var km_sekarang     =window.km_sekarang!=undefined?window.km_sekarang:''; 
var km_service     =window.km_service!=undefined?window.km_service:''; 
var tgl_service     =window.tgl_service!=undefined?window.tgl_service:''; 
var keterangan     =window.keterangan!=undefined?window.keterangan:''; 

            $('*[name="id_mobil"]').find('option').removeAttr('selected');
            $('*[name="keterangan"]').find('option').removeAttr('selected');


            $('*[name="id_mobil"]').find('option[value="'+id_mobil+'"]').attr('selected','selected');
            $('*[name="keterangan"]').find('option[value="'+keterangan+'"]').attr('selected','selected');

            $('*[name="driver"]').val(driver);
            $('*[name="lokasi"]').val(lokasi);
            $('*[name="km_sekarang"]').val(km_sekarang);
            $('*[name="km_service"]').val(km_service);
            $('*[name="tgl_service"]').val(tgl_service); 



        });
        $('body').delegate('select[name="id_mobil"]','change',function(e)
        {
            e.preventDefault();
             fetch('{{url('get-data-pengguna')}}?id_mobil='+$(this).val(), { method: 'GET'}).then(res => res.json()).then(data => 
                    {
                         $('input[name="driver"]').val(data.nama);
                         $('input[name="driver"]').removeAttr('disabled');


                    });

        });
    });
</script>
@endsection
