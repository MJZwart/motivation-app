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
- Go to your DB admin and create a new database called ‘`motivation`’.
- In a terminal or powershell, go to the folder you cloned the repository in and type the following:

`composer install`

`npm install`

- Make a copy of `env.example` and rename this to `.env`

`php artisan migrate:fresh --seed`

`php artisan key:generate`


## To run

In the same shell, type:

`npm run watch`

Open another shell, cd to the folder you cloned the repository in and type:

`php artisan serve`

Make sure both PHP and MySQL are running through XAMPP or the like

## Contributing

If you wish to contribute to this project, you are free to fork the code and make your own additions, though no guarantees are given of its implementation. You are free to [open a new issue](https://github.com/MJZwart/motivation-app/issues/new) with any and all ideas for the project.

### Adding languages

In order to add a new language, first add the shorthand on the following locations:
- `app/Http/Requests/RegisterUserRequest`
- `app/Http/Requests/UpdateLanguage`
- `resources/js/components/global/ChangeLanguage (l 18)`
- Add the language option in `resources/js/services/languageService.ts` with the flag.
- `resources/js/store/userStore.ts`
- `resources/type/global.d.ts`

Then create the json files for the language, using the shorthand (e.g. 'en.json', or 'nl.json') in each of the folders under
`resources/lang/i18n/...`
and add a js file like `en.js`, or `nl.js`.

For the back-end translation, create a folder with the shorthand in `resources/lang`. In there, copy all the existing php files that are under 'en', and translate the strings.

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
