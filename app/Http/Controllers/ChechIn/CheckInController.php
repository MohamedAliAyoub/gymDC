<?php

namespace App\Http\Controllers\ChechIn;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckIn\CheckInRequest;
use App\Models\BodyImage;
use App\Models\CheckIn\CheckIn;
use Illuminate\Http\JsonResponse;
use function App\Http\Helpers\uploadImage;

class CheckInController extends Controller
{
    public function store(CheckInRequest $request): JsonResponse
    {
        $validated = $request->validated();
        unset($validated['body_images']);
        $validated['user_id'] = auth()->id();
        if ($request->hasFile('in_body_image')) {
            $path = uploadImage($request->file('in_body_image'), 'public', 'in_body_image');
            $validated['in_body_image'] = $path;
        }
        $checkIn = CheckIn::query()->create($validated);

        if ($request->has('body_images')) {
            foreach ($request->file('body_images') as $file) {
                $path = uploadImage($file, 'public', 'body_images');
                BodyImage::query()->create([
                    'image' => $path,
                    'check_in_id' => $checkIn->id,
                ]);
            }
        }
        return response()->json(['message' => 'check-in form submitted successfully']);
    }

}
