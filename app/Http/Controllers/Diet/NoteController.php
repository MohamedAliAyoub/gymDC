<?php

namespace App\Http\Controllers\Diet;

use App\Http\Controllers\Controller;
use App\Models\Diet\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/diet/note",
     *     summary="Retrieve all notes",
     *     @OA\Response(response="200", description="Notes retrieved successfully")
     * )
     */
    public function index()
    {
        $notes = Note::query()->paginate(15);

      return $this->paginateResponse($notes, 'Notes retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/diet/note",
     *     summary="Create a new note",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Note's title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="Note's content",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Note's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="plan_id",
     *         in="query",
     *         description="Plan id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="meal_id",
     *         in="query",
     *         description="Meal id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Note created successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */

    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'title' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|boolean',
            'plan_id' => 'nullable|integer',
            'meal_id' => 'nullable|integer',
        ]);

        $note = Note::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Note created successfully',
            'note' => $note,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/diet/note/{note}",
     *     summary="Update an existing note",
     *     @OA\Parameter(
     *         name="user_id",
     *         in="query",
     *         description="User id",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Note's title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="content",
     *         in="query",
     *         description="Note's content",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Note's status",
     *         required=true,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Parameter(
     *         name="plan_id",
     *         in="query",
     *         description="Plan id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="meal_id",
     *         in="query",
     *         description="Meal id",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Note updated successfully"),
     *     @OA\Response(response="400", description="Validation errors")
     * )
     */
    public function update(Request $request, $id)
    {
        try {
            $note = Note::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Note not found',
            ], 404);
        }
        $request->validate([
            'user_id' => 'nullable|integer',
            'title' => 'nullable|string',
            'content' => 'required|string',
            'status' => 'required|boolean',
            'plan_id' => 'nullable|integer|exists:plans,id',
            'meal_id' => 'nullable|integer|exists:meals,id',
        ]);

        $note->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Note updated successfully',
            'note' => $note,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/diet/note/{note}",
     *     summary="Retrieve a note",
     *     @OA\Response(response="200", description="Note retrieved successfully"),
     *     @OA\Response(response="404", description="Note not found")
     * )
     */
    public function show($id)
    {
        try {
            $note = Note::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Note not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Note retrieved successfully',
            'note' => $note,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/diet/note/{note}",
     *     summary="Delete a note",
     *     @OA\Response(response="200", description="Note deleted successfully"),
     *     @OA\Response(response="404", description="Note not found")
     * )
     */
    public function delete($id)
    {
        try {
            $note = Note::query()->findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Note not found',
            ], 404);
        }
        $note->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Note deleted successfully',
        ]);
    }
}
