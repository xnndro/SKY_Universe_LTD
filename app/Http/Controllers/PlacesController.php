<?php

namespace App\Http\Controllers;

use App\Models\Places;
use Illuminate\Http\Request;

class PlacesController extends Controller
{
    public function index()
    {
        $places = Places::all();
        return view('location', compact('places'));
    }

    public function getPlacesByLocation(Request $request)
    {
        $location = $request->input('location');

        // Fetch places from the database based on the selected location
        $places = Places::where('location', $location)->get();

        return response()->json($places);
    }
}

