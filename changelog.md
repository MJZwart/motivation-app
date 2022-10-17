# Changelog

## v0.3.0

-   Prevent blocked users from seeing the blocker's profile or sending a friend request, and omitting the blocked user from searches
-   Allow users to reset passwords (may need extra testing, hotfixes will follow if it doesn't work initially when live)
-   When editing a task, now show which task list it is in and what the supertask is (if applicable)
-   Fixed an issue where a user couldn't accept or reject a group invite when the group has already been deleted, as the user kept getting redirected to the dashboard. Now the invite link gets disabled if the group was deleted
-   Fixed a similar issue with friend requests, also disabling the link if a user tried to accept/deny a request through notifications
-   Added an option to turn off tutorials site-wide in settings (the question mark you see in the corner of things)

### Code

-   Made some changes to how toasts work, using a global toastService, rather than the store, making it more generic and easier to call one
-   Similar changes to how the back-end sends information (such as success messages) to the page, using a reusable wrapper class
-   Changes to how the logged in user's data was saved. Previously it was set to localStorage, but this opened up possibility of tampering, now the user is fetched before the app is loaded, including its admin privileges

## v0.2.4-release

-   First release of the site
    Shortly after releasing:
-   Fixed a problem creating a task
-   Fixed the favicon and name
-   When visiting another page whilst scrolled down, now scroll to the start of the new page
