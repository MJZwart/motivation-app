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
        'updated' => 'Task successfully updated.',
        'deleted' => 'Task deleted',
        'completed' => 'Task completed.',
        'unauthorized' => 'This is not your task',
        'template' => [
            'created' => 'Template created',
            'updated' => 'Template updated',
            'deleted' => 'Template deleted',
        ],
    ],

    'tasklist' => [
        'created' => 'Task list successfully created.',
        'updated' => 'Task list updated.',
        'deleted' => 'Task list deleted',
        'unauthorized' => 'This is not your task list',
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
        'blocked' => 'User blocked',
        'unblocked' => 'User has been unblocked',
        'created' => 'You have successfully registered. You can now login with your chosen username.',
        'setup' => 'You have successfully set up your account.',
        'profile_unavailable' => 'Unable to view this user\'s profile.',
        'not_admin' => 'You are not admin.',
        'email_updated' => 'Your email has been changed.',
        'password_updated' => 'Your password has been updated. Please log in using your new password.',
        'settings_updated' => 'Your settings have been changed.',
        'language_updated' => 'Your language has been changed.',
        'reward_updated' => 'Your rewards type has been changed.',
        'reported' => 'User reported',
    ],

    'feedback' => [
        'archived' => 'Feedback archived',
        'unarchived' => 'Feedback unarchived',
        'created' => 'Thank you for your feedback. We will contact you if we have any further questions or remarks.',
    ],

    'bug' => [
        'created' => 'Bug report successfully created.',
        'updated' => 'Bug Report updated.',
        'resolved_title' => 'Your bug report has been resolved.',
        'resolved_text' => 'The bug you reported with the title ":bug_title" has been resolved and should be fixed in the next update. Thank you for your report.',
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
        'updated' => 'You have updated the group.',
        'not_public' => 'This group is not public.',
        'needs_application' => 'This group needs an application to join.',
        'no_application' => 'This group does not require applications to join.',
        'already_applied' => 'You already have a pending application for this group.',
        'banned' => 'You are banned from this group.',
        'already_member' => 'You are already a member of this group.',
        'join_success' => 'You successfully joined the group :name.',
        'new_application_title' => 'New group application to :name.',
        'new_application_text' => ':username has applied to your group :groupname. Head to the details of :groupname and click on "Manage Applications" to accept or reject the application.',
        'successful_application' => 'You successfully applied to the group :name.',
        'application_accepted_title' => 'Your application to :name has been accepted.',
        'application_accepted_text' => 'Your application to :name has been accepted. You can now see it under Social > Groups > My Groups.',
        'accepted_application' => 'You successfully accepted :username\'s application.',
        'rejected_application' => 'You successfully rejected :username\'s application.',
        'rejected_and_banned' => 'You have successfully denied :username\'s application and banned them from your group.',
        'removed_and_banned' => 'You have successfully removed and banned :username from your group.',
        'leave' => [
            'not_member' => 'You are not a member of the group you are trying to leave.',
            'only_member' => 'You can\'t leave a group you are the only member of, please delete instead.',
            'admin' => 'You cannot leave a group where you are an admin.',
            'success' => 'You have successfully left the group :name.',
        ],
        'invite' => [
            'new_title' => 'You have a new group invite.',
            'new_text' => 'You have been invited to join the group :name.',
            'sent' => 'You have invited this user.',
            'not_yours' => 'This is not your invitation.',
            'rejected' => 'You have rejected the invitation.',
        ],
    ],

    'message' => [
        'unable_to_send' => 'You are unable to send messages to this user.',
        'sent' => 'Message sent',
        'deleted' => 'Message deleted',
    ],

    'notification' => [
        'sent' => 'Notification sent.',
        'deleted' => 'Notification deleted.',
    ],

    'reward' => [
        'name_changed' => 'You have changed the name.',
        'activated' => 'You have activated :name',
        'deleted' => 'You have deleted :name',
    ],

    'not_authorized' => 'You are not authorized to do this.',
    'accept' => 'accept',
    'reject' => 'reject',
    'deny' => 'deny',

];
