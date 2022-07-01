<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ContentGuide;

class ContentGuideController extends Controller
{
    public function search(Request $request)
    {
        if ($request->id) {
            return ContentGuide::find($request->id);
        }
    }
}
