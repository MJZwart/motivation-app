import {waitShort, waitLong} from '../../support/commands';
import {user1, admin, user3, sendFriendRequestButton, acceptButton, kickRemoveButton} from '../../support/constants';

describe('Friends', () => {
    
    function loginUser1() {
        cy.login(user1.username, user1.password);
    }
    function loginadmin() {
        cy.login(admin.username, admin.password);
    }
    function loginUser3() {
        cy.login(user3.username, user3.password);
    }

    function searchOnUsernameInFriendsPanel(username) {
        cy.get('#search-by-username').type(username);
        cy.get('button').contains('Search').click();
        waitShort();
    }

    function goToFriendsPage() {
        cy.intercept('get', '/api/friend').as('getFriends');

        cy.get('a').contains('Social').click();
        cy.get('button').contains('Friends').click();
        cy.wait('@getFriends');
        waitShort();
    }

    function sendFriendRequest(username) {
        cy.get('td').contains(username).should('exist').parent().parent().find(sendFriendRequestButton).click();
        waitShort();
    }

    describe('User 1 can send and cancel friend requests', () => {
        it('can send friend requests', () => {
            //User 1
            loginUser1();
            goToFriendsPage();

            //User 1 can send a friend request to user 2 and user 3
            searchOnUsernameInFriendsPanel(admin.username);

            sendFriendRequest(admin.username);

            searchOnUsernameInFriendsPanel(user3.username);

            sendFriendRequest(user3.username);
        });
        it('can cancel friend request and send another', () => {
            //User 1
            loginUser1();
            goToFriendsPage();

            //User 1 can cancel friend request to user 3 and send another request
            cy.get('.outgoing-request').contains(user3.username).find(kickRemoveButton).click();
            waitShort();

            cy.get('.outgoing-request').should('contain.text', admin.username).should('not.contain.text', user3.username);

            searchOnUsernameInFriendsPanel(user3.username);

            sendFriendRequest(user3.username);
        });
    });

    describe('Users can manage friend requests', () => {
        it('user 2 can reject a friend request through notifications', () => {
            loginadmin();
            cy.visit('/notifications');
            waitLong();

            //User 2 can reject a friend request. The friendship is not visible in the friends panel
            cy.get('.notification-link.active').contains('Deny').should('exist').click();
            waitShort();
            
            goToFriendsPage();
            cy.get('#friendships').should('not.contain.text', user1.username);

            //User 2 can send a friend request back
            searchOnUsernameInFriendsPanel(user1.username);
            sendFriendRequest(user1.username);
        });
        it('user 1 can accept friend request through notifications', () => {
            //User 1
            loginUser1();
            goToFriendsPage();

            //User 1 has 1 request from user 2
            cy.get('#incoming-friend-requests').should('contain.text', admin.username);

            //User 1 can accept through notifications
            cy.visit('/notifications');
            waitLong();

            cy.get('.notification-link.active').contains('Accept').should('exist').click();
            waitShort();

            goToFriendsPage();
            cy.get('#friendships').should('contain.text', admin.username);
        });
        it('user 3 can accept a friend request', () => {
            //User 3
            loginUser3();
            goToFriendsPage();

            //User 3 has 1 request from user 1
            cy.get('#incoming-friend-requests').should('contain.text', user1.username).parent().find(acceptButton).click();
            waitShort();
            cy.get('#friendships').should('contain.text', user1.username);
        });
    });
    describe('Users can manage friendships', () => {
        it('user 1 can delete an existing friendship', () => {
            //User 1
            loginUser1();
            goToFriendsPage();

            cy.get('#friendships').contains(user3.username).parent().find(kickRemoveButton).click();
            cy.get('#friendships').contains(admin.username).parent().find(kickRemoveButton).click();
            cy.on('window:confirm', () => true);
            waitShort();

            cy.get('#friendships').should('not.contain.text', user3.username);
        });
        it('the inverse relationship is also removed', () => {
            //User 3
            loginUser3();
            goToFriendsPage();

            cy.get('#friendships').should('not.contain.text', user1.username);
        });
    });
});