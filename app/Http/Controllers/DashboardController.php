<?php

namespace App\Http\Controllers;

use App\Models\Perkembangan;
use App\Models\Posyandu;
use App\Models\User;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
	public function index()
	{

		$posyandu =  DB::table('tb_rekap_posyandu')
			->leftJoin('districts', 'tb_rekap_posyandu.kecamatan_id', '=', 'districts.id')
			->leftJoin('villages', 'tb_rekap_posyandu.kelurahan_id', '=', 'villages.id')
			->leftJoin('tb_geografi', 'tb_rekap_posyandu.id', '=', 'tb_geografi.posyandu_id')
			->leftJoin('tb_demografi', 'tb_rekap_posyandu.id', '=', 'tb_demografi.posyandu_id')
			->leftJoin('tb_pembentukan', 'tb_rekap_posyandu.id', '=', 'tb_pembentukan.posyandu_id')
			->select(
				'tb_rekap_posyandu.*',
				'tb_geografi.*',
				'tb_demografi.*',
				'tb_pembentukan.*',
				'tb_rekap_posyandu.id as id_posyandu',
				'districts.*',
				'villages.*',
				'districts.name as kecamatan',
				'villages.name as kelurahan',
			)->where('tb_rekap_posyandu.id', auth()->user()->posyandu_id)
			->first();
		$totalPenduduk = DB::table('tb_rekap_posyandu')
			->leftJoin('tb_geografi', 'tb_rekap_posyandu.id', '=', 'tb_geografi.posyandu_id')
			->leftJoin('tb_demografi', 'tb_rekap_posyandu.id', '=', 'tb_demografi.posyandu_id')
			->leftJoin('tb_pembentukan', 'tb_rekap_posyandu.id', '=', 'tb_pembentukan.posyandu_id')
			->select(
				'tb_rekap_posyandu.*',
				'tb_geografi.*',
				'tb_demografi.*',
				'tb_pembentukan.*',
				'tb_rekap_posyandu.id as id_posyandu',
			)
			->select(DB::raw('SUM(jml_pdd) as jmlh'))
			->first();
		// dd($totalPenduduk);
		$totalPendudukl = DB::table('tb_rekap_posyandu')
			->leftJoin('tb_geografi', 'tb_rekap_posyandu.id', '=', 'tb_geografi.posyandu_id')
			->leftJoin('tb_demografi', 'tb_rekap_posyandu.id', '=', 'tb_demografi.posyandu_id')
			->leftJoin('tb_pembentukan', 'tb_rekap_posyandu.id', '=', 'tb_pembentukan.posyandu_id')
			->select(
				'tb_rekap_posyandu.*',
				'tb_geografi.*',
				'tb_demografi.*',
				'tb_pembentukan.*',
				'tb_rekap_posyandu.id as id_posyandu',
			)
			->select(DB::raw('SUM(jml_pdd_l) as jmlh_l'))
			// ->select(DB::raw('SUM(jml_pdd_l) as jmlh_p'))
			->first();
		$totalPendudukp = DB::table('tb_rekap_posyandu')
			->leftJoin('tb_geografi', 'tb_rekap_posyandu.id', '=', 'tb_geografi.posyandu_id')
			->leftJoin('tb_demografi', 'tb_rekap_posyandu.id', '=', 'tb_demografi.posyandu_id')
			->leftJoin('tb_pembentukan', 'tb_rekap_posyandu.id', '=', 'tb_pembentukan.posyandu_id')
			->select(
				'tb_rekap_posyandu.*',
				'tb_geografi.*',
				'tb_demografi.*',
				'tb_pembentukan.*',
				'tb_rekap_posyandu.id as id_posyandu',
			)
			->select(DB::raw('SUM(jml_pdd_p) as jmlh_p'))
			->first();
		// dd($total);
		$total_posyandu = Posyandu::count();
		$total_posyandu_kecamatan = Posyandu::where('kecamatan_id', auth()->user()->kecamatan_id)->count();
		$total_petugas = User::where('role', 'petugas')->count();
		$total_petugas_kecamatan = User::where('role', 'petugas_kecamatan')->count();
		// dd($total_petugas);
		$total_perkembangan = Perkembangan::count();
		// $date = Carbon::now()->format('Y');
		// $tb_perkembangan  = Perkembangan::where('tahun_rekap', $date)->sum('jml_kader');
		// $tb_perkembangan_tl  = Perkembangan::where('tahun_rekap', $date)->where('jml_terlatih', 'ya')->sum('jml_kader_terlatih');
		// dd($tb_perkembangan_tl);
		$data = [
			'menu' => 'dashboard',
			'submenu' => 'dashboard',
			'pos' => $posyandu,
			'tp' => $totalPenduduk,
			'tpl' => $totalPendudukl,
			'tpp' => $totalPendudukp,
			'total_posyandu' => $total_posyandu,
			'total_posyandu_kecamatan' => $total_posyandu_kecamatan,
			'total_petugas' => $total_petugas,
			'total_petugas_kecamatan' => $total_petugas_kecamatan,
			'total_perkembangan' => $total_perkembangan,
			// 'allPos' => $data
		];
		return view('pages.backend.dashboard', $data);
	}
}
