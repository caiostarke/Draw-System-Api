<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DrawController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $draw = new Draw();

        $draw->user_id = Auth::user()->id;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'body_content' => ['required', 'string'],
            'image' => 'required|image|max:2048',
        ]);

        if($request->hasFile('image')) {

            $requestImage = $request->image;

            $extension = $requestImage->extension();

            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->storeAs('imgs', $imageName, 'public');

            $draw->image = $imageName;
        }

        $draw->name = $request->name;
        $draw->body_content = $request->body_content; 

        $draw->save();
        

        return response()->json([
            'message' => 'Draw created successfully',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }

    public function index() {
        $draws = Draw::all();

        $draws->transform(function($draw) {
            $draw->image_url = Storage::url("imgs/" . $draw->image);
            return $draw;
        });
        
        return response()->json([
            'draws' => $draws,
        ]);
    }   
}
