import {getRandomString, waitShort, waitLong} from '../../support/commands';
import {notificationButton, admin, editButton, messageButton, banButton, user1, unlockedButton, lockButton} from '../../support/constants';

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

    describe('Admin can see the admin dashboard', () => {
        it('shows all relevant information', () => {
            cy.get('.overview').should('contain.text', 'Total users');
            cy.get('.overview').should('contain.text', 'Unresolved bugs');
        });
    });
    describe('Admin can send a notification to all users', () => {
        it('can send and see a custom notification', () => {
            //Waiting to prevent throttling errors
            waitLong();
            const title = getRandomString();
            const text = getRandomString();

            goToTab('Send notification');

            cy.get('h3').should('contain.text', 'Send notification to all users');
            
            cy.get('#title').type(title);
            cy.get('#text').type(text);
            cy.get('#send-notification-button').click();
            waitShort();

            cy.get('a').find(notificationButton).click();
            waitShort();

            cy.get('span').contains(title).should('exist');
            cy.get('p').contains(text).should('exist');
        });
    });
    describe('Admin can see balancing', () => {
        it('can see the balancing page and update the village balancing', () => {
            goToTab('Balancing');

            cy.get('h5').contains('Add a new level').should('exist');

            goToTab('Village XP gain');

            cy.get('span').contains('GENERIC').parent().parent().get('input').first().type('1');
            cy.get('button').contains('Update village experience gain').click();
            waitShort();

            cy.get('span').contains('GENERIC').parent().parent().get('input').first().type('{backspace}');
            cy.get('button').contains('Update village experience gain').click();
            waitShort();
        });
        it('can update the character balancing', () => {
            goToTab('Balancing');
            goToTab('Character XP gain');

            cy.get('span').contains('GENERIC').parent().parent().get('input').first().type('1');
            cy.get('button').contains('Update character experience gain').click();
            waitShort();

            cy.get('span').contains('GENERIC').parent().parent().get('input').first().type('{backspace}');
            cy.get('button').contains('Update character experience gain').click();
            waitShort();
        });
    });
    describe('Admin can see achievements', () => {
        it('can see achievements and add a new achievement', () => {
            //Waiting to prevent throttling errors
            waitLong();
            const name = getRandomString();
            const desc = getRandomString();
            const amount = Math.floor(Math.random() * 10);

            goToTab('Achievements');

            cy.get('h3').contains('Manage achievements').should('exist');

            cy.get('button').contains('Add new achievement').click();
            waitShort();
            
            cy.get('#name').type(name);
            cy.get('#description').type(desc);
            cy.get('#type').select('TASKS_MADE');
            cy.get('#trigger_amount').type(amount);

            const triggerDesc = 'Created ' + amount + ' task';
            cy.get('p').contains(triggerDesc).should('exist');

            cy.get('button').contains('Create new achievement').click();
            waitShort();

            //It is currently difficult to see if it shows up in the list of achievements due to the pagination
        });
        it('can edit an achievement', () => {
            const desc = getRandomString();

            goToTab('Achievements');

            cy.get(editButton).first().click();
            waitShort();

            cy.get('#description').type(desc);
            cy.get('button').contains('Edit achievement').click();
            waitShort();

            cy.get('td').contains(desc).should('exist');
        });
    });

    describe('Admin can see bug reports', () => {
        it('can see and edit bug reports', () => {
            //Waiting to prevent throttling errors
            waitLong();
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
        it('can send an email to a bug report author', () => {
            const message = getRandomString();
            goToTab('Bug reports');

            cy.get('tr').contains('Reported').parent().first().find(messageButton).click();
            waitShort();

            cy.get('h5').contains('Send message to').should('exist');
            cy.get('#message').type(message);
            cy.get('button').contains('Send message').click();
            waitShort();

            cy.visit('/messages');
            cy.get('.messages').contains(message).should('exist');
        });
    });
    describe('Admin can see reported users', () => {
        it('can see reported users and suspend them', () => {
            //Waiting to prevent throttling errors
            waitLong();
            const reason = getRandomString();
            const days = Math.floor(Math.random() * 10) + 1;
            goToTab('Reported users');

            cy.get('.content-block.clickable').first().should('exist').click();
            cy.get('h5').contains('Reports').should('exist');

            cy.get('.content-block.clickable').first().find(banButton).click();
            waitShort();

            cy.get('h5').contains('Suspend user').should('exist');
            cy.get('#reason').type(reason);
            cy.get('#days').type(days);
            cy.get('button').contains('Suspend user').click();
            waitShort();

            goToTab('Suspended users');

            cy.get('td').contains(reason).should('exist').parent().contains(days);
        });
        it('can mail a reported user', () => {
            const message = getRandomString();
            goToTab('Reported users');

            cy.get('.content-block.clickable').first().should('exist').find(messageButton).click();
            waitShort();

            cy.get('h5').contains('Send message to').should('exist');
            cy.get('#message').type(message);
            cy.get('button').contains('Send message').click();
            waitShort();

            cy.visit('/messages');
            cy.get('.messages').contains(message).should('exist');
        });
        it('can suspend a user through their profile', () => {
            //Waiting to prevent throttling errors
            waitLong();
            const reason = getRandomString();
            const days = Math.floor(Math.random() * 10) + 1;

            cy.visit('/social#Friends');
            waitShort();

            cy.get('#search-by-username').type(user1.username);
            cy.get('button').contains('Search').click();
            waitShort();

            cy.get('a').contains(user1.username).click();
            waitShort();
            
            cy.get('span').contains('Admin actions').find(banButton).click();

            cy.get('h5').contains('Suspend user').should('exist');
            cy.get('#reason').type(reason);
            cy.get('#days').type(days);
            cy.get('button').contains('Suspend user').click();
            waitShort();

            goToAdminPage();
            waitShort();
            goToTab('Suspended users');
            waitShort();

            cy.get('tr').contains(user1.username).should('exist').parent().parent().contains(reason).should('exist').parent().contains(days).should('exist');
        });
    });
    describe('Admins can handle suspensions', () => {
        it('can edit a suspension', () => {
            //Waiting to prevent throttling errors
            waitLong();
            const comment = getRandomString();
            const days = Math.floor(Math.random() * 10) + 1;

            goToTab('Suspended users');
            cy.get('tr').contains(user1.username).should('exist').parent().parent().find(editButton).click();
            waitShort();

            cy.get('h5').should('contain.text', 'Edit suspension of');

            cy.get('#days').type(days);
            cy.get('#comment').type(comment);

            cy.get('button').contains('Submit').click();
            waitShort();

            cy.get('tr').contains(user1.username).should('exist').parent().parent().contains(comment).should('exist');
        });
        it('can end a user suspension', () => {
            const comment = getRandomString();
            
            goToTab('Suspended users');
            cy.get('tr').contains(user1.username).should('exist').parent().parent().find(editButton).click();
            waitShort();

            cy.get('h5').should('contain.text', 'Edit suspension of');
            cy.get('#end-suspension').click();
            cy.get('#comment').type(comment);
            
            cy.get('button').contains('Submit').click();
            waitShort();

            cy.get('tr').contains(user1.username).should('exist').parent().parent().find(unlockedButton).should('exist');
        });
    });
    describe('Admin can see feedback', () => {
        it('can see and archive feedback', () => {
            goToTab('Feedback');

            cy.get('h3').should('contain.text', 'Feedback');

            cy.get('tr').contains('OTHER').parent().find(lockButton).click();
            waitShort();
        });
        it('can toggle to see archived feedback and unarchive feedback', () => {
            goToTab('Feedback');

            cy.get('button').contains('Show archived').click();
            waitShort();
        });
    });
});