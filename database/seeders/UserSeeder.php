<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
 
        $users = [
            [ 
                'name' => 'Admin',
                'last_name' => 'admin_monas',
                'password'=>bcrypt('123456'),
                'tpt_lahir'=>'sleman',
                'tgl_lahir'=>'2000-01-01',
                'email' => 'admin@gmail.com',
                'nip' => '00001',
                'no_tlp' => '085000002001',
                'user_name' => 'admin',
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ] 
        ];

        User::insert($users);
    }
}
