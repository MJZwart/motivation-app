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

## Security Vulnerabilities

If you discover a security vulnerability within the site, please [open a new issue](https://github.com/MJZwart/motivation-app/issues/new) in this github.

## License

You are free to fork this git and make your own version, but please give credit where credit is due. This project is open-source software licensed under the MIT License.
