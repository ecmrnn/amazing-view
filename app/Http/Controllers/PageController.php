<?php

namespace App\Http\Controllers;

use App\Enums\FeaturedServiceStatus;
use App\Enums\MilestoneStatus;
use App\Enums\TestimonialStatus;
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
        $featured_services = FeaturedService::where('status', FeaturedServiceStatus::ACTIVE)->get();
        $testimonials = Testimonial::where('status', TestimonialStatus::ACTIVE)->get();

        return view($page->view, [
            'page' => $page,
            'contents' => $contents,
            'medias' => $medias,
            'featured_services' => $featured_services,
            'testimonials' => $testimonials,
        ]);
    }

    public function about() {
        $page = Page::whereUrl('/about')->first();
        $contents = PageContent::where('page_id', $page->id)->pluck('value', 'key');
        $medias = MediaFile::where('page_id', $page->id)->pluck('path', 'key');
        $milestones = Milestone::where('status', MilestoneStatus::ACTIVE)->get();

        $map = array(
            'option' => [
                'center' => [
                    'lat' => 14.442312,
                    'lng' => 121.396931
                ],
                'zoom' => 14,
                'zoomControl' => true,
                'minZoom' => 10,
                'maxZoom' => 18,
            ],
            'marker' => [
                [
                    'position' => [
                        'lat' => 14.442312,
                        'lng' => 121.396931
                    ],
                    'draggable' => false,
                ]
            ]
        );

        return view('about', [
            'contents' => $contents,
            'medias' => $medias,
            'milestones' => $milestones,
            'map' => $map,
        ]);
    }

    public function rooms() {
        $page = Page::whereUrl('/rooms')->first();
        $contents = PageContent::where('page_id', $page->id)->pluck('value', 'key');
        $medias = MediaFile::where('page_id', $page->id)->pluck('path', 'key');
        $rooms = RoomType::all();

        return view('rooms', [
            'contents' => $contents,
            'medias' => $medias,
            'rooms' => $rooms,
        ]);
    }

    public function contact() {
        $page = Page::whereUrl('/contact')->first();
        $contents = PageContent::where('page_id', $page->id)->pluck('value', 'key');
        $medias = MediaFile::where('page_id', $page->id)->pluck('path', 'key');
        $contact_details = $page->contents->where('key', 'phone_number');
        
        return view('contact', [
            'contents' => $contents,
            'medias' => $medias,
            'contact_details' => $contact_details,
        ]);
    }

    public function reservation() {
        $page = Page::whereUrl('/reservation')->first();
        $contents = PageContent::where('page_id', $page->id)->pluck('value', 'key');
        $medias = MediaFile::where('page_id', $page->id)->pluck('path', 'key');
        
        return view('reservation', [
            'contents' => $contents,
            'medias' => $medias,
        ]);
    }

    public function functionHall() {
        $page = Page::whereUrl('/function-hall')->first();
        $contents = PageContent::where('page_id', $page->id)->pluck('value', 'key');
        $medias = MediaFile::where('page_id', $page->id)->pluck('path', 'key');

        return view('function-hall', [
            'contents' => $contents,
            'medias' => $medias,
        ]);
    }

    public function findReservation() {
        $page = Page::whereUrl('/search')->first();
        $contents = PageContent::where('page_id', $page->id)->pluck('value', 'key');

        return view('search', [
            'contents' => $contents,
        ]);
    }

}
