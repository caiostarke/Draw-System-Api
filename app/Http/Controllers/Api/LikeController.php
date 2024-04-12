<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Draw;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LikeController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $like = new Like();
        
        $like->user_id = Auth::user()->id;

        $validated = $request->validate([
            'draw_id' => ['required', 'string'],
        ]);

        $draw = Draw::find($validated['draw_id']);

        if (!$draw) {
            return response()->json([
                'message' => 'Draw not found', 
            ], 404);
        }

        $like->draw_id = $validated['draw_id'];

        $hasLiked = Like::where('user_id', $like->user_id)->where('draw_id', $like->draw_id)->first();

        if ($hasLiked) {
            $like->delete();

            $hasLiked->delete();

            return response()->json([
                'message' => 'You unliked this draw succesfully',
            ]);
        }

        $like->save();

        return response()->json([
            'message' => 'You liked a draw Successfully',
            ]);
        }   

    /**
     * The index function retrieves all liked draws from the logged user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $likes = Auth::user()->likes;
    
        if ($likes) {
            $draws = Draw::whereIn('id', $likes->pluck('draw_id'))->get();

            return response()->json([
                'draws' => $draws,
            ]);
        }       

        return response()->json([
            'message' => 'No liked draws found'
        ]);
    }

}
