<?php

namespace App\Http\Controllers;

use App\Models\ContactDetails;
use App\Models\Content;
use App\Models\FeaturedService;
use App\Models\Milestone;
use App\Models\RoomType;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home() {

        $heading = Content::whereName('home_heading')->pluck('value')->first();
        $home_hero_image = Content::whereName('home_hero_image')->pluck('value')->first();
        $subheading = Content::whereName('home_subheading')->pluck('value')->first();
        $history_image = Content::whereName('about_history_image')->pluck('value')->first();
        $history = Content::whereName('about_history')->pluck('long_value')->first();
        $featured_services = FeaturedService::select('image', 'title', 'description')
            ->whereStatus(FeaturedService::STATUS_ACTIVE)
            ->get();

        return view('index', [
            'heading' => html_entity_decode($heading),
            'subheading' => html_entity_decode($subheading),
            'home_hero_image' => $home_hero_image,
            'featured_services' => $featured_services,
            'history_image' => $history_image,
            'history' => $history,
        ]);
    }

    public function about() {
        $heading = Content::whereName('about_heading')->pluck('value')->first();
        $subheading = Content::whereName('about_subheading')->pluck('value')->first();
        $history = Content::whereName('about_history')->pluck('long_value')->first();
        $history_image = Content::whereName('about_history_image')->pluck('value')->first();
        $about_hero_image = Content::whereName('about_hero_image')->pluck('value')->first();
        $milestones = Milestone::all();

        return view('about', [
            'heading' => html_entity_decode($heading),
            'subheading' => html_entity_decode($subheading),
            'history' => html_entity_decode($history),
            'history_image' => $history_image,
            'about_hero_image' => $about_hero_image,
            'milestones' => $milestones,
        ]);
    }

    public function rooms() {
        $heading = Content::whereName('rooms_heading')->pluck('value')->first();
        $subheading = Content::whereName('rooms_subheading')->pluck('value')->first();
        $available_rooms = RoomType::all();

        return view('rooms', [
            'heading' => html_entity_decode($heading),
            'subheading' => html_entity_decode($subheading),
            'available_rooms' => $available_rooms
        ]);
    }

    public function contact() {
        $contact_hero_image = Content::whereName('contact_hero_image')->pluck('value')->first();
        $heading = Content::whereName('contact_heading')->pluck('value')->first();
        $subheading = Content::whereName('contact_subheading')->pluck('value')->first();
        $contact_details = ContactDetails::pluck('value');
        
        return view('contact', [
            'heading' => html_entity_decode($heading),
            'subheading' => html_entity_decode($subheading),
            'contact_details' => $contact_details,
            'contact_hero_image' => $contact_hero_image,
        ]);
    }
}
