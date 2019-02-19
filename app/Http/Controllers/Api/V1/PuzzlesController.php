<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Image;
use App\Http\Resources\Puzzle;

class PuzzlesController extends Controller
{
    public function index()
    {
        $images = Image::with([])->get();

        //dd($images);

        return Puzzle::collection(
            $images
        );
    }
}
