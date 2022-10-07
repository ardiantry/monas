<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use  Carbon\Carbon;
use DataTables;

class UserController extends Controller
{
    public function login()
    {
        
        return view('pages.login');
    }
    public function register()
    {
        
        return view('pages.register');
    }
    public function forgot()
    {
        
        return view('pages.forgot');
    }
    public function reset()
    { 
        return view('pages.reset');
    }

    public function saveUser(Request $request)
    {

 
        $validator  = Validator::make($request->all(), [
            'name'              => 'required|max:50',
            'last_name'         => 'required|max:50',
            'tgl_lahir'         => 'required',
            'tpt_lahir'         => 'required|max:50',
            'nip'               => 'required|max:50',
            'no_tlp'            => 'required|max:50',
            'user_name'         => 'required|max:50',
            'email'             => 'required|email|unique:users|max:100',
            'password'          => 'required|min:6|max:50',
            'passwordconfirm'   => 'required|min:6|same:password'
        ]);

        if ($validator->fails()) 
        { 
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ]);
        } else {
            $user                   = new User();
            $user->name             =$request->input('name');
            $user->last_name        =$request->input('last_name');
            $user->tgl_lahir        =Carbon::parse($request->input('tgl_lahir'));
            $user->tpt_lahir        =$request->input('tpt_lahir');
            $user->nip              =$request->input('nip');
            $user->no_tlp           =$request->input('no_tlp');
            $user->user_name        =$request->input('user_name');
            $user->email            =$request->input('email');
            $user->password         =hash::make($request->input('password'));
            $user->save();
            return response()->json([
                'status' => 200,
                'messages' => 'Register Successfully'
            ]);
        }
    }
    public function loginUser(Request $request)
    {
        $validator  = Validator::make(request()->all(), [
            'user_name' => 'required|max:100',
            'password' => 'required|min:6|max:50',
        ]);

        if ($validator->fails()) 
        {
            // dd($validator->getMessageBag());
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ]);
        } 
        else 
        {
            $kredensil = $request->only('user_name', 'password');

            if (Auth::attempt($kredensil)) 
            {
                
                $request->session()->regenerate(); 
                return response()->json([
                    'status' => 200,
                    'messages' => 'success'
                ]);
               
            } else {
                return response()->json([
                    'status' => 401,
                    'messages' => 'User not found!'
                ]);
            }
        }
    }
    public function Dashboard()
    {
        $data = ['menu' => 'dashboard' ];
        return view('pages.user.dashboard.index', $data);
    }
    public function datamobil()
    {
        $data = ['menu' => 'data-mobil' ];
        return view('pages.user.datamobil.index', $data);
    }

    public function profileImageUpdate(Request $request)
    {
        $validator  = Validator::make(request()->all(), [
            'picture' => 'mimes:jpg,bmp,png,jpeg,svg',
        ]);

        if ($validator->fails()) {
            // dd($validator->getMessageBag());
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ]);
        } else {
            $user_id = $request->user_id;
            // dd($user_id);
            $user = User::find($user_id);
            if ($request->hasFile('picture')) {
                $file = $request->file('picture');
                $fileName = $user->name . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/picture', $fileName);
                if ($request->picture) {
                    // dd('hapus');
                    Storage::delete('public/picture/' . $user->picture);
                }
            } else {
                $fileName = $user->picture;
            }

            $userData = [
                'picture' => $fileName,
            ];

            $user->update($userData);
            return response()->json([
                'status' => 200,
                'messages' => 'Updated Successfully'
            ]);
        }
    }

    public function profileUpdate(Request $request)
    {
        $validator  = Validator::make(request()->all(), [
            'name' => 'required|max:50',
            'email' => 'unique:users,email,' . $request->user_id,
            'gender' => 'required',
            'phone' => 'required|numeric',
            'dob' => 'required',
        ]);

        if ($validator->fails()) {
            // dd($validator->getMessageBag());
            return response()->json([
                'status' => 400,
                'messages' => $validator->getMessageBag()
            ]);
        } else {
            $user = User::find($request->user_id);
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'phone' => $request->phone,
                'dob' => $request->dob,
            ];
            // dd($userData);
            $user->update($userData);
            return response()->json([
                'status' => 200,
                'messages' => 'Updated Successfully'
            ]);
        }
    }
    
    public function simpanmobil(Request $request)
    {
          $validator  = Validator::make($request->all(), [
            'plat_nomor'         => 'required',
            'merk_mobil'         => 'required',
            'nama_mobil'         => 'required'
             
        ]);
          $error=true;
          $alert='';

        if ($validator->fails()) 
        { 
            $alert=$validator->getMessageBag();
        } 
        else 
        {

            $data['plat_nomor']=$request->input('plat_nomor');
            $data['merk_mobil']= $request->input('merk_mobil');
            $data['nama_mobil']=$request->input('nama_mobil'); 
            $data['updated_at']=Carbon::now();

            if(@$request->input('id_edit'))
            {

                DB::table('tb_data_mobil')->where('id',$request->input('id_edit'))->update($data);
                 $alert='Update Sukses';
            }
            else
            {
                $data['created_at']=Carbon::now();
                DB::table('tb_data_mobil')->insert($data);
                $alert='Simpan Sukses';
            }
            $error=false;
        }
            return response()->json([
                'error' => $error,
                'messages' => $alert,
            ]);
    }
    public function showtabledatamobil(Request $request)
    {
         $data = DB::table('tb_data_mobil')->select('tb_data_mobil.*','tb_pengguna.id as id_pengguna')->leftJoin('tb_pengguna','tb_data_mobil.id','=','tb_pengguna.id_mobil')->latest()->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('created_at', function ($row) {
                $date = date('d-m-Y', strtotime($row->created_at));
                return $date;
            }) 
            ->addColumn('status', function ($row) {
                
                return @$row->id_pengguna?'Dipakai':'Free';
            }) 
            ->addColumn('action', function ($row) {
                        $actionBtn = '       <td class="text-center">
                    <ul class="table-controls">
                        <li><a href="javascript:void(0);" 
                        data-id="' . $row->id . '"
                        data-plat_nomor="' . @$row->plat_nomor . '"
                        data-merk_mobil="' . @$row->merk_mobil . '"
                        data-nama_mobil="' . @$row->nama_mobil . '"


                        class="editIcon" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                        <li><a href="javascript:void(0);" data-id="' . $row->id . '" class="HapusData" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>  </a></li>                                     
                     </ul>';
                        return $actionBtn;
                    })
            ->make(true);
    }
    public function hapusmobil(Request $request)
    {
        DB::table('tb_data_mobil')->where('id',$request->input('id_edit'))->delete();
        return response()->json([
                'error' => false
            ]);
    }
     public function datapengguna()
    {
        $data = ['menu' => 'data-pengguna' ];
        return view('pages.user.data_pengguna.index', $data);
    }
    public function datamobillist(Request $request)
    { 
        $datapengguna=DB::table('tb_pengguna')->where('status','aktif')->get();
        $id_mbl=array();
        $i=0;
        foreach ($datapengguna as $key) {
            if($request->get('id_mobil')!=$key->id_mobil)
            {
                $id_mbl[$i]= $key->id_mobil;
                $i++;

            }
        }
       $datamobil= DB::table('tb_data_mobil')->whereNotIn('id', $id_mbl)->get();
        return response()->json([
                'datamobil' => $datamobil
            ]);
    }

 public function simpanpengguna(Request $request)
    { 
          $validator  = Validator::make($request->all(), [
            'nama_pengguna'   => 'required',
            'id_mobil'         => 'required' 
             
        ]);
          $error=true;
          $alert='';

        if ($validator->fails()) 
        { 
            $alert=$validator->getMessageBag();
        } 
        else 
        {

            $data['nama_pengguna']=$request->input('nama_pengguna');
            $data['id_mobil']= $request->input('id_mobil');  

            if(@$request->input('id_edit'))
            {

                DB::table('tb_pengguna')->where('id',$request->input('id_edit'))->update($data);
                 $alert='Update Sukses';
            }
            else
            { 
                DB::table('tb_pengguna')->insert($data);
                $alert='Simpan Sukses';
            }
            $error=false;
        }
            return response()->json([
                'error' => $error,
                'messages' => $alert,
            ]);
    }

public function showtabledatapengguna(Request $request)
    {
         $data = DB::table('tb_pengguna')
         ->select('tb_pengguna.*','tb_data_mobil.plat_nomor')
         ->leftJoin('tb_data_mobil','tb_pengguna.id_mobil','=','tb_data_mobil.id')
         ->where('status','aktif')
         ->latest()->get();
        return Datatables::of($data)
            ->addIndexColumn() 
            ->addColumn('action', function ($row) {
                        $actionBtn = '       <td class="text-center">
                    <ul class="table-controls">
                        <li><a href="javascript:void(0);" 
                        data-id="' . @$row->id . '"
                        data-nama_pengguna="' . @$row->nama_pengguna . '"
                        data-id_mobil="' . @$row->id_mobil . '"  

                        class="editIcon" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                        <li><a href="javascript:void(0);" data-id="' . $row->id . '" class="HapusData" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>  </a></li>                                     
                     </ul>';
                        return $actionBtn;
                    })
            ->make(true);
    }
public function hapuspengguna(Request $request)
    {
          DB::table('tb_pengguna')->where('id',$request->input('id_edit'))->update(['status'=>'tdkaktif']);
        
        return response()->json([
                'error' => false
            ]);
    }

public function dataservice(Request $request)
    {
         $data = ['menu' => 'data' ];
        return view('pages.user.dataservice.index', $data); 
    }
public function simpandataservice(Request $request)
{
  $validator  = Validator::make($request->all(), [ 
            // id_edit
            'id_mobil'=>'required',
            'driver'=>'required',
            'lokasi'=>'required',
            'km_sekarang'=>'required',
            'km_service'=>'required',
            'tgl_service'=>'required',
            'keterangan'=> 'required',         
            ]);
          $error=true;
          $alert='';

        if ($validator->fails()) 
        { 
            $alert=$validator->getMessageBag();
        } 
        else 
        {
                $data['id_mobil']=$request->input('id_mobil');
                $data['driver']=$request->input('driver');
                $data['lokasi']=$request->input('lokasi');
                $data['km_sekarang']=$request->input('km_sekarang');
                $data['km_service']=$request->input('km_service');
                $data['tgl_service']=Carbon::parse($request->input('tgl_service'));
                $data['keterangan']=$request->input('keterangan'); 
         //   $data['updated_at']=Carbon::now();

            if(@$request->input('id_edit'))
            {

                DB::table('tb_data_service')->where('id',$request->input('id_edit'))->update($data);
                 $alert='Update Sukses';
            }
            else
            {
               // $data['created_at']=Carbon::now();
                DB::table('tb_data_service')->insert($data);
                $alert='Simpan Sukses';
            }
            $error=false;
        }
            return response()->json([
                'error' => $error,
                'messages' => $alert,
            ]);
}
public function showtabledataservice(Request $request)
{
      $data = DB::table('tb_data_service')
         ->select('tb_data_service.*','tb_data_mobil.plat_nomor','tb_data_mobil.merk_mobil')
         ->leftJoin('tb_data_mobil','tb_data_service.id_mobil','=','tb_data_mobil.id') 
         ->latest()->get();
        // dd($data);
        return Datatables::of($data)
            ->addIndexColumn() 
            ->addColumn('action', function ($row) {
                $var='';
                foreach($row as $key => $val)
                {
                 $var .=' data-'.$key.'="'. $val.'"';
                }
                        $actionBtn = '<td class="text-center">
                    <ul class="table-controls">
                        <li><a href="javascript:void(0);" 
                         '.$var.' 

                        class="editIcon" title="Edit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 text-success"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a></li>
                        <li><a href="javascript:void(0);" data-id="' . $row->id . '" class="HapusData" title="Hapus">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 text-danger"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>  </a></li>                                     
                     </ul>';
                        return $actionBtn;
                        
                    })
            ->make(true);
}
public function getdatapengguna(Request $request)
{
     $datapengguna=DB::table('tb_pengguna')->where('id_mobil',$request->input('id_mobil'))->first();
      return response()->json([
                'nama' => @$datapengguna->nama_pengguna
            ]);
    }

    public function logout()
    {
        request()->session()->flush();
        Auth::logout();
        return redirect('/login');
    }
}
