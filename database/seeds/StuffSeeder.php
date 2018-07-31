<?php

use App\Stuff;
use Illuminate\Database\Seeder;

class StuffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stuff = ['телефон','ноутбук','машина','квартира'];

        foreach ($stuff as $value){
            $item = new Stuff;
            $item->name = $value;
            $item->save();
        }

    }
}
