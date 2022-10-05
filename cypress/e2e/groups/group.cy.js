import {TEST_USER_1, TEST_USER_2, getRandomString} from '../../support/commands';

describe('Groups', () => {

    const publicGroupName = getRandomString();
    const groupWithApplicationName1 = getRandomString();
    const groupWithApplicationName2 = getRandomString();

    const globalLongWait = 3000;
    const globalShortWait = 1000;

    const user1 = TEST_USER_1;
    const user2 = TEST_USER_2;

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
    }

    describe('User 1 can create groups', () => {
        it('can create groups', () => {
            cy.intercept('post', '/api/groups').as('storeGroup');
            //User 1
            loginUser1();

            //Create group public                   publicGroupName
            createNewGroup(publicGroupName);

            cy.wait('@storeGroup');
            cy.wait(globalShortWait)
        });
        it('can edit groups', () => {
            const desc = getRandomString();
            loginUser1();

            cy.get('.content-block').contains(publicGroupName).should('exist').parent().parent().find('.fa-magnifying-glass').click();
            cy.wait(globalLongWait);

            //Edit group description                publicGroupName
            cy.get('button').contains('Manage group').click();
            cy.wait(globalLongWait);
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
            cy.get('.content-block').contains(groupWithApplicationName1).should('exist').parent().parent().find('.fa-magnifying-glass').click();
            cy.wait(globalLongWait);

            cy.get('button').contains('Manage group').click();
            cy.wait(globalLongWait);
            cy.get('button').contains('Require application').click();
            cy.wait(globalShortWait);
            cy.get('button').contains('Do not require application').should('exist');
        });
    });
    describe('User 2 can join groups', () => {
        it('can join a public group and send applications', () => {
            //User 2
            loginUser2();

            cy.get('.tab-item').contains('All groups').click();
            cy.wait(globalLongWait);

            //Join a public group                   publicGroupName
            cy.get('.content-block').contains(publicGroupName).should('exist').parent().parent().find('.fa-magnifying-glass').click();
            cy.wait(globalLongWait);

            cy.get('h2').should('have.text', publicGroupName);
            cy.get('button').contains('Join group').click();
            cy.wait(2000);

            cy.get('.content-block').contains('Your rank').parent().should('contain.text', 'member');

            //Send application                      groupWithApplicationName1
            goToGroupsPage();
            cy.wait(globalLongWait);

            cy.get('.tab-item').contains('All groups').click();
            cy.wait(globalLongWait);

            cy.get('.content-block').contains(groupWithApplicationName1).should('exist').parent().parent().find('.fa-magnifying-glass').click();
            cy.wait(globalLongWait);

            cy.get('h2').should('have.text', groupWithApplicationName1);
            cy.get('button').contains('Request to join group').click();
            cy.wait(globalLongWait);
            cy.get('button').contains('Application pending').should('exist').should('have.attr', 'disabled');
            
            //Send another application              groupWithApplicationName2
            goToGroupsPage();
            cy.wait(globalLongWait);

            cy.get('.tab-item').contains('All groups').click();
            cy.wait(globalLongWait);

            cy.get('.content-block').contains(groupWithApplicationName2).should('exist').parent().parent().find('.fa-magnifying-glass').click();
            cy.wait(globalLongWait);

            cy.get('h2').should('have.text', groupWithApplicationName2);
            cy.get('button').contains('Request to join group').click();
            cy.wait(globalLongWait);
            cy.get('button').contains('Application pending').should('exist').should('have.attr', 'disabled');
        });
    });
    describe('User 1 can manage applications and invites', () => {
        it('can reject or accept applications and send invites', () => {
            //User 1
            loginUser1();

            cy.get('.content-block').contains(groupWithApplicationName1).should('exist').parent().parent().find('.fa-magnifying-glass').click();
            cy.wait(globalLongWait);

            //Can see application                   groupWithApplicationName1/groupWithApplicationName2
            cy.get('button').contains('Manage applications').click();
            cy.wait(globalLongWait);

            //Can accept application                groupWithApplicationName1
            cy.get('.modal-body').contains('cyptest2').should('exist').parent().get('button').contains('Accept').click();
            cy.get('.modal-body').should('contain.text', 'No applications yet');
            cy.get('.close').click();

            goToGroupsPage();
            cy.wait(globalLongWait);

            cy.get('.content-block').contains(groupWithApplicationName2).should('exist').parent().parent().find('.fa-magnifying-glass').click();
            cy.wait(globalLongWait);

            //Can reject application group 2        groupWithApplicationName2
            cy.get('button').contains('Manage applications').click();
            cy.wait(globalLongWait);

            cy.get('.modal-body').contains('cyptest2').should('exist').parent().get('button').contains('Reject').click();
            cy.get('.modal-body').should('contain.text', 'No applications yet');
            cy.get('.close').click();

            //Can send invite to friend (?)
            //TODO add a friend through seeder and allow this part
            //Can send invite to a user             groupWithApplicationName2
            cy.get('button').contains('Invite users').click();
            cy.get('#search-users').type('cyptest2');
            cy.get('button').contains('Search').click();
            cy.wait(globalLongWait);

            cy.get('a').contains('cyptest2').should('exist').parent().parent().find('span').contains('Invite').click();
        });
    });
    describe('Users can manage invites', () => {
        it('User 2 can see and accept invites, and becomes part of the group', () => {
            //User 2
            loginUser2();
            cy.visit('/notifications');
            cy.wait(globalLongWait);

            //User sees notification
            //User can accept the invite            groupWithApplicationName2
            cy.get('.card-body').contains('You have been invited').should('exist').should('contain.text', groupWithApplicationName2)
                .parent().find('span').contains('Accept').click().should('have.class', 'disabled');
            goToGroupsPage();
            cy.get('.content-block').should('contain.text', publicGroupName);
            cy.get('.content-block').should('contain.text', groupWithApplicationName1);
            cy.get('.content-block').should('contain.text', groupWithApplicationName2);
            //User is part of three groups          groupWithApplicationName1, groupWithApplicationName2, publicGroupName
        });
        it('User 1 can kick users and send invite again', () => {
            //User 1
            //Kick user from group                  groupWithApplicationName2
            //Send invite again                     groupWithApplicationName2
        });
        it('User 2 can reject invites and leave a group', () => {
            //User 2
            //User can reject invite                groupWithApplicationName2
            //User can leave group                  groupWithApplicationName1
        });
        it('User 1 can send another invite and disband a group', () => {
            //User 1
            //User can send another invite          groupWithApplicationName2
            //User can disband group                publicGroupName
        });
    });
});