<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function App\Http\Helpers\uploadImage;

class UserController extends Controller
{
    public function assignUserDetailsOfClient(Request $request):JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'age' => 'required|numeric',
            'activity_level' => 'required|numeric',
            'in_body' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        // Upload the profile picture and get the path
        //TODO activity_level add it in the database
        if ($request->hasFile('in_body'))
            $path = uploadImage($request->file('in_body'), 'public', 'clients/in_body');
        else
            $path = null;
        UserDetails::query()->create([
            'user_id' => $request->user_id,
            'height' => $request->height,
            'weight' => $request->weight,
            'age' => $request->age,
            'activity_level' => $request->activity_level,
            'in_body' => $path,
        ]);
        return response()->json(['message' => 'User details assigned successfully']);

    }
}
