<?php

namespace App\Http\Controllers;

use App\Models\Demografi;
use App\Models\District;
use App\Models\Posyandu;
use App\Models\Village;
use Illuminate\Http\Request;
use DB;
use Auth;
use Carbon\Carbon;
use PDF;

class HomeController extends Controller
{
	public function index()
	{
		$total_posyandu = Posyandu::count();
		$total_penduduk = Demografi::sum('jml_pdd');
		$total_kecamatan = District::where('regency_id', 3212)->count();

		// $dis  = District::all();
		// foreach ($dis as $di) {
		// 	$vil = Village::where('district_id', $di->id)->get();
		// 	foreach ($vil as $vi) {
		// 		$total_desa = 0 + $vi->id;
		// 	}
		// }
		$total_desa = 299;
		// dd($total_desa);
		$data = [
			'total_posyandu' => $total_posyandu,
			'total_penduduk' => $total_penduduk,
			'total_kecamatan' => $total_kecamatan,
			'total_desa' => $total_desa,
		];
		return view('pages.landing-page', $data);
	}
	public function cetakpdf(Request $request)
	{
		$dat_kat = DB::table('tb_kategori')->get();
		$i 		= 0;
		$dat_id = array();
		if (@$request->id_data) {
			$dat_id = DB::table('tb_data')->where('id', @$request->id_data)->first();
		}
		foreach ($dat_kat as $key) {
			@$dat_kat[$i]->sub_kategori = DB::table('tb_subkategori')->where('id_kategori', @$key->id)->orderBy('urutan', 'ASC')->get();
			$ii = 0;
			foreach (@$dat_kat[$i]->sub_kategori as $kye_2) {
				$id_attr    = @unserialize(@$kye_2->nama_attribut) ? @unserialize(@$kye_2->nama_attribut) : array();
				//@$dat_kat[$i]->sub_kategori[$ii]->atribute=DB::table('tb_attribut')->whereIn('id',$id_attr)->get();  
				$dtjwb 		= DB::table('tb_jawaban');
				$dtjwb->where('id_subkategori', $kye_2->id);
				$dtjwb->where('id_data', @$request->id_data);
				@$jwb = $dtjwb->first();
				@$jawaban 	= @unserialize(@$jwb->jawaban) ? @unserialize(@$jwb->jawaban) : array();
				$ttr_jawab = array();
				$iii = 0;
				foreach ($jawaban as $id_attr => $jwaban) {

					$ck_tr = DB::table('tb_attribut')->where('id', $id_attr)->first();
					switch (@$ck_tr->jenis_form) {
						case 'input':
							$ttr_jawab[$iii] = $jwaban . ' ' . $ck_tr->nama_attribut;
							break;
						case 'select':
							$ttr_jawab[$iii] = $jwaban . ' ' . $ck_tr->nama_attribut;
							break;
						case 'radio':
							$ttr_jawab[$iii] = $ck_tr->nama_attribut;
							break;
						case 'checkbox':
							$ttr_jawab[$iii] = $ck_tr->nama_attribut;

							break;
						case 'textarea':
							$ttr_jawab[$iii] = $ck_tr->nama_attribut . ' ' . $jwaban;
							break;
					}
					$iii++;
				}
				@$dat_kat[$i]->sub_kategori[$ii]->jawaban = $ttr_jawab;
				$ii++;
			}
			$i++;
		}
		//dd($dat_kat);
		//return view('pages.detailpdf', compact('dat_kat','dat_id'));

		$pdf = PDF::loadView('pages.detailpdf', compact('dat_kat', 'dat_id'));
		return $pdf->download('data' . @$request->id_data . '.pdf');
	}
}
