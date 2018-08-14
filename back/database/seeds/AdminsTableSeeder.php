<?php

use App\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::create([
            'username' => 'admin',
            'password' => bcrypt('admin')
        ]);

        DB::table('admin_institution')->insert([
            'admin_id' => $admin->id,
            'institution_id' => 1
        ]);
    }
}
