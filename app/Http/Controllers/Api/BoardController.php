<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $id = Auth::id();
        $boards = Board::query()->where('user_id', $id)->get();
        return response(['success' => true, 'boards' => $boards]);
    }


    public function show(string $id)
    {
        // $user = Auth::user();
        $board = Board::findOrFail($id);

        return response(['success' => true, 'board' => $board]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response(['success' => false, 'msg' => 'Bad input!']);
        }
        $board = new Board();
        $board->title = $request->title;
        $board->user_id = Auth::id();
        $board->save();
        return response(['success' => true, 'msg' => 'Project created.']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $board = Board::findOrFail($id);
            if($request['title']){
                $data['title'] = $request['title'];
                $board->update($data);
                return response(['success' => true, 'msg' => 'Updated']);
            }
            return response(['success' => false, 'msg' => 'Title is empty']);
        } catch (ModelNotFoundException $exception) {
            return response(['success' => false, 'msg' => 'Board not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $board = Board::findOrFail($id);
            $board->delete();
            return response(['success' => true, 'msg' => 'Deleted']);
        } catch (ModelNotFoundException $exception) {
            return response(['success' => false, 'msg' => 'Board not found'], 404);
        }
    }
}
