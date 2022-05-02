# About Motivation

Gamified to-do lists. Basically.

## (Pre-)Installations

Programs needed to work this:
- NPM: (https://www.npmjs.com/get-npm)
- PHP and MySQL (like through XAMPP: https://www.apachefriends.org/download.html)
- Composer: (https://getcomposer.org/download/)
- An IDE. I use Visual Studio Code at the moment, but to each their own.

## Installation

- Clone the repository off of Github (through whatever works for you)
- Go to CMD.exe or use the XAMPP shell. Make sure PHP/Apache is running
- Go to your MySQL admin and create a new database called ‘`motivation`’.
- CD to the folder you cloned the repository in and type the following:

`composer install`

`npm install`

`npm run dev`

- Make a copy of `env.example` and rename this to `.env`

`php artisan migrate:fresh --seed`

`php artisan key:generate`


## To run

In the same shell, type:

`npm run hot`

Open another shell, cd to the folder you cloned the repository in and type:

`php artisan serve`

Make sure both PHP and MySQL are running through XAMPP or the like

## Contributing

If you wish to contribute to this project, you are free to fork the code and make your own additions, though no guarantees are given of its implementation. You are free to [open a new issue](https://github.com/MJZwart/motivation-app/issues/new) with any and all ideas for the project.

## Bug reports

If you spot any bugs in the code, please [open a new issue](https://github.com/MJZwart/motivation-app/issues/new). Provide the following information to help us reproduce the bug. 
- How were you running the application?
- What were you trying to do? Provide details where necessary.
- What were you expecting to happen?
- What actually happened?
Also include screenshots if you stumble across errors in the console etc.

## Security Vulnerabilities

If you discover a security vulnerability within the site, please [open a new issue](https://github.com/MJZwart/motivation-app/issues/new) in this github.

## License

You are free to fork this git and make your own version, but please give credit where credit is due. This project is open-source software licensed under the MIT License.

## Roadmap

Before launching the beta version of the site, the following things need to be finished:
# Content
- [ ] An actual landing page
- [ ] A filled in FAQ page
- [ ] A privacy policy
- [ ] Responsive design on every page in terms of placement of components and readable text
- [x] A feedback form
- [x] All invalid inputs show as red when validation fails
- [x] Tooltips on all buttons and otherwise unclear data
- [ ] A tutorial or 'help what do I do here' button on every page
# Users and interaction
- [ ] Allowing users to manage their blocklist and search for friends to add
- [ ] Allow a user to manage the group they are an admin of
- [x] Fully allowing a user to manage their characters or villages (rename them, see their level)
- [ ] Finished groups: invites to private groups, 
# Admin and back-end
- [ ] Admins able to view and respond to feedback
- [ ] Admins able to view reported users and perform action: message the user, ban the user
- [ ] Tracking of logins (attempts) for security
- [ ] Worked out good balancing of the rewards (experience points) to start off with, and plenty of levels to gain

## Further development
- [ ] Dark version of the website
- [ ] More than one language
- [ ] Other types of rewards for completing tasks
- [ ] Allowing a user to retire an existing character into a village for a boost
- [ ] Adding favourite tasks or templates so users can easily find tasks that can be repeated in various ways, with slight variations
- [ ] Interaction with the characters and villages that users grow with their tasks
- [ ] Interaction with characters and villages within a group to help and motivate each other
