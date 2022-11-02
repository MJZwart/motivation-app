import {getRandomString, waitShort} from '../../support/commands';
import {deleteButton, completeTaskButton, notificationButton, admin, editButton} from '../../support/constants';

describe('Admin', () => {
    beforeEach(() => {
        cy.login(admin.username, admin.password);
        goToAdminPage();
    });

    function goToAdminPage() {
        cy.get('a').contains('Admin').click();
        waitShort();
    }
    function goToTab(tabName) {
        cy.get('button').contains(tabName).click();
        waitShort();
    }

    // describe('Admin can see the admin dashboard', () => {
    //     it('shows all relevant information', () => {
    //         cy.get('.overview').should('contain.text', 'Total users');
    //         cy.get('.overview').should('contain.text', 'Unresolved bugs');
    //     });
    // });
    // describe('Admin can send a notification to all users', () => {
    //     it('can send and see a custom notification', () => {
    //         const title = getRandomString();
    //         const text = getRandomString();

    //         goToTab('Send notification');

    //         cy.get('h3').should('contain.text', 'Send notification to all users');
            
    //         cy.get('#title').type(title);
    //         cy.get('#text').type(text);
    //         cy.get('#send-notification-button').click();
    //         waitShort();

    //         cy.get('a').find(notificationButton).click();
    //         waitShort();

    //         cy.get('span').contains(title).should('exist');
    //         cy.get('p').contains(text).should('exist');
    //     });
    // });
    // describe('Admin can see balancing', () => {
    //     it('can see the balancing page and update the village balancing', () => {
    //         goToTab('Balancing');

    //         cy.get('h5').contains('Add a new level').should('exist');

    //         goToTab('Village XP gain');

    //         cy.get('span').contains('GENERIC').parent().parent().get('input').first().type('1');
    //         cy.get('button').contains('Update village experience gain').click();
    //         waitShort();

    //         cy.get('span').contains('GENERIC').parent().parent().get('input').first().type('{backspace}');
    //         cy.get('button').contains('Update village experience gain').click();
    //         waitShort();
    //     });
    //     it('can update the character balancing', () => {
    //         goToTab('Balancing');
    //         goToTab('Character XP gain');

    //         cy.get('span').contains('GENERIC').parent().parent().get('input').first().type('1');
    //         cy.get('button').contains('Update character experience gain').click();
    //         waitShort();

    //         cy.get('span').contains('GENERIC').parent().parent().get('input').first().type('{backspace}');
    //         cy.get('button').contains('Update character experience gain').click();
    //         waitShort();
    //     });
    // });
    // describe('Admin can see achievements', () => {
    //     it('can see achievements and add a new achievement', () => {
    //         const name = getRandomString();
    //         const desc = getRandomString();
    //         const amount = Math.floor(Math.random() * 10);

    //         goToTab('Achievements');

    //         cy.get('h3').contains('Manage achievements').should('exist');

    //         cy.get('button').contains('Add new achievement').click();
    //         waitShort();
            
    //         cy.get('#name').type(name);
    //         cy.get('#description').type(desc);
    //         cy.get('#type').select('TASKS_MADE');
    //         cy.get('#trigger_amount').type(amount);

    //         const triggerDesc = 'Created ' + amount + ' tasks.';
    //         cy.get('p').contains(triggerDesc).should('exist');

    //         cy.get('button').contains('Create new achievement').click();
    //         waitShort();

    //         //It is currently difficult to see if it shows up in the list of achievements due to the pagination
    //     });
    //     it('can edit an achievement', () => {
    //         const desc = getRandomString();

    //         goToTab('Achievements');

    //         cy.get(editButton).first().click();
    //         waitShort();

    //         cy.get('#description').type(desc);
    //         cy.get('button').contains('Edit achievement').click();
    //         waitShort();

    //         cy.get('td').contains(desc).should('exist');
    //     });
    // });

    describe('Admin can see bug reports', () => {
        it('can see and edit bug reports', () => {
            const comment = getRandomString();
            goToTab('Bug reports');

            cy.get('h3').contains('Manage bug reports');

            cy.get('tr').contains('Reported').parent().first().find(editButton).click();
            waitShort();

            cy.get('#admin-comment').type(comment);
            cy.get('button').contains('Update').click();
            waitShort();

            cy.get('td').contains(comment).should('exist');
        });
        // it('can send an email to a bug report author', () => {

        // });
    });
    // describe('Admin can see reported users', () => {
    //     it('can see reported users and suspend them', () => {

    //     });
    //     it('can email a reported user', () => {

    //     });
    //     it('can suspend a user through their profile', () => {

    //     });
    // });
    // describe('Admins can handle suspensions', () => {
    //     it('can edit a suspension', () => {

    //     });
    //     it('can end a user suspension', () => {

    //     });
    // });
    // describe('Admin can see feedback', () => {
    //     it('can see and archive feedback', () => {

    //     });
    //     it('can toggle to see archived feedback and unarchive feedback', () => {

    //     });
    // });
});