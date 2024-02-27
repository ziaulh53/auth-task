<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\BoardInvitation;
use App\Models\Board;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    public function invite(Request $request)
    {
        $boardId = $request->input('board_id');
        $board = Board::findOrFail($boardId);
        $request->validate([
            'email' => 'required|email',
        ]);

        if ($board->members()->where('email', $request->email)->exists()) {
            return response()->json(['success' => false, 'msg' => 'User is already a member of this board.'], 400);
        }
        if ($board->invitations()->where('email', $request->email)->exists()) {
            return response()->json(['success' => false, 'msg' => 'User has already been invited to this board.'], 400);
        }

        $invitation = Invitation::create([
            'board_id' => $board->id,
            'email' => $request->email,
            'token' => bin2hex(random_bytes(32)),
        ]);

        Mail::to($request->email)->send(new BoardInvitation($invitation));
        return response(['success' => true, 'msg' => 'Invitation sent successfully.'], 200);
    }

    public function accept(Request $request){
        $invitation = Invitation::where('token', $request->token)->firstOrFail();
        $user = User::where('email', $invitation->email)->firstOrFail();
        $invitation->board->members()->attach($user);
        $invitation->delete();
        return response(['success' => true, 'msg' => 'Invitation accepted successfully.', 'board_id'=>$invitation->board_id], 200);
    }

    public function getUserInvitations(){
        $user = Auth::user();
        $invitations = Invitation::query()->where('email', $user->email)->with('board')->get();
        return response(['success'=>false, 'invitations'=>$invitations]);
    }

    public function cancelInvitation(Request $request)
    {
        // Check if the invitation exists
        $invitation = Invitation::findOrFail($request->invitation_id);
        if (!$invitation) {
            return response()->json(['success' => false, 'message' => 'Invitation not found.'], 404);
        }

        // Delete the invitation
        $invitation->delete();

        return response()->json(['success' => true, 'msg' => 'Invitation canceled successfully.'], 200);
    }

}
