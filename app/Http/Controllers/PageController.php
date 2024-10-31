<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\FeaturedService;
use App\Models\Milestone;
use App\Models\RoomType;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function home() {

        $heading = Content::whereName('home_heading')->pluck('value')->first();
        $subheading = Content::whereName('home_subheading')->pluck('value')->first();
        $history_image = Content::whereName('about_history_image')->pluck('value')->first();
        $history = Content::whereName('about_history')->pluck('long_value')->first();
        $featured_services = FeaturedService::select('image', 'title', 'description')
            ->whereStatus(FeaturedService::STATUS_ACTIVE)
            ->get();

        return view('index', [
            'heading' => html_entity_decode($heading),
            'subheading' => html_entity_decode($subheading),
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
        $milestones = Milestone::all();

        return view('about', [
            'heading' => html_entity_decode($heading),
            'subheading' => html_entity_decode($subheading),
            'history' => html_entity_decode($history),
            'history_image' => html_entity_decode($history_image),
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
}
