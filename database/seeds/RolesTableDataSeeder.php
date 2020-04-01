<?php

use Illuminate\Database\Seeder;

class RolesTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'manager',
            ],
            [
                'id' => 2,
                'name' => 'client',
            ],
            
            
        ];

        foreach ($data as $key => $value) {
            DB::table('roles')->insert([
                'id' => $value['id'],
                'name' => $value['name'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
