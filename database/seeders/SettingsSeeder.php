<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = collect([
            [
                'key' => 'site_logo',
                'value' => 'global/application-logo.png',
                'type' => 'image',
            ],
            [
                'key' => 'site_title',
                'value' => 'Amazing View Mountain Resort',
                'type' => 'text',
            ],
            [
                'key' => 'site_tagline',
                'value' => 'Experience Serenity and Luxury here in Amazing View!',
                'type' => 'text',
            ],
            [
                'key' => 'site_phone',
                'value' => '09171399334',
                'type' => 'text',
            ],
            [
                'key' => 'site_email',
                'value' => 'reservation@amazingviewresort.com',
                'type' => 'text',
            ],]
        );

        foreach ($settings as $setting) {
            Settings::create($setting);
        }
    }
}
