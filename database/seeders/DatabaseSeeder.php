<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shift;
use App\Models\Service;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Shift::firstOrCreate(['code' => 'M'], ['name' => 'Matutino']);
        Shift::firstOrCreate(['code' => 'V'], ['name' => 'Vespertino']);

    // Servicios hospitalización / urgencias / toco / UCI, etc.
        $services = [
            ['name' => 'UCIN',                       'short_name' => 'UCIN',      'installed_capacity' => 24, 'area' => 'hospitalizacion'],
            ['name' => 'CIRUGÍA GENERAL',           'short_name' => 'CG',        'installed_capacity' => 32, 'area' => 'hospitalizacion'],
            ['name' => 'GINECOLOGIA-OBSTETRICIA',   'short_name' => 'GINE',      'installed_capacity' => 45, 'area' => 'hospitalizacion'],
            ['name' => 'UCINEX',                    'short_name' => 'UCINEX',    'installed_capacity' => 10, 'area' => 'hospitalizacion'],
            ['name' => 'ESCOLARES',                 'short_name' => 'ESC',       'installed_capacity' => 34, 'area' => 'hospitalizacion'],
            ['name' => 'LACTANTES',                 'short_name' => 'LAC',       'installed_capacity' => 0,  'area' => 'hospitalizacion'],
            ['name' => 'MEDICINA INTERNA',          'short_name' => 'MI',        'installed_capacity' => 47, 'area' => 'hospitalizacion'],
            ['name' => 'INFECTOLOGIA',              'short_name' => 'INF',       'installed_capacity' => 6,  'area' => 'hospitalizacion'],
            ['name' => 'URGENCIAS PEDIATRIA',       'short_name' => 'URG_PED',   'installed_capacity' => 7,  'area' => 'hospitalizacion'],
            ['name' => 'URGENCIAS ADULTOS',         'short_name' => 'URG_AD',    'installed_capacity' => 24, 'area' => 'hospitalizacion'],
            ['name' => 'TOCO CIRUGIA',              'short_name' => 'TOCO',      'installed_capacity' => 31, 'area' => 'toco'],
            ['name' => 'UTIP',                      'short_name' => 'UTIP',      'installed_capacity' => 4,  'area' => 'hospitalizacion'],
            ['name' => 'UTI',                       'short_name' => 'UTI',       'installed_capacity' => 4,  'area' => 'hospitalizacion'],
            ['name' => 'UCI',                       'short_name' => 'UCI',       'installed_capacity' => 4,  'area' => 'hospitalizacion'],
            ['name' => 'RECUPERACION QX GERIATRIA', 'short_name' => 'REC_GER',   'installed_capacity' => 6, 'area' => 'recuperacion'],
            ['name' => 'RECUPERACION QX 5TO PISO',  'short_name' => 'REC_5',     'installed_capacity' => 10, 'area' => 'recuperacion'],
        ];

        foreach ($services as $s) {
            Service::firstOrCreate(['name' => $s['name']],$s);
        }
    }
}
