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
        'created' => 'Taak aangemaakt.',
        'updated' => 'Taak gewijzigd.',
        'deleted' => 'Taak verwijderd.',
        'completed' => 'Taak voltooid.',
        'unauthorized' => 'Dit is niet jouw taak.',
        'template' => [
            'created' => 'Template aangemaakt',
            'updated' => 'Template aangepast',
            'deleted' => 'Template verwijderd',
        ],
    ],

    'tasklist' => [
        'created' => 'Takenlijst aangemaakt.',
        'updated' => 'Takenlijst gewijzigd.',
        'deleted' => 'Takenlijst verwijderd.',
        'unauthorized' => 'Dit is niet jouw takenlijst.',
    ],

    'achievement' => [
        'created' => 'Achievement toegevoegd.',
        'updated' => 'Achievement gewijzigd.',
    ],

    'exp' => [
        'updated' => 'Experience punten gewijzigd',
        'added' => 'Level toegevoegd',
        'char_updated' => 'Karakter experience balancering gewijzigd',
        'vill_updated' => 'Nederzetting experience balancering gewijzigd',
    ],

    'user' => [
        'suspension' => [
            'until' => 'Gebruiker geschorst tot :time',
            'ended_notification_title' => 'Je schorsing is opgeheven.',
            'ended_notification' => ':admin heeft je schorsing opgeheven. Reden hiervoor: :comment. Je was oorspronkelijk geschorst voor: :reason',
            'login_notification' => 'Je bent geschorst tot :time. Reden: :reason. Als je hier tegenin wil gaan, neem contact op met een van de admins op onze Discord. Resterende tijd: :remaining.'
        ],
        'password_reset' => [
            'link_sent' => 'Gelukt, als er een account met dit e-mail bestaat hebben we je een mail gestuurd met de link om je wachtwoord te resetten. Check je spam als je deze mail niet kunt vinden.',
            'link_error' => 'Er is iets misgegaan. Probeer het later opnieuw of neem contact op met een admin.',
            'reset_success' => 'Wachtwoord gewijzigd. Login met je nieuwe wachtwoord.',
            'invalid_token' => 'Token ongeldig. Klik opnieuw op de link in je email om het nogmaals te probleren.',
            'invalid_user' => 'Gebruiker ongeldig. Controlleer of je e-mailadres klopt en probeer het nogmaals.',
            'reset_error' => 'Er is iets misgegaan. Probeer het later nogmaals of neem contact op met een admin.',
            'throttled' => 'Wacht even voordat je het opnieuw probeert.',
        ],
        'blocked' => 'Gebruiker geblokkeerd',
        'unblocked' => 'Gebruiker gedeblokkeerd',
        'created' => 'Je account is aangemaakt!',
        'setup' => 'Je hebt je account ingesteld.',
        'profile_unavailable' => 'Dit profiel is niet zichtbaar.',
        'not_admin' => 'Je bent geen admin.',
        'email_updated' => 'Je e-mailadres is gewijzigd.',
        'password_updated' => 'Je wachtwoord is gewijzigd. Log in met je nieuwe wachtwoord.',
        'settings_updated' => 'Je instellingen zijn gewijzigd.',
        'language_updated' => 'Je taalinstellingen zijn gewijzigd.',
        'reward_updated' => 'Je beloningstype is gewijzigd.',
        'reported' => 'Gebruiker gemeld.',
    ],

    'feedback' => [
        'archived' => 'Feedback gearchiveerd',
        'unarchived' => 'Feedback gedearchiveerd',
        'created' => 'Bedankt voor je feedback. We nemen contact met je op als we verdere vragen of opmerkingen hebben.',
    ],

    'bug' => [
        'created' => 'Bugmelding aangemaakt.',
        'updated' => 'Bugmelding gewijzigd.',
        'resolved_title' => 'Je bugmelding is opgelost.',
        'resolved_text' => 'De bug die je hebt gemeld met de title ":bug_title" is opgelost en zou gefixt moeten zijn in de volgende update. Bedankt voor je melding.',
    ],

    'friend' => [
        'deleted' => 'Vriendschap verwijderd.',
        'request' => [
            'already_sent' => 'Je hebt al een vriendschapsverzoek naar deze gebruiker gestuurd.',
            'already_accepted' => 'Je hebt dit vriendschapsverzoek al geaccepteerd.',
            'already_accepted_other' => 'Dit vriendschapsverzoek is al geaccepteerd.',
            'unable_to_send' => 'Je kunt geen vriendschapsverzoek naar deze gebruiker sturen.',
            'blocked' => 'Je hebt deze gebruiker geblokkeerd.',
            'notification_title' => 'Nieuw vriendschapsverzoek!',
            'notification_body' => 'Je hebt een nieuwe vriendschapsverzoek van :name. Wil je dit accepteren?',
            'sent' => 'Vriendschapsverzoek verstuurd.',
            'accepted' => 'Vriendschapsverzoek geaccepteerd. Jullie zijn nu vrienden.',
            'denied' => 'Vriendschapsverzoek geweigerd.',
            'cancelled' => 'Vriendschapsverzoek geannuleerd.',
        ],
    ],

    'group' => [
        'created' => 'Je groep :name is aangemaakt.',
        'deleted' => 'Je groep :name is verwijderd.',
        'updated' => 'Je groep is gewijzigd.',
        'not_public' => 'Deze groep is niet openbaar.',
        'needs_application' => 'Voor lidmaatschap van deze groep is een aanmelding vereist.',
        'no_application' => 'Voor lidmaatschap van deze groep is geen aanmelding vereist.',
        'already_applied' => 'Je hebt al een aanmelding naar deze groep gestuurd.',
        'suspended' => 'Je bent verbannen van deze groep.',
        'already_member' => 'Je bent al lid van deze groep.',
        'join_success' => 'Je bent nu lid van de groep :name.',
        'new_application_title' => 'Nieuwe groepaanmelding voor :name.',
        'new_application_text' => ':username heeft een aanmelding gestuurd voor je groep :groupname. Ga naar de groeppagina van :groupname en klik op "Beheer aanmeldingen" om deze aanmelding te weigeren of te accepteren.',
        'successful_application' => 'Je hebt een aanmelding naar de groep :name gestuurd.',
        'application_accepted_title' => 'Je aanmelding is geaccepteerd.',
        'application_accepted_text' => 'Je aanmelding voor de groep :name is geaccepteerd. Je kunt de groep nu vinden onder Sociaal > Groepen > Mijn groepen.',
        'accepted_application' => 'Je hebt de aanmelding van :username geaccepteerd.',
        'rejected_application' => 'Je hebt de aanmelding van :username afgewezen.',
        'rejected_and_suspended' => 'Je hebt de aanmelding van :username afgewezen en de gebruiker verbannen van je groep.',
        'removed_and_suspended' => 'Je hebt :username verwijderd uit en verbannen van je groep.',
        'leave' => [
            'not_member' => 'Je bent geen lid van de groep die je probeert te verlaten.',
            'only_member' => 'Je kunt niet een groep verlaten waar jij het enige lid van bent, verwijder in plaats daarvan de groep.',
            'admin' => 'Je kunt niet een groep verlaten waar jij de admin van bent.',
            'success' => 'Je hebt de groep :name verlaten.',
        ],
        'invite' => [
            'new_title' => 'Je hebt een nieuwe groepsuitnodiging.',
            'new_text' => 'Je bent uitgenodigd lid te worden van de groep :name.',
            'sent' => 'Je hebt de gebruiker uitgenodigd.',
            'not_yours' => 'Dit is niet jouw uitnodiging.',
            'rejected' => 'Je hebt de uitnodiging afgewezen.',
        ],
        'unblocked' => 'Je hebt :username gedeblokkeerd',
        'user_not_blocked' => 'Deze gebruiker is niet geblokkeerd',
        'not_admin' => 'Je bent geen admin van deze groep',
        'message' => [
            'created' => 'Message posted',
            'deleted' => 'Bericht verwijderd',
        ],
        'role' => [
            'updated' => 'Rol bewerkt',
            'created' => 'Rol aangemaakt',
            'deleted' => 'Rol verwijderd',
        ],
        'ownership_transferred' => 'Eigendom overgedragen',
    ],

    'message' => [
        'unable_to_send' => 'Je kunt deze gebruiker geen berichten sturen.',
        'sent' => 'Bericht verstuurd',
        'deleted' => 'Bericht verwijderd',
    ],

    'notification' => [
        'sent' => 'Notificatie verstuurd.',
        'deleted' => 'Notificatie verwijderd.',
    ],

    'reward' => [
        'name_changed' => 'Je hebt de naam gewijzigd.',
        'activated' => 'Je hebt :name geactiveerd',
        'deleted' => 'Je hebt :name verwijderd',
        'level' => [
            'village' => [
                'levelup' => 'Je nederzetting is nu level :level',
                'statup' => 'Je :stat is nu level :level',
            ],
            'character' => [
                'levelup' => 'Je karakter is nu level :level',
                'statup' => 'Je :stat is nu level :level',
            ],
        ],
        'coinsEarned' => 'Je hebt :coins verdiend',
    ],

    'not_authorized' => 'Je bent onbevoegd om dit te doen.',
    'accept' => 'accepteer',
    'reject' => 'afwijzen',
    'deny' => 'weiger',

];
