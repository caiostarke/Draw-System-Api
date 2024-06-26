<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use Illuminate\Http\Request;
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

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'body_content' => ['required', 'string'],
            'image' => 'required|image|max:2048',
        ]);

        if($request->hasFile('image')) {
            $imageName = $this->createImagePathAndSave($validated['image']);

            $draw->image = $imageName;
        }

        $draw->name = $validated['name'];
        $draw->body_content = $validated['body_content']; 

        $draw->save();
        

        return response()->json([
            'message' => 'Draw created successfully',
        ]);
    }

    public function destroy($id)
    {
        $draw = Auth::user()->draws->find($id);

        if ($draw) {
            $draw->delete();
            return response()->json([
                'message' => 'Draw deleted successfully',
            ]);
        }

        return response()->json([
            'message' => 'Draw not found',
        ]);
    }   

    public function index() {
        $draws = Draw::all();

        if (!Auth::user()) {
            return response()->json($draws);
        }

        $transformedDraws = $draws->map(function($draw) {
            return [
                'id' => $draw->id,
                'name' => $draw->name,
                'body_content' => $draw->body_content,
                'image_url' => $draw->image_url,
                'likes_count' => $draw->likes_count,
                'liked' => $draw->userHasLiked(Auth::user()),
            ];  
        });

        return response()->json($transformedDraws);
    }

    public function update(Request $request, $id) {
        $user = Auth::user();
        $draw = $user->draws->find($id);

        if (!$draw) {
            return response()->json([
                'message' => 'Draw not found', 
            ], 404);
        }

        $validated = $request->validate([
            'name' => ['string', 'max:255'],
            'body_content' => ['string'],
            'image' => 'image|max:2048',
        ]);

        if($request->hasFile('image')) {
            $imageName = $this->createImagePathAndSave($validated['image']);

            $draw->image = $imageName;
        }

        $draw->name = $validated['name'];
        $draw->body_content = $validated['body_content'];

        $draw->save();

        return response()->json([
            'message' => 'Draw updated successfully',
        ]);

    }

    public function show($id) {
        $draw = Draw::find($id);

        if (!$draw) {
            return response()->json([
                'message' => 'Draw not found', 
            ], 404);
        }   

        return response()->json([
            'draw' => $draw,
        ]);

    }

    private function createImagePathAndSave($image) {
        $imageName = md5($image->getClientOriginalName() . strtotime("now")) . "." . $image->extension();

        $image->storeAs('imgs', $imageName, 'public');
        
        return $imageName;
    }



}
