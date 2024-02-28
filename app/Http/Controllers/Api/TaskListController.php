<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
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
        return response(['lists' => $lists], 200);
    }

    public function taskListOnBoard(string $id)
    {
        $lists = TaskList::where('board_id', $id)->with('cards.assignedUsers')->orderBy('order')->get();
        return response(['lists' => $lists], 200);
    }

    public function updateListOrder(Request $request)
    {
        foreach ($request->input('lists') as $index => $listId) {
            TaskList::where('id', $listId)->update(['order' => $index]);
        }
        return response(['success' => true]);
    }

    public function updateCardOrder(Request $request)
    {
        $validatedData = $request->validate([
            'fromListId' => 'required|integer',
            'toListId' => 'required|integer',
            'oldIndex' => 'required|integer',
            'newIndex' => 'required|integer',
        ]);

        $fromListId = $validatedData['fromListId'];
        $toListId = $validatedData['toListId'];
        $oldIndex = $validatedData['oldIndex'];
        $newIndex = $validatedData['newIndex'];

        try {
            $card = Card::where('task_list_id', $fromListId)
                ->orderBy('order')
                ->offset($oldIndex)
                ->firstOrFail();

            if ($fromListId !== $toListId) {
                $this->updateOrdersOnListChange($card, $fromListId, $toListId, $newIndex);
            } else {
                $this->updateOrdersWithinList($card, $oldIndex, $newIndex);
            }

            return response()->json(['success' => true, 'msg' => 'Card order updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function updateOrdersOnListChange(Card $card, $fromListId, $toListId, $newIndex)
    {
        // Determine if the card is moving up or down in the same list
        $movingUp = $card->task_list_id === $toListId && $card->order > $newIndex;

        // Decrement order of cards after the removed card in the old list
        Card::where('task_list_id', $fromListId)
            ->where('order', '>', $card->order)
            ->orderBy('order')
            ->decrement('order');

        // Increment order of cards after the new index in the new list
        Card::where('task_list_id', $toListId)
            ->where('order', '>=', $newIndex)
            ->orderBy('order')
            ->increment('order');

        // If moving up in the same list, increment order of cards before the new index
        if ($movingUp) {
            Card::where('task_list_id', $toListId)
                ->whereBetween('order', [$newIndex, $card->order - 1])
                ->orderBy('order')
                ->increment('order');
        }

        // Update the card's task_list_id and order
        $card->task_list_id = $toListId;
        $card->order = $newIndex;
        $card->save();
    }
    private function updateOrdersWithinList(Card $card, $oldIndex, $newIndex)
    {
        // Determine direction of movement (up or down)
        $direction = $oldIndex < $newIndex ? 'down' : 'up';

        // Retrieve cards to update within the same list
        $cardsToUpdate = Card::where('task_list_id', $card->task_list_id)
            ->whereBetween('order', [$direction === 'down' ? $oldIndex : $newIndex, $direction === 'down' ? $newIndex : $oldIndex])
            ->orderBy('order')
            ->get();

        // Update card order based on direction
        foreach ($cardsToUpdate as $cardToUpdate) {
            $cardToUpdate->order += $direction === 'down' ? -1 : 1;
            $cardToUpdate->save();
        }

        // Update order of the moved card
        $card->order = $newIndex;
        $card->save();
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();
        $validator = Validator::make($request->all(), [
            'board_id' => 'required|exists:boards,id',
            'title' => 'required|string|max:255',
            'order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response(['success' => false, 'msg' => 'Bad Input'], 201);
        }

        TaskList::create([
            'board_id' => $request->board_id,
            'title' => $request->title,
            'order' => $request->order,
            'user_id' => $userId
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
            if ($request['title']) {
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
