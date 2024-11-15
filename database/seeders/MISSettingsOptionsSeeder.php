<?php

namespace Database\Seeders;

use App\Models\MisStaffSettingsOption;
use Illuminate\Database\Seeder;

class MISSettingsOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MisStaffSettingsOption::create([
            'key' => 'nomer-sertifikata-polzovatelya',
            'property' => 'Номер сертификата пользователя',
            'setting_type' => 7,
            'label' => 'Номер сертификата пользователя',
        ]);

        MisStaffSettingsOption::create([
            'key' => 'sertifikat-deystvitelen-s',
            'property' => 'Сертификат действителен с',
            'setting_type' => 7,
            'label' => 'Сертификат действителен с',
        ]);

        MisStaffSettingsOption::create([
            'key' => 'sertifikat-deystvitelen-po',
            'property' => 'Сертификат действителен по',
            'setting_type' => 7,
            'label' => 'Сертификат действителен по',
        ]);
    }
}
