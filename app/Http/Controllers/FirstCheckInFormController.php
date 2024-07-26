<?php

namespace App\Http\Controllers;

use App\Http\Requests\FirstCheckInFormRequest;
use App\Models\BodyImage;
use function App\Http\Helpers\uploadImage;

class FirstCheckInFormController extends Controller
{
    public function store(FirstCheckInFormRequest $request)
    {
        $validated = $request->validated();
        unset($validated['injuries_image']);
        unset($validated['body_images']);
        // Decode the JSON strings back into arrays
        $validated['food_you_dont_like'] = json_decode($validated['food_you_dont_like']);
        $validated['available_tool_in_home'] = json_decode($validated['available_tool_in_home']);
        $user = auth()->user();

        if ($request->hasFile('injuries_image')) {
            $path = uploadImage($request->file('injuries_image'), 'public', 'injuries_image');
        }

        if ($user->firstCheckInForm()->exists()) {
            $user->firstCheckInForm()->update($validated);
            $firstCheckInForm = $user->firstCheckInForm()->first();
        } else {
            $firstCheckInForm = $user->firstCheckInForm()->create($validated);
        }

        if ($request->has('body_images')) {
            foreach ($request->file('body_images') as $file) {
                $path = uploadImage($file, 'public', 'body_images');
                BodyImage::query()->create([
                    'image' => $path,
                    'first_check_in_form_id' => $firstCheckInForm->id,
                ]);
            }
        }


        return response()->json(['message' => 'First check-in form submitted successfully']);
    }
}
