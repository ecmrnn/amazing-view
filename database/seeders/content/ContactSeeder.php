<?php

namespace Database\Seeders\content;

use App\Models\ContactDetails;
use App\Models\Content;
use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heading = Content::create([
            'name' => 'contact_heading',
            'type' => 'text',
            'value' => 'Got any business idea? <br> Send an Email!'
        ]);

        $subheading = Content::create([
            'name' => 'contact_subheading',
            'type' => 'text',
            'value' => 'You may reach us at the following phone numbers or you may opt to send an email using the given form.'
        ]);

        ContactDetails::create([
            'name' => 'phone_number',
            'type' => 'phone',
            'value' => '09171399334'
        ]);

        ContactDetails::create([
            'name' => 'phone_number',
            'type' => 'phone',
            'value' => '09051620527'
        ]);

        ContactDetails::create([
            'name' => 'phone_number',
            'type' => 'phone',
            'value' => '09451320863'
        ]);

        $page = Page::whereTitle('Contact')->first();
        $page->contents()->attach([
            $heading->id,
            $subheading->id,
        ]);
    }
}
