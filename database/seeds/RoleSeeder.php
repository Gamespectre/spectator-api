<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert(
            [ 'name' => 'anon', 'level' => 'anon'],
            [ 'name' => 'user', 'level' => 'user'],
            [ 'name' => 'admin', 'level' => 'admin']
        );
    }
}
