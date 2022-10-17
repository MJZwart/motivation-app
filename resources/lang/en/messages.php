<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Messages Language Lines
    |--------------------------------------------------------------------------
    |
    | 
    |
    */

    'task' => [
        'created' => 'Task created.',
    ],

    'achievement' => [
        'created' => 'Achievement added.',
        'updated' => 'Achievement updated.',
    ],

    'exp' => [
        'updated' => 'Experience points updated',
        'added' => 'Level added',
        'char_updated' => 'Character experience balancing updated',
        'vill_updated' => 'Village experience balancing updated',
    ],

    'user' => [
        'ban' => [
            'until' => 'User banned until :time',
            'ended_notification_title' => 'Your suspension has been lifted.',
            'ended_notification' => ':admin has ended your suspension. Reason given: :comment. You were originally banned for: :reason',
            'login_notification' => 'You are banned until :time. Reason: :reason. If you wish to dispute your ban, contact one of the admins on our Discord. Time remaining: :remaining.'
        ],
        'password_reset' => [
            'link_sent' => 'Success, if an account with this e-mail exists, we have sent you an e-mail with the link to reset your password. Check your spam folder if you cannot find our email.',
            'link_error' => 'Something went wrong. Try again later or contact an admin.',
            'reset_success' => 'Password changed. Login with your new password',
            'invalid_token' => 'Invalid token. Please revisit the original link from your email and try again.',
            'invalid_user' => 'Invalid user. Check your e-mailaddress and try again.',
            'reset_error' => 'Something went wrong. Try again later or contact an admin.',
        ],
    ],

    'feedback' => [
        'archived' => 'Feedback archived',
        'unarchived' => 'Feedback unarchived',
        'created' => 'Thank you for your feedback. We will contact you if we have any further questions or remarks.',
    ],

    'bug' => [
        'created' => 'Bug report successfully created.',
        'updated' => 'Bug Report updated.'
    ],

    'friend' => [
        'deleted' => 'Friend removed.',
        'request' => [
            'already_sent' => 'You\'ve already sent a friend request to this user',
            'already_accepted' => 'You have already accepted this request.',
            'already_accepted_other' => 'Friend request already accepted',
            'unable_to_send' => 'Unable to send a friend request to this user.',
            'blocked' => 'You have blocked this user.',
            'notification_title' => 'New friend request!',
            'notification_body' => 'You have a new friend request from :name. Would you like to accept?',
            'sent' => 'Friend request successfully sent.',
            'accepted' => 'Friend request accepted. You are now friends.',
            'denied' => 'Friend request denied.',
            'cancelled' => 'Friend request cancelled.',
        ],
    ],

    'group' => [
        'created' => 'Your group :name has been created.',
        'deleted' => 'Your group :name has been deleted.',
        'not_public' => 'This group is not public.',
        'needs_application' => 'This group needs an application to join.',
        'no_application' => 'This group does not require applications to join.',
        'already_applied' => 'You already have a pending application for this group.',
        'banned' => 'You are banned from this group.',
        'already_member' => 'You are already a member of this group.',
        'join_success' => 'You successfully joined the group :name.',
        'new_application_title' => 'New group application to :name.',
        'new_application_text' => ':username has applied to your group :groupname. Head to the details of :groupname and click on "Manage Applications" to accept or reject the application.',
    ]

];
