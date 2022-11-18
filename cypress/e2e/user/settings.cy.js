import {getRandomString, waitShort} from '../../support/commands';
import {user1, banButton, unlockedButton, tutorialButton, editButton, deleteButton} from '../../support/constants';

describe('Settings', () => {
    function loginUser1() {
        cy.login(user1.username, user1.password);
    }

    beforeEach(() => {
        loginUser1();
    });

    describe('User settings', () => {
        it('User can change password and is logged out', () => {
            //Waiting to prevent throttling issues
            cy.wait(5000);
            const newPass = getRandomString(8);
            cy.visit('/settings#AccountSettings');
            waitShort();

            cy.get('#old_password').type(user1.password);
            cy.get('#password').type(newPass);
            cy.get('#password_confirmation').type(newPass);

            cy.get('button').contains('Update password').click();
            waitShort();

            cy.url().should('not.include', 'settings');

            cy.login(user1.username, newPass);
            waitShort();

            cy.visit('/settings#AccountSettings');
            waitShort();

            cy.get('#old_password').type(newPass);
            cy.get('#password').type(user1.password);
            cy.get('#password_confirmation').type(user1.password);

            cy.get('button').contains('Update password').click();
            waitShort();
        });
        it('User can change email', () => {
            const newEmail = getRandomString() + '@email.com';
            cy.visit('/settings#AccountSettings');
            waitShort();

            cy.get('#email').invoke('val').then((currentEmail) => {
                cy.get('#email').type('{selectAll}' + newEmail);
                cy.get('button').contains('Update e-mail').click();
                waitShort();

                cy.get('#email').invoke('val').should('not.eq', currentEmail).should('eq', newEmail);
            });
        });
        it('User can show or hide tutorials', () => {
            cy.visit('/settings#AccountSettings');
            waitShort();

            cy.get('button').contains('Hide tutorials').click();
            waitShort();

            cy.visit('/overview');
            waitShort();

            cy.get('.card-header').first().should('not.have.descendants', 'svg');

            cy.visit('/settings#AccountSettings');
            waitShort();

            cy.get('button').contains('Turn on tutorials').click();
            waitShort();

            cy.visit('/overview');
            waitShort();

            cy.get('.card-header').first().should('have.descendants', 'svg');
        });
        it('User can change language', () => {
            cy.visit('/settings#AccountSettings');
            waitShort();

            cy.get('span.clickable.fi.fi-nl').first().click();
            waitShort();

            cy.get('h4').contains('Taal').should('exist');
            cy.get('span.clickable.fi.fi-gb').first().click();
            cy.get('h4').contains('Taal').should('not.exist');
            cy.get('h4').contains('Language').should('exist');
        });
        it('User can switch light and dark mode', () => {
            cy.visit('/settings#AccountSettings');
            waitShort();

            cy.get('button').contains('Dark mode').click();
            waitShort();
            cy.get('button').contains('Light mode').click();
            waitShort();
        });
    });

    describe('Profile settings', () => {
        it('User can show and hide various items on profile', () => {
            cy.visit('/settings#ProfileSettings');
            waitShort();

            cy.get('#show_reward').uncheck();
            cy.get('#show_achievements').uncheck();
            cy.get('#show_friends').uncheck();
            cy.get('button').contains('Save profile settings').click();
            waitShort();

            cy.get('.dropDownMenuWrapper').click();
            cy.get('a').contains('Profile').click();
            waitShort();

            cy.get('.card-header').should('not.exist');

            cy.visit('/settings#ProfileSettings');
            waitShort();

            cy.get('#show_reward').check();
            cy.get('#show_achievements').check();
            cy.get('#show_friends').check();
            cy.get('button').contains('Save profile settings').click();
            waitShort();

            cy.get('.dropDownMenuWrapper').click();
            cy.get('a').contains('Profile').click();
            waitShort();

            cy.get('.card-header').contains('Friends').should('exist');
            cy.get('.card-header').contains('Achievements').should('exist');
        });
    });

    describe('Reward settings', () => {
        const charName = getRandomString();
        const villName = getRandomString();
        const newCharName = getRandomString();
        it('User can make a new character and switch to it', () => {
            //Waiting to prevent throttling issues
            cy.wait(5000);
            cy.visit('/settings#RewardSettings');
            waitShort();

            cy.get('#CHARACTER').check();
            cy.get('#NEWchar').check();
            cy.get('#new-object-name').type(charName);
            cy.get('button').contains('Save settings').click();
            waitShort();
        });
        it('User can switch to village', () => {
            cy.visit('/settings#RewardSettings');
            waitShort();

            cy.get('#VILLAGE').check();
            cy.get('#NEWvill').check();
            cy.get('#new-object-name').type(villName);
            cy.get('button').contains('Save settings').click();
            waitShort();
        });
        it('User can switch back to character', () => {
            cy.visit('/settings#RewardSettings');
            waitShort();

            cy.get('#CHARACTER').check();
            cy.contains(charName).click();
            cy.get('button').contains('Save settings').click();
            waitShort();
        });
        it('User can rename a character', () => {
            cy.visit('/settings#RewardSettings');
            waitShort();

            cy.get('table').contains(charName).parent().find(editButton).click();
            waitShort();

            cy.get('#name').type('{selectAll}' + newCharName);
            cy.get('button').contains('Update name').click();
            waitShort();

            cy.get('table').should('contain.text', newCharName);
        });
        it('User can delete a character', () => {
            cy.visit('/settings#RewardSettings');
            waitShort();

            cy.get('table').contains(newCharName).parent().find(deleteButton).click();
            cy.on('window:confirm', () => true);
            waitShort();
            
            cy.get('table').should('not.contain.text', newCharName);
        });
    });
});