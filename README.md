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

## 0.3.0 
https://github.com/MJZwart/motivation-app/projects/2

# Tasks
- [ ] Moving tasks to other task lists https://github.com/MJZwart/motivation-app/issues/296
- [ ] Store favourite tasks and being able to retrieve the when making new tasks https://github.com/MJZwart/motivation-app/issues/186
- [ ] Editing these favourites
- [ ] More task types https://github.com/MJZwart/motivation-app/issues/459
# Admin
- [ ] Allow admins to close a user report independent of actions taken https://github.com/MJZwart/motivation-app/issues/385
- [x] Refine the blocked users concept https://github.com/MJZwart/motivation-app/issues/409

# Settings
- [ ] Option to turn off tutorials site-wide https://github.com/MJZwart/motivation-app/issues/407
- [ ] More than one language https://github.com/MJZwart/motivation-app/issues/154
- [ ] Dark version of the website or other version of design for switching https://github.com/MJZwart/motivation-app/issues/236 https://github.com/MJZwart/motivation-app/issues/361
- [x] Able to change password https://github.com/MJZwart/motivation-app/issues/452

# Other
- [ ] Make it clear to the user when something is loading (like buttons etc) https://github.com/MJZwart/motivation-app/issues/442
- [ ] Better error handling (eg when searching groups/users) https://github.com/MJZwart/motivation-app/issues/446
- [ ] More achievement (types) https://github.com/MJZwart/motivation-app/issues/449

## 0.4.0
https://github.com/MJZwart/motivation-app/projects/3

- [ ] Interaction with the characters and villages that users grow with their tasks
- [ ] Allowing a user to retire an existing character into a village for a boost https://github.com/MJZwart/motivation-app/issues/201


## Further development
- [ ] Other types of rewards for completing tasks
- [ ] Interaction with characters and villages within a group to help and motivate each other
