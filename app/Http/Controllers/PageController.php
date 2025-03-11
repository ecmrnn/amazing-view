<?php

namespace App\Http\Controllers;

use App\Models\ContactDetails;
use App\Models\Content;
use App\Models\FeaturedService;
use App\Models\MediaFile;
use App\Models\Milestone;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\RoomType;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index() {
        $page = Page::whereUrl('/')->first();
        $contents = PageContent::where('page_id', $page->id)->pluck('value', 'key');
        $medias = MediaFile::where('page_id', $page->id)->pluck('path', 'key');
        $featured_services = FeaturedService::all();
        $testimonials = Testimonial::all();

        return view($page->view, [
            'page' => $page,
            'contents' => $contents,
            'medias' => $medias,
            'featured_services' => $featured_services,
            'testimonials' => $testimonials,
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
        $rooms_hero_image = Content::whereName('rooms_hero_image')->pluck('value')->first();
        $available_rooms = RoomType::all();

        return view('rooms', [
            'heading' => html_entity_decode($heading),
            'subheading' => html_entity_decode($subheading),
            'available_rooms' => $available_rooms,
            'rooms_hero_image' => $rooms_hero_image
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

    public function reservation() {
        $reservation_hero_image = Content::whereName('reservation_hero_image')->pluck('value')->first();
        $heading = Content::whereName('reservation_heading')->pluck('value')->first();
        $subheading = Content::whereName('reservation_subheading')->pluck('value')->first();
        
        return view('reservation', [
            'heading' => html_entity_decode($heading),
            'subheading' => html_entity_decode($subheading),
            'reservation_hero_image' => $reservation_hero_image,
        ]);
    }

    public function functionHall() {
        $reservation_hero_image = Content::whereName('reservation_hero_image')->pluck('value')->first();

        return view('function-hall', [
            'heading' => 'Function Hall',
            'subheading' => 'Make your events unforgettable with our elegant Function Hall',
            'reservation_hero_image' => $reservation_hero_image,
        ]);
    }
}
