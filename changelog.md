# Changelog

## v0.3.0
- Prevent blocked users from seeing the blocker's profile or sending a friend request, and omitting the blocked user from searches
- Allow users to reset passwords (may need extra testing, hotfixes will follow if it doesn't work initially when live)
- When editing a task, now show which task list it is in and what the supertask is (if applicable)

### Code
- Made some changes to how toasts work, using a global toastService, rather than the store, making it more generic and easier to call one
- Similar changes to how the back-end sends information (such as success messages) to the page, using a reusable wrapper class

## v0.2.4-release
- First release of the site
Shortly after releasing:
- Fixed a problem creating a task
- Fixed the favicon and name
- When visiting another page whilst scrolled down, now scroll to the start of the new page