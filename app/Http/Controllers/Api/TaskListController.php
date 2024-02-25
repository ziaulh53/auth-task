<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // /** @var User $user */
        // $user = Auth::user();
        // $boards = $user->boards;
        /** @var User $user */
        $user = Auth::user();
        $lists = $user->ownedBoards()->with('lists')->get()->pluck('lists')->flatten();
        return response()->json(['lists' => $lists], 200);
    }

    public function taskListOnBoard(string $id)
    {
        $lists = TaskList::where('board_id', $id)->with('cards')->get();;
        return response()->json(['lists' => $lists], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'board_id' => 'required|exists:boards,id',
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'msg'=>'Bad Input'], 201);
        }

        TaskList::create([
            'board_id' => $request->board_id,
            'title' => $request->title,
        ]);

        return response(['success' => true, 'msg' => 'List created.']);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $list = TaskList::findOrFail($id);
            if($request['title']){
                $data['title'] = $request['title'];
                $list->update($data);
                return response(['success' => true, 'msg' => 'Updated']);
            }
            return response(['success' => false, 'msg' => 'Title is empty']);
        } catch (ModelNotFoundException $exception) {
            return response(['success' => false, 'msg' => 'List not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $list = TaskList::findOrFail($id);
            $list->delete();
            return response(['success' => true, 'msg' => 'Deleted']);
        } catch (ModelNotFoundException $exception) {
            return response(['success' => false, 'msg' => 'List not found'], 404);
        }
    }
}
