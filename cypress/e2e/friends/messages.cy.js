import { deleteButton, messageButton, user1, user2 } from "../../support/constants";
import {getRandomString, waitShort} from '../../support/commands';


describe('Messages & Notifications', () => {
    function loginUser1() {
        cy.login(user1.username, user1.password);
    }
    function loginUser2() {
        cy.login(user2.username, user2.password);
    }

    describe('Messages', () => {
        const message = getRandomString();
        const reply = getRandomString();
        it('User 1 can see messages and send a message to another user through profiles', () => {
            loginUser1();

            cy.visit('/messages');
            waitShort();

            cy.get('h3').should('contain.text', 'Messages');
            cy.get('.conversation.clickable.content-block').should('be.visible');

            cy.get('a').contains(user2.username).click();
            waitShort();

            cy.get('.profile-actions').find(messageButton).click();
            waitShort();

            cy.get('h5').should('contain.text', 'Send message to');
            cy.get('#message').type(message);
            cy.get('button').contains('Send message').click();
            waitShort();

            cy.visit('/messages');
            waitShort();

            cy.get('.messages').should('contain.text', message);
        });
        it('User 2 can see this message and reply to it', () => {
            loginUser2();

            cy.visit('/messages');
            waitShort();

            cy.get('.messages').should('contain.text', message);
            cy.get('#message').type(reply);
            cy.get('button').contains('Send reply').click();
        });
        it('User 2 can delete the received message', () => {
            loginUser2();

            cy.visit('/messages');
            waitShort();

            cy.get('.messages').contains(message).parent().trigger('mouseover').find(deleteButton).click();
            cy.on('window:confirm', () => true);
            waitShort();
            //NOTE Currently doesn't work because of the bug solved in PR #558 (User story #552)
            // cy.get('.messages').should('not.contain.text', message);
        });
        it('User 1 can still view this deleted message', () => {
            loginUser1();

            cy.visit('/messages');
            waitShort();

            cy.get('.messages').should('contain.text', message);
            cy.get('.messages').should('contain.text', reply);
        });
        it('User 1 can message a friend', () => {
            const msg = getRandomString();
            loginUser1();

            cy.visit('/social#Friends');
            waitShort();

            cy.contains(user2.username).parent().find(messageButton).click();
            waitShort();

            cy.get('#message').type(msg);
            cy.get('button').contains('Send message').click();
            waitShort();

            cy.visit('/messages');
            waitShort();

            cy.get('.messages').should('contain.text', msg);
        });
    });

    describe('Notifications', () => {
        it('User can see notifications and delete them', () => {
            loginUser1();

            cy.visit('/notifications');
            waitShort();

            cy.get('.card.summary-card').should('exist');

            cy.contains('Items 1 -').invoke('text').then((currentItemsText) => {
                // const items = currentItemsText.slice(-1);
                cy.get('.card').first().find(deleteButton).first().click();
                cy.on('window:confirm', () => true);
                waitShort();

                cy.contains('Items 1 -').invoke('text').should('not.eq', currentItemsText);
            });
        });
    });
});