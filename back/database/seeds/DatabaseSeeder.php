<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(InstitutionsTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(FoldersTableSeeder::class);
        $this->call(SessionsTableSeeder::class);
        $this->call(CohortsTableSeeder::class);
    }
}
