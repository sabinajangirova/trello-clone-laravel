<?php

namespace Database\Seeders;

use App\Models\Column;
use Illuminate\Database\Seeder;

class ColumnsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $columns = ['To do', 'In process', 'Done'];
        foreach ($columns as $column){
            Column::create(['name' => $column]);
        }
    }
}
