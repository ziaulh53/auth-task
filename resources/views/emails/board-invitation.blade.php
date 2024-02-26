@component('mail::message')
# Invitation to Join a Board

You have been invited to join a board. Click the button below to accept the invitation.

@component('mail::button', ['url' => 'http:localhost:8000/invitations/accept/' . $invitation->token])
Accept Invitation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
