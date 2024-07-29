<?php

namespace App\Http\Controllers\ChechIn;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckIn\FirstCheckInFormRequest;
use App\Http\Resources\Dashboard\CheckIn\CheckInNutritionResource;
use App\Http\Resources\Dashboard\CheckIn\CheckInWorkOutResource;
use App\Http\Resources\Dashboard\CheckIn\FirstCheckInResource;
use App\Models\BodyImage;
use function App\Http\Helpers\uploadImage;

class FirstCheckInFormController extends Controller
{
    public function store(FirstCheckInFormRequest $request)
    {


        $validated = $request->validated();
        unset($validated['injuries_image']);
        unset($validated['body_images']);
        $user = auth()->user();


        if ($request->hasFile('injuries_image')) {
            $path = uploadImage($request->file('injuries_image'), 'public', 'injuries_image');
            $validated['injuries_image'] = $path;
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

    public function getAllClientCheckInForms()
    {
        $user = auth()->user();
        $firstCheckInForm = $user->firstCheckInForm()->with('bodyImages')->first();
        $checkIn = $user->checkIn()->with('bodyImages')->get();
        $checkInWorkout = $user->checkInWorkout()->get();
        return response()->json([
            'first_check_in_form' => FirstCheckInResource::make($firstCheckInForm),
            'check_in' => CheckInNutritionResource::collection($checkIn),
            'check_in_workout' => CheckInWorkOutResource::collection($checkInWorkout),
        ]);
    }
}
