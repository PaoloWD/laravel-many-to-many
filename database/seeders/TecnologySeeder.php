<?php

namespace Database\Seeders;

use App\Models\Tecnology;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TecnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tecnologies = [
            "Java",
            "Php",
            "C++",
            "JavaScript",
            "Css",
            "Html",
        ];

        foreach ($tecnologies as $tecnology) {
            $newTecnology = new Tecnology();
            $newTecnology->name = $tecnology;
            $newTecnology->description = "Descrizione tecnologia" . $tecnology;
            $newTecnology->save();
        }
    }
}