import {getRandomString} from '../../support/commands';

describe('New user', () => {
    
    const username = getRandomString();
    const password = getRandomString(8);
    const email = getRandomString() + '@mail.com';

    const characterName = getRandomString();

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
    describe('Check that an unregistered user can submit feedback', () => {
        it('submits feedback', () => {
            cy.visit('/');

            //Visit the feedback page
            cy.get('.silent').contains('Feedback').click();
            cy.url().should('contain', 'feedback');
            cy.get('h2').should('have.text', 'Feedback');

            //Fill in feedback and submit
            cy.intercept('/api/feedback').as('storeFeedback');
            cy.get('#feedback').type(getRandomString());
            cy.get('#email').type(email);
            cy.get('button').contains('Send feedback').click();

            cy.wait('@storeFeedback').its('response.statusCode').should('eq', 200);
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
        it('registers a new account and chooses a village with no tasks', () => {    
            const username1 = getRandomString();
            const password1 = getRandomString(8);
            const email1 = getRandomString() + '@mail.com';
            const villageName = getRandomString();
            cy.visit('/');
            cy.contains('Register').should('exist').click();
            cy.url().should('contain', 'register');
            //Fill in the account data
            cy.get('#username').type(username1);
            cy.get('#email').type(email1);
            cy.get('#password').type(password1);
            cy.get('#password_confirmation').type(password1);
            cy.get('#agree_to_tos').click();
            
            //Click register
            cy.contains('Register new account').should('exist').click();

            //Assert that the user gets redirected to the login screen
            cy.url().should('contain', 'login');
            //Login
            cy.get('#username').type(username1);
            cy.get('#password').type(password1);
            cy.get('#login-button').click();

            //Assert that the user gets logged in and gets sent to the welcome screen
            cy.url().should('contain', 'welcome');

            //Choose village and enter no name
            cy.get('#VILLAGE').click();
            cy.get('.block').contains('Next').click();
            cy.get('#reward_object_name').should('have.class', 'invalid');
            //Then enter a name and continue
            cy.get('#reward_object_name').type(villageName);
            cy.get('.block').contains('Next').click();

            //Pick no tasks and continue
            cy.get('.modal-header').should('contain', 'Just a little more');

            cy.get('button').contains('Submit').click();

            cy.get('.card').should('contain', villageName);
            cy.get('.card').should('contain', 'Tasks');
            cy.get('.task').should('not.exist');
        });
        it('registers a new account and chooses no rewards and no tasks', () => {    
            const username2 = getRandomString();
            const password2 = getRandomString(8);
            const email2 = getRandomString() + '@mail.com';

            cy.visit('/');
            cy.contains('Register').should('exist').click();
            cy.url().should('contain', 'register');
            //Fill in the account data
            cy.get('#username').type(username2);
            cy.get('#email').type(email2);
            cy.get('#password').type(password2);
            cy.get('#password_confirmation').type(password2);
            cy.get('#agree_to_tos').click();
            
            //Click register
            cy.contains('Register new account').should('exist').click();

            //Assert that the user gets redirected to the login screen
            cy.url().should('contain', 'login');
            //Login
            cy.get('#username').type(username2);
            cy.get('#password').type(password2);
            cy.get('#login-button').click();

            //Assert that the user gets logged in and gets sent to the welcome screen
            cy.url().should('contain', 'welcome');

            //Choose none
            cy.get('#NONE').click();
            cy.get('.block').contains('Next').click();

            //Pick no tasks and continue
            cy.get('.modal-header').should('contain', 'Just a little more');

            cy.get('button').contains('Submit').click();

            cy.get('.card').should('have.length', 2);
            cy.get('.card').should('contain', 'Tasks');
            cy.get('.task').should('not.exist');
        });
    });
    describe('Check that an registered user can submit a bug report', () => {
        it('visits the bug report page and submits a report', () => {
            cy.intercept('/api/bugreport').as('storeBugReport');

            //Login and go to the bugreport page
            cy.login(username, password);
            cy.get('.silent').contains('Report a bug').click();
            cy.url().should('contain', 'bugreport');
            cy.get('h2').should('contain', 'Submit bug report');

            //Fill in and submit a bug report
            cy.get('#title').type(getRandomString());
            cy.get('#page').type(getRandomString());
            cy.get('#comment').type(getRandomString());
            cy.get('.block').contains('Submit bug report').click();
            cy.wait('@storeBugReport').its('response.statusCode').should('eq', 200);
        });
    });
});