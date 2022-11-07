import {getRandomString, waitShort} from '../../support/commands';
import {user1, user2, viewGroupPageButton, kickRemoveButton} from '../../support/constants';

describe('Groups', () => {

    const publicGroupName = getRandomString();
    const groupWithApplicationName1 = getRandomString();
    const groupWithApplicationName2 = getRandomString();

    function loginUser1() {
        cy.login(user1.username, user1.password);
        goToGroupsPage();
    }
    function loginUser2() {
        cy.login(user2.username, user2.password);
        goToGroupsPage();
    }

    function goToGroupsPage() {
        cy.intercept('get', '/api/groups/dashboard').as('getGroups');

        cy.get('a').contains('Social').click();
        cy.wait('@getGroups');
        waitShort();
    }

    function createNewGroup(groupName, groupDesc = getRandomString(), publicGroup = true, requiresApplication = false) {
        cy.get('button').contains('Create new group').click();

        cy.get('#name').type(groupName);
        cy.get('#description').type(groupDesc);
        if (publicGroup) {
            cy.get('#public-checkbox').click();
            if (requiresApplication)
                cy.get('#require-application-checkbox').click();
        }
        cy.get('#create-new-group').click();
        waitShort();
        cy.get('.content-block.clickable').should('contain.text', groupName);
    }

    function openGroupPage(groupName) {
        cy.get('.content-block').contains(groupName).should('exist').parent().parent().find(viewGroupPageButton).click();
        waitShort();
    }

    describe('User 1 can create groups', () => {
        it('can create groups', () => {
            //User 1
            loginUser1();

            //Create group public                   publicGroupName
            createNewGroup(publicGroupName);
            waitShort()
        });
        it('can edit groups', () => {
            const desc = getRandomString();
            loginUser1();

            openGroupPage(publicGroupName);

            //Edit group description                publicGroupName
            cy.get('button').contains('Manage group').click();
            waitShort();
            cy.get('#edit-item-2').click();
            cy.get('#description').type(desc);
            cy.get('#save-2').click();
            cy.get('.modal-body').should('contain.text', desc);
        });
        it('can change a groups join type', () => {
            loginUser1();

            //Make 2 new groups to edit
            createNewGroup(groupWithApplicationName1);
            createNewGroup(groupWithApplicationName2, undefined, undefined, true);

            //Edit group to require application     groupWithApplicationName1
            openGroupPage(groupWithApplicationName1);

            cy.get('button').contains('Manage group').click();
            waitShort();
            cy.get('button').contains('Require application').click();
            waitShort();
            cy.get('button').contains('Do not require application').should('exist');
        });
    });
    describe('User 2 can join groups', () => {
        it('can join a public group and send applications', () => {
            //User 2
            loginUser2();

            cy.get('.tab-item').contains('All groups').click();
            waitShort();

            cy.get('#filter-group-by-name').type(publicGroupName);

            //Join a public group                   publicGroupName
            openGroupPage(publicGroupName);

            cy.get('h2').should('have.text', publicGroupName);
            cy.get('button').contains('Join group').click();
            waitShort();

            cy.get('.content-block').contains('Your rank').parent().should('contain.text', 'member');

            //Send application                      groupWithApplicationName1
            goToGroupsPage();
            waitShort();

            cy.get('.tab-item').contains('All groups').click();
            waitShort();
            cy.get('#filter-group-by-name').type(groupWithApplicationName1);

            openGroupPage(groupWithApplicationName1);

            cy.get('h2').should('have.text', groupWithApplicationName1);
            cy.get('button').contains('Request to join group').click();
            waitShort();
            cy.get('button').contains('Application pending').should('exist').should('have.attr', 'disabled');
            
            //Send another application              groupWithApplicationName2
            goToGroupsPage();
            waitShort();

            cy.get('.tab-item').contains('All groups').click();
            waitShort();
            cy.get('#filter-group-by-name').type(groupWithApplicationName2);

            openGroupPage(groupWithApplicationName2);

            cy.get('h2').should('have.text', groupWithApplicationName2);
            cy.get('button').contains('Request to join group').click();
            waitShort();
            cy.get('button').contains('Application pending').should('exist').should('have.attr', 'disabled');
        });
    });
    describe('User 1 can manage applications and invites', () => {
        it('can reject or accept applications and send invites', () => {
            //User 1
            loginUser1();

            openGroupPage(groupWithApplicationName1);

            //Can see application                   groupWithApplicationName1/groupWithApplicationName2
            cy.get('button').contains('Manage applications').click();
            waitShort();

            //Can accept application                groupWithApplicationName1
            cy.get('.modal-body').contains(user2.username).should('exist').parent().get('button').contains('Accept').click();
            cy.get('.modal-body').should('contain.text', 'No applications yet');
            cy.get('.close').click();

            goToGroupsPage();
            waitShort();

            openGroupPage(groupWithApplicationName2);

            //Can reject application group 2        groupWithApplicationName2
            cy.get('button').contains('Manage applications').click();
            waitShort();

            cy.get('.modal-body').contains(user2.username).should('exist').parent().get('button').contains('Reject').click();
            cy.get('.modal-body').should('contain.text', 'No applications yet');
            cy.get('.close').click();

            //Can send invite to friend (?)
            //TODO add a friend through seeder and allow this part
            //Can send invite to a user             groupWithApplicationName2
            cy.get('button').contains('Invite users').click();
            cy.get('#search-users').type(user2.username);
            cy.get('button').contains('Search').click();
            waitShort();

            cy.get('a').contains(user2.username).should('exist').parent().parent().find('span').contains('Invite').click();
        });
    });
    describe('Users can manage invites', () => {
        it('User 2 can see and accept invites, and becomes part of the group', () => {
            //User 2
            loginUser2();
            cy.visit('/notifications');
            waitShort();

            //User sees notification
            //User can accept the invite            groupWithApplicationName2
            cy.get('.card-body').contains('You have been invited').should('exist').should('contain.text', groupWithApplicationName2)
                .parent().find('span').contains('Accept').click();
            goToGroupsPage();
            cy.get('.content-block').should('contain.text', publicGroupName);
            cy.get('.content-block').should('contain.text', groupWithApplicationName1);
            cy.get('.content-block').should('contain.text', groupWithApplicationName2);
            //User is part of three groups          groupWithApplicationName1, groupWithApplicationName2, publicGroupName
        });
        it('User 1 can kick users and send invite again', () => {
            //User 1
            loginUser1();
            cy.visit('/social');
            waitShort();

            openGroupPage(groupWithApplicationName2);
            cy.get('button').contains('Manage group').click();
            waitShort();

            //Kick user from group                  groupWithApplicationName2
            cy.get('.member-list').contains(user2.username).parent().find(kickRemoveButton).click();
            waitShort();
            cy.get('.modal-body').should('not.contain', user2.username);
            cy.get('.close').click();
            
            //Send invite again                     groupWithApplicationName2
            cy.get('button').contains('Invite users').click();
            waitShort();
            cy.get('#search-users').type(user2.username);
            cy.get('button').contains('Search').click();
            waitShort();

            cy.get('a').contains(user2.username).should('exist').parent().parent().find('span').contains('Invite').click();
        });
        it('User 2 can reject invites and leave a group', () => {
            //User 2
            loginUser2();
            cy.visit('/notifications');
            waitShort();

            //User can reject invite                groupWithApplicationName2
            cy.get('.card-body').contains('You have been invited').should('exist').should('contain.text', groupWithApplicationName2)
                .parent().find('span').contains('Reject').click();
                
            //User can leave group                  groupWithApplicationName1
            cy.visit('/social');
            waitShort();

            openGroupPage(groupWithApplicationName1);
            cy.get('button').contains('Leave group').click();
            cy.on('window:confirm', () => true);
            waitShort();
            cy.get('button').contains('Request to join group').should('exist');
        });
        it('User 1 can send another invite and disband a group', () => {
            //User 1
            loginUser1();
            cy.visit('/social');
            waitShort();

            openGroupPage(groupWithApplicationName2);
            //User can send another invite          groupWithApplicationName2
            cy.get('button').contains('Invite users').click();
            waitShort();
            cy.get('#search-users').type(user2.username);
            cy.get('button').contains('Search').click();
            waitShort();
            
            cy.get('a').contains(user2.username).should('exist').parent().parent().find('span').contains('Invite').click();
            cy.get('.close').click();

            //User can disband group                publicGroupName, groupWithApplicationName2, groupWithApplicationName1
            cy.get('button').contains('Delete group').click();
            cy.on('window:confirm', () => true);
            waitShort();
            
            openGroupPage(groupWithApplicationName1);
            cy.get('button').contains('Delete group').click();
            cy.on('window:confirm', () => true);
            waitShort();
            
            openGroupPage(publicGroupName);
            cy.get('button').contains('Delete group').click();
            cy.on('window:confirm', () => true);
            waitShort();

            //TODO check that the invite has also become inactive
        });
    });
});