const { loadavg } = require("os");

describe('New user', () => {

    const username = Math.random().toString(36).substring(2,7);
    const password = Math.random().toString(36).substring(2,10);
    const email = Math.random().toString(36).substring(2,7) + '@mail.com';

    const characterName = Math.random().toString(36).substring(2,7);

    describe('Check that the user can visit any public page', () => {
        it('visits various pages', () => {
            //Home page
            cy.visit('/');
            cy.get('h1').should('contain', 'Welcome to Questifyer');
            cy.get('.card-header').should('contain', 'Sir McTaskalot');
            cy.get('.register-button').should('exist').should('contain', 'Create an account today!');
            cy.get('#footer').should('exist');
            
            //FAQ
            cy.get('.silent').contains('F.A.Q.').click();
            cy.url().should('contain', 'faq');
            cy.get('h2').should('have.text', 'F.A.Q.');

            //Privacy Policy
            cy.get('.silent').contains('Privacy policy').click();
            cy.url().should('contain', 'privacy');
            cy.get('h2').should('have.text', 'Privacy policy');

            //Terms of service
            cy.get('.silent').contains('Terms of service').click();
            cy.url().should('contain', 'tos');
            cy.get('h2').should('have.text', 'Terms of Service');
        });
    });
    describe('Check that an unregistered user can submit a bug report', () => {
        it('visits the bug report page and submits a report', () => {

        });
    });
    describe('Check that you can create an account', () => {
        it('registers a new account and chooses a character with multiple tasks', () => {    
            cy.visit('/');
            cy.contains('Register').should('exist').click();
            cy.url().should('contain', 'register');
            //Fill in the account data
            cy.get('#username').type(username);
            cy.get('#email').type(email);
            cy.get('#password').type(password);
            cy.get('#password_confirmation').type(password);
            cy.get('#agree_to_tos').click();
            
            //Click register
            cy.contains('Register new account').should('exist').click();

            //Assert that the user gets redirected to the login screen
            cy.url().should('contain', 'login');
            //Login
            cy.get('#username').type(username);
            cy.get('#password').type(password);
            cy.get('#login-button').click();

            //Assert that the user gets logged in and gets sent to the welcome screen
            cy.url().should('contain', 'welcome');

            //Choose character and enter name
            cy.get('#CHARACTER').click();
            cy.get('#reward_object_name').type(characterName);
            cy.get('.block').contains('Next').click();

            //Pick three tasks and continue
            cy.get('.modal-header').should('contain', 'Just a little more');
            cy.get('#1').click();
            cy.get('#2').click();
            cy.get('#3').click();

            cy.get('button').contains('Submit').click();

            cy.get('.card').should('contain', characterName);
            cy.get('.card').should('contain', 'Tasks');
            cy.get('.task').should('have.length', 3);
        });
    });
});