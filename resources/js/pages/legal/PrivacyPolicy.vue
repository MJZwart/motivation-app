
<template>
    <div class="w-50-flex center">
        <h2>Privacy policy</h2>

        <h4>The information we collect</h4>
        <p>We collect the following information from users:
            <ul>
                <li>E-mail address as used on register</li>
                <li>Login dates and times</li>
                <li>Actions performed on the site</li>
                <li>Any information the user chooses to add to their profile</li>
            </ul>
            Users are not encouraged to share their personal information on their public profile. 
        </p>

        <h4>How we use the data</h4>
        <p>At the time of publishing, we use this data as follows:
            <ul>
                <li>
                    E-mail: Resetting passwords if necessary, or to inform the user in the case of important 
                    announcements (information we are legally required to share with the user)
                </li>
                <li>
                    Login/action tracking: This is purely for debugging purposes and to help the user in the case 
                    something went wrong
                </li>
                <li>
                    User created content: Anything the user submits to the site will only be used in the space 
                    where the user submitted this. Any content a user submits to a community shared space, such 
                    as groups, will be visible to anyone within that space. None of this data is shared elsewhere.
                </li>
            </ul>
        </p>

        <h4>Third party services</h4>
        <p>
            We do not use any  third party services and do not share or sell any user generated data. All data is 
            used as described above and will remain within the site.
        </p>
        
        <h4>Controlling your data</h4>
        <p>
            Users reserve the right to delete their data upon request. At the time of publishing there is no 
            functionality within the site to delete an account, but this is still possible by contacting an administrator 
            through our Discord. We are working on the functionality for a user to delete their own account without any 
            need for outside contact.
        </p>
        <p>
            Users are able to delete almost any content they have submitted, such as tasks, profile information and groups 
            they have created. The content that a user cannot delete is: groups that the user is no longer admin of, 
            messages sent to other users, tracked actions, and bug reports or feedback that has been submitted. All of this 
            is still possible to delete by contacting an administrator. 
        </p>
        
        <h4>Tracking</h4>
        <p>
            We use internal tracking of actions for debugging purposes. These actions are tracked in our database and may be 
            reviewed by administrators if necessary. These actions contain: registration, login attempts, group interaction 
            (creating/joining/editing/leaving groups), submitting bug reports or feedback, changes in friends (adding/removing 
            friends), sending or deleting messages, deleting notifications, interaction with tasks/task lists and updating 
            user information. This information includes the user’s user agent to help debugging. We are working on 
            functionality to opt out of adding the user agent to the action tracking.
        </p>
        <p>
            None of this information is shared with or sold to third parties. We do not track any activity outside of the 
            website. Any information shared with us outside of this website (such as contacting administrators through Discord 
            or email) will not be linked to a {{$t('app-name')}} account.
        </p>
        <p>{{$t('app-name')}} - September 2022</p>
    </div>
</template>