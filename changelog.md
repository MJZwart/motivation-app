# Changelog

## v0.3.0
- Prevent blocked users from seeing the blocker's profile or sending a friend request, and omitting the blocked user from searches
- Allow users to reset passwords (may need extra testing, hotfixes will follow if it doesn't work initially when live)
- When editing a task, now show which task list it is in and what the supertask is (if applicable)
- The groups table has changed in looks. You can now click on it to see some more details
- The page now scrolls up when switching pages (looked wonky when going from one long page to another)
- Translations! The site is now ready to add translations and started with a full Dutch translation. May have some missing translations here and there.

### Code
- Made some changes to how toasts work, using a global toastService, rather than the store, making it more generic and easier to call one
- Similar changes to how the back-end sends information (such as success messages) to the page, using a reusable wrapper class
- Created end-to-end testing to help find bugs quicker.
- Fixed a bug where new achievements required a description (but they don't really need it).
- Updated some small dependencies versions.

## v0.2.4-release
- First release of the site
Shortly after releasing:
- Fixed a problem creating a task
- Fixed the favicon and name
- When visiting another page whilst scrolled down, now scroll to the start of the new page