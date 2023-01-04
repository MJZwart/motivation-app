import {waitShort, waitLong} from '../../support/commands';
import {user1, user3, lockButton, unlockedButton} from '../../support/constants';

describe('Blocking', () => {
    
    function loginUser1() {
        cy.login(user1.username, user1.password);
    }
    function loginUser3() {
        cy.login(user3.username, user3.password);
    }

    function goToFriendsPage() {
        cy.get('a').contains('Social').click();
        waitShort();
        cy.get('button').contains('Friends').click();
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
            searchOnUsernameInFriendsPanel(user3.username);

            cy.get('a').contains(user3.username).click();
            waitLong();

            cy.get(lockButton).click();
            cy.on('window:confirm', () => true);
            waitShort();

            cy.get('a').contains('Social').click();
            waitShort();
            cy.get('button').contains('Blocklist').click();
            waitShort();

            cy.get('a').contains(user3.username).should('exist');
        });
        it('blocks friend requests', () => {
            //User 2
            loginUser3();

            goToFriendsPage();
            searchOnUsernameInFriendsPanel(user1.username);

            cy.get('a').contains(user1.username).should('not.exist');
        });
        it('user 1 can unblock a user', () => {
            //User 1
            loginUser1();

            cy.get('a').contains('Social').click();
            waitShort();
            cy.get('button').contains('Blocklist').click();
            waitLong();

            cy.get('a').contains(user3.username).should('exist').parent().find(unlockedButton).click();
            waitShort();
            cy.get('a').contains(user3.username).should('not.exist');
        });
    });
});