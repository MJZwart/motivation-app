# Changelog

## v0.3.0

### Features
-   Prevent blocked users from seeing the blocker's profile or sending a friend request, and omitting the blocked user from searches
-   Allow users to reset passwords (may need extra testing, hotfixes will follow if it doesn't work initially when live)
-   Added an option to turn off tutorials site-wide in settings (the question mark you see in the corner of things)
-   The groups table has changed in looks. You can now click on it to see some more details
-   Translations! The site is now ready to add translations and started with a full Dutch translation. May have some missing translations here and there.
-   Pagination on pages where the content may become larger than is expected and would require a lot of scrolling

### Fixes
-   Fixed an issue where a user couldn't accept or reject a group invite when the group has already been deleted, as the user kept getting redirected to the dashboard. Now the invite link gets disabled if the group was deleted
-   Fixed a similar issue with friend requests, also disabling the link if a user tried to accept/deny a request through notifications
-   The page now scrolls up when switching pages (looked wonky when going from one long page to another)
-   When editing a task, now show which task list it is in and what the supertask is (if applicable)
-   Fixed a bug where new achievements required a description (but they don't really need it).
-   Showing 'No results' when searching for users with no results, so the user knows the query actually went through.

### Code

-   Made some changes to how toasts work, using a global toastService, rather than the store, making it more generic and easier to call one
-   Similar changes to how the back-end sends information (such as success messages) to the page, using a reusable wrapper class
-   Changes to how the logged in user's data was saved. Previously it was set to localStorage, but this opened up possibility of tampering, now the user is fetched before the app is loaded, including its admin privileges
-   Created end-to-end testing to help find bugs quicker.
-   Updated some small dependencies versions.


## v0.2.4-release

-   First release of the site
    Shortly after releasing:
-   Fixed a problem creating a task
-   Fixed the favicon and name
-   When visiting another page whilst scrolled down, now scroll to the start of the new page
