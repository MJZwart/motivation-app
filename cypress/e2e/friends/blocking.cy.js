import {waitShort, waitLong} from '../../support/commands';
import {user1, user2, user3, blockButton, unblockButton, kickRemoveButton, globalLongWait} from '../../support/constants';

describe('Blocking', () => {
    
    function loginUser1() {
        cy.login(user1.username, user1.password);
    }
    function loginUser2() {
        cy.login(user2.username, user2.password);
    }

    function goToFriendsPage() {
        cy.intercept('get', '/api/friend').as('getFriends');

        cy.get('a').contains('Social').click();
        cy.get('button').contains('Friends').click();
        cy.wait('@getFriends');
        waitShort();
    }
    function searchOnUsernameInFriendsPanel(username) {
        cy.get('#search-by-username').type(username);
        cy.get('button').contains('Search').click();
        waitLong();
    }
    
    describe('User 1 can block user 2', () => {
        it('can block a user', () => {
            //User 1
            loginUser1();

            goToFriendsPage();
            searchOnUsernameInFriendsPanel(user2.username);

            cy.get('a').contains(user2.username).click();
            waitLong();

            cy.get(blockButton).click();
            cy.on('window:confirm', () => true);
            waitShort();

            cy.get('a').contains('Social').click();
            cy.get('button').contains('Blocklist').click();
            waitShort();

            cy.get('a').contains(user2.username).should('exist');
        });
        it('blocks friend requests', () => {
            //User 2
            loginUser2();

            goToFriendsPage();
            searchOnUsernameInFriendsPanel(user1.username);

            cy.get('a').contains(user1.username).should('not.exist');
        });
        it('user 1 can unblock a user', () => {
            //User 1
            loginUser1();

            cy.get('a').contains('Social').click();
            cy.get('button').contains('Blocklist').click();
            waitShort();

            cy.get('a').contains(user2.username).should('exist').parent().find(unblockButton).click();
            waitShort();
            cy.get('a').contains(user2.username).should('not.exist');
        });
    });
});