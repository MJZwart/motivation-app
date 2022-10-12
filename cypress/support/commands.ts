/// <reference types="cypress" />
// ***********************************************
// This example commands.ts shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })
//
// declare global {
//   namespace Cypress {
//     interface Chainable {
//       login(email: string, password: string): Chainable<void>
//       drag(subject: string, options?: Partial<TypeOptions>): Chainable<Element>
//       dismiss(subject: string, options?: Partial<TypeOptions>): Chainable<Element>
//       visit(originalFn: CommandOriginalFn, url: string, options: Partial<VisitOptions>): Chainable<Element>
//     }
//   }
// }
// @ts-nocheck

import { globalLongWait, globalShortWait } from "./constants";

type UserData = {
    username: string,
    password: string,
    csrfCookie: undefined,
    authCookie: undefined | string[],
    cookies: undefined | Cypress.Cookie[],
    localStorage: undefined | string,
    fail3: boolean,
}

let userData: UserData = {
    username: 'test',
    password: 'password',
    csrfCookie: undefined,
    authCookie: undefined,
    cookies: undefined,
    localStorage: undefined,
    fail3: false,
}

export function getRandomString(length = 5) {
    return Math.random().toString(36).substring(2, length + 2);
}

export function waitShort() {
    cy.wait(globalShortWait);
}
export function waitLong() {
    cy.wait(globalLongWait);
}

// function validateLogin(userData) {
//     let cookie;
//     if (userData.authCookie) {
//         cookie = userData.authCookie[0] + '=' + userData.authCookie[1];
//     }
//     return cy
//         .request({
//             url: '/api/me',
//             failOnStatusCode: false,
//             log: true,
//             headers: {
//                 'Cache-Control': 'no-cache',
//                 cookie: cookie,
//             },
//         })
//         .then(response => {
//             const fail = response.status !== 200;
//             const fail2 = response.headers['Set-Cookie'] !== undefined;
//             if (fail || fail2 || userData.fail3 !== false) {
//                 let message = 'Failed to validate login';
//                 message += fail ? '\tStatus:' + response.status : 'No-Cookie returned';
//                 cy.log(message);
//                 // Cypress.session.clearAllSavedSessions();
//                 userData.authCookie = userData.cookies = userData.localStorage = null;
//                 userData.fail3 = false;
//                 cy.task('setUserData', userData);
//                 return cy.wrap(false);
//             }
//             cy.log('Validate login succes');
//             return cy.wrap(true);
//         });
// }

function login(username: string, password: string) {
    cy.session([username, password], () => {
        cy.intercept('/api/login').as('apiLogin');
        cy.intercept('/api/sanctum/csrf-cookie').as('csrfCookie');
        cy.visit('/login');
        cy.get('#username').type(username);
        cy.get('#password').type(password);
        cy.get('#login-button').click();
        // cy.wait('csrfCookie').then(intercept => {
        //     let response = intercept.response;
        //     let header = response.headers;
        //     userData.csrfCookie = intercept.response.headers['set-cookie'][0].split(';')[0].split('=');
        // });
        cy.wait('@apiLogin').then(intercept => {
            userData.authCookie = intercept.response.headers['set-cookie'][0].split(';')[0].split('=');
        });
        cy.wait(1);
        cy.getCookies().then(itsCookies => {
            userData.cookies = itsCookies;
            userData.localStorage = JSON.stringify(localStorage);
            cy.task('setUserData', userData);
        });
    });
    cy.visit('/');
}

Cypress.Commands.add('login', (username: string = 'test', password: string = 'password') => {
    let cookies = [];
    // Get the stored userdata
    cy.task('getUserData')
        .then(Data => {
            userData = Data;
            cookies = userData.cookies;
            // Check if any Userdata was stored
            if (Array.isArray(cookies)) {
                //Retrieve local storage data
                const itsLocalStorage = userData.localStorage;
                if (itsLocalStorage) {
                    // Restores the old local storage because,
                    // Vue-services needs some local storage data set,
                    // otherwise it will think the user is not logged in
                    Object.entries(JSON.parse(itsLocalStorage)).forEach(([key, value]) => {
                        localStorage.setItem(key, value);
                    });
                }
            }
            // Wrap the cookies
            if (!cookies) return [];
            return cookies;
        })
        // Becuase of cypress async nature setCookie needs to be chained in a 'each' call
        .each(cookie => {
            cy.setCookie(cookie['name'], cookie['value'], cookie);
        })
        .wait(1)
        .then(() => {
            // validateLogin(userData).then(valid => {
            //     if (valid === false) {
                    login(username, password);
                // }
                cy.wait(500);
                cy.url().then(url => {
                    if (url !== 'http://localhost:8000/dashboard') {
                        login(username, password);
                    }
                });
            // });
        });
});

Cypress.on('uncaught:exception', (err, runnable) => {
    console.log(err);
    return false;
})

Cypress.Commands.add('resetDB', () => {
    cy.exec('php artisan migrate:fresh --seed');
});