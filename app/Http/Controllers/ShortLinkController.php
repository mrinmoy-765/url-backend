<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shortlink;
use Illuminate\Support\Str;

class ShortLinkController extends Controller
{
     public function index()
    {
        $shortLinks = ShortLink::latest()->get();
        return response()->json(['shortLinks' => $shortLinks], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'link' => 'required|url'
        ]);

        $input['link'] = $request->link;
        $input['code'] = Str::random(6);

        $shortLink = ShortLink::create($input);

        return response()->json(['shortLink' => $shortLink], 201);
    }

    public function shortenLink($code)
    {
        $find = ShortLink::where('code', $code)->first();

        if ($find) {
            return response()->json(['link' => $find->link], 200);
        } else {
            return response()->json(['error' => 'Short link not found'], 404);
        }
    }
}
