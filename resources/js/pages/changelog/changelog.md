# Changelog

## v0.5.0

### Features


### Design
-  Changed the look of the checkboxes


### Bugs



## v0.4.0

### Features
-   Characters and villages now earn coins! There's nothing you can do with it yet, but it looks cool if you ask me.
-   Added a messaging board to groups. All members of a group can now chat with each other. Admins and moderators can delete message and any user can delete their own message. In case of abuse users can report a message.
-   Overhauled the group roles: three separate icons, the standard addition of a 'moderator' role, plus the ability for admins and moderators to edit these roles as they see fit. They can change the names and permissions of these roles and even add more roles with their own names and permissions.
-   Ranked these roles by position. Admin/owner is always the highest and 'member' (default for new members) is always the lowest. All other ranks can be switched in position to sort these.
-   Added the option for group admins to give ownership to another group member.

### Quality of life
-   You can now filter for groups that require no application.
-   When sending feedback or a bug report, you now have the option to send diagnostics about your screen size/system to help us hunt bugs quicker.
-   Added a spinner to buttons when waiting for server response to show the user something is happening.
-   Made some pages look a bit better on mobile. Still a work in progress.
-   After registering a new account, you are now logged in straight away.
-   More icons!
-   On the page with all groups, easily see that you are already a member of this group + which rank you are.

### Bugs
-   Fixed a few viewing problems on smaller screens and changed some classes to fit better (subjectively).
-   Reinstated the 'level up' messages after finishing tasks and gaining enough exp for level ups.
-   Probably some more bugs that weren't noted and got fixed when working on features. Sorry, forgot to write them down.

### Code
-   Refactored the back-end experience gain calculations, it was kind of a mess.
-   Made the sub-tabs more reusable and reduced duplicate code related to this.
-   Reworked the group rank/role system to account for more roles (such as moderators/recruiters) and making it easier to update these permissions
-   Started using a new framework for icons

---

### v0.3.1
-   Hotfix: The email service wasn't working at all, it should be set up now. Made some new tasks to fix and clear up the password reset function.
-   Hotfix: The email to reset the password wasn't working, it should be set up correctly now.
-   Fix: Reset password requests were throttled, but it displayed an OK message to the user, making them believe a mail was sent when it was not. This now shows the correct error.

## v0.3.0

### Features
-   Prevent blocked users from seeing the blocker's profile or sending a friend request, and omitting the blocked user from searches
-   Allow users to reset passwords (may need extra testing, hotfixes will follow if it doesn't work initially when live)
-   Added an option to turn off tutorials site-wide in settings (the question mark you see in the corner of things)
-   The groups table has changed in looks. You can now click on it to see some more details
-   Translations! The site is now ready to add translations and started with a full Dutch translation. May have some missing translations here and there
-   Pagination on pages where the content may become larger than is expected and would require a lot of scrolling
-   Created this changelog
-   Allow users to create, manage and use templates to make it easier to re-use recurring tasks that don't quite fit as a repeatable
-   Created a simple breadcrumb on group pages (which will be reusable on other pages later, but currently there is no need for it yet)

### Fixes
-   Fixed an issue where a user couldn't accept or reject a group invite when the group has already been deleted, as the user kept getting redirected to the dashboard. Now the invite link gets disabled if the group was deleted
-   Fixed a similar issue with friend requests, also disabling the link if a user tried to accept/deny a request through notifications
-   The page now scrolls up when switching pages (looked wonky when going from one long page to another)
-   When editing a task, now show which task list it is in and what the supertask is (if applicable)
-   Fixed a bug where new achievements required a description (but they don't really need it)
-   Showing 'No results' when searching for users with no results, so the user knows the query actually went through
-   You no longer need to be logged in to submit a bug report
-   Made the dropdown selects actually look like dropdowns

### Code
-   Made some changes to how toasts work, using a global toastService, rather than the store, making it more generic and easier to call one
-   Similar changes to how the back-end sends information (such as success messages) to the page, using a reusable wrapper class
-   Changes to how the logged in user's data was saved. Previously it was set to localStorage, but this opened up possibility of tampering, now the user is fetched before the app is loaded, including its admin privileges
-   Created end-to-end testing to help find bugs quicker
-   Updated some small dependencies versions
-   Changed some of the dates to show 'yesterday', 'today' and 'tomorrow' rather than the date. Other dates remained unchanged
-   Renamed 'ban' to 'suspend' throughout the code for consistency
-   Switched all stores from 'js' files to 'ts' files and made some fixes here and there where too much data was sent when only an id was necessary

---
## v0.2.4-release

-   First release of the site
    Shortly after releasing:
-   Fixed a problem creating a task
-   Fixed the favicon and name
-   When visiting another page whilst scrolled down, now scroll to the start of the new page
