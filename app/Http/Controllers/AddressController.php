<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class AddressController extends Controller
{
    public static function getRegions() {
        $response = Http::get('https://psgc.cloud/api/regions');

        if ($response->successful()) {
            return $response->json();
        }
    }

    public static function getProvinces($region) {
        $response = Http::get('https://psgc.cloud/api/regions/' . $region . '/provinces');

        if ($response->successful()) {
            return $response->json();
        }
    }

    public static function getCities($province) {
        $response = Http::get('https://psgc.cloud/api/provinces/' . $province . '/cities-municipalities');

        if ($response->successful()) {
            return $response->json();
        }
    }

    public static function getBaranggays($city) {
        $response = Http::get('https://psgc.cloud/api/cities-municipalities/' . $city . '/barangays');

        if ($response->successful()) {
            return $response->json();
        }
    }

    public static function getDistricts() {
        $response = Http::get('https://psgc.cloud/api/sub-municipalities');

        if ($response->successful()) {
            return $response->json();
        }
    }

    public static function getDistrictBaranggays($district) {
        $districts = AddressController::getDistricts();
        $district_code = 0;

        foreach ($districts as $district_obj) {
            if ($district_obj['name'] == $district) {
                $district_code = $district_obj['code'];
            }
        }

        $path = "https://psgc.cloud/api/sub-municipalities/" . $district_code . "/barangays";
        $response = Http::get('https://psgc.cloud/api/sub-municipalities/' . $district_code . '/barangays');

        if ($response->successful()) {
            return $response->json();
        }
    }
}
