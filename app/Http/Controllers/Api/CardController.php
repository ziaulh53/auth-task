<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $validator = Validator::make($request->all(), [
            'task_list_id' => 'required|exists:task_lists,id',
            'title' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

         Card::create([
            'task_list_id' => $request->task_list_id,
            'title' => $request->title,
            'user_id'=>$userId,
            'priority'=>'Low',
            'order'=> $request->order
        ]);
        return response(['success'=>true, 'msg'=>'Card created']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $card =  Card::with('assignedUsers')->find($id);
        $boardId = $request->query('board_id');
        $board = Board::query()->find($boardId);
        $members = $board->members()->get();
        return response(['success'=>true, 'card'=>$card, 'members'=>$members]);
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
        $validatedData = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'priority' => 'nullable|in:Low,Medium,High',
        ]);
    
        $card = Card::findOrFail($id);
    
        $card->fill($validatedData);
        $card->save();
        return response(['success' => true, 'msg' => 'Card updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $card = Card::findOrFail($id);
        $card->delete();
        return response(['success' => true, 'msg' => 'Deleted']);
    }

    public function assignMember(Request $request)
    {
        $card = Card::findOrFail($request->card_id);
        $user = User::findOrFail($request->user_id);
        // Check if the card and user exist
        if (!$card || !$user) {
            return response(['success' => false, 'msg' => 'Card or user not found.'], 404);
        }
        if ($card->assignedUsers()->where('user_id', $user->id)->exists()) {
            return response(['success' => false, 'msg' => 'User is already assigned to this card.'], 400);
        }

        $card->assignedUsers()->attach($user->id);

        return response(['success' => true, 'msg' => 'User assigned to the card successfully.'], 200);
    }

    public function removeMemberFromCard(Request $request)
    {
        $card = Card::findOrFail($request->card_id);
        $user = User::findOrFail($request->user_id);
        if (!$card || !$user) {
            return response(['success' => false, 'msg' => 'Card or user not found.'], 404);
        }

        if (!$card->assignedUsers()->where('user_id', $user->id)->exists()) {
            return response(['success' => false, 'msg' => 'User is not assigned to this card.'], 400);
        }

        $card->assignedUsers()->detach($user->id);

        return response(['success' => true, 'msg' => 'User removed from the card successfully.'], 200);
    }

    public function updateCardLabel(Request $request){
        $request->validate([
            'card_id' => 'required|exists:cards,id',
            'labels' => 'array',
        ]);
    
        try {
            $card = Card::findOrFail($request->card_id);
            $card->update(['labels' => $request->labels]);
            return response(['success' => true, 'msg' => 'Labels updated successfully']);
        } catch (\Exception $e) {
            return response(['success' => false, 'msg' => 'Failed to update labels: ' . $e->getMessage()], 500);
        }
    }
}
