import {getRandomString, waitLong, waitShort} from '../../support/commands';
import {user1, editButton, deleteButton} from '../../support/constants';

describe('Settings', () => {
    function loginUser1() {
        cy.login(user1.username, user1.password);
    }

    beforeEach(() => {
        loginUser1();
    });

    const accountSettingsUrl = '/settings#account-settings';
    const rewardSettingsUrl = '/settings#reward-settings';
    const profileSettingsUrl = '/settings#profile-settings';
    describe('User settings', () => {
        it('User can change password and is logged out', () => {
            const newPass = getRandomString(8);
            cy.visit(accountSettingsUrl);
            waitShort();

            cy.get('#old_password').type(user1.password);
            cy.get('#password').type(newPass);
            cy.get('#password_confirmation').type(newPass);

            cy.get('button').contains('Update password').click();
            waitShort();

            cy.url().should('not.include', 'settings');

            cy.login(user1.username, newPass);
            waitShort();

            cy.visit(accountSettingsUrl);
            waitShort();

            cy.get('#old_password').type(newPass);
            cy.get('#password').type(user1.password);
            cy.get('#password_confirmation').type(user1.password);

            cy.get('button').contains('Update password').click();
            waitShort();
        });
        it('User can change email', () => {
            const newEmail = getRandomString() + '@email.com';
            cy.visit(accountSettingsUrl);
            waitShort();

            cy.get('#email').invoke('val').then((currentEmail) => {
                cy.get('#email').type('{selectAll}' + newEmail);
                cy.get('button').contains('Update e-mail').click();
                waitShort();

                cy.get('#email').invoke('val').should('not.eq', currentEmail).should('eq', newEmail);
            });
        });
        it('User can show or hide tutorials', () => {
            cy.visit(accountSettingsUrl);
            waitShort();

            cy.get('button').contains('Hide tutorials').click();
            waitShort();

            cy.visit('/overview');
            waitShort();

            cy.get('.content-block').first().should('not.have.descendants', 'svg');

            cy.visit('/settings#account-settings');
            waitShort();

            cy.get('button').contains('Turn on tutorials').click();
            waitShort();

            cy.visit('/overview');
            waitShort();

            cy.get('.content-block').first().should('have.descendants', 'svg');
        });
        it('User can change language', () => {
            cy.visit(accountSettingsUrl);
            waitShort();

            cy.get('span.clickable.fi.fi-nl').first().click();
            waitShort();

            cy.get('h4').contains('Taal').should('exist');
            cy.get('span.clickable.fi.fi-gb').first().click();
            cy.get('h4').contains('Taal').should('not.exist');
            cy.get('h4').contains('Language').should('exist');
        });
        it('User can switch light and dark mode', () => {
            cy.visit(accountSettingsUrl);
            waitShort();

            cy.get('button').contains('Light mode').click();
            waitShort();
            cy.get('button').contains('Dark mode').click();
            waitShort();
        });
    });

    describe('Profile settings', () => {
        it('User can show and hide various items on profile', () => {
            cy.visit(profileSettingsUrl);
            waitShort();

            cy.get('#show-reward').click();
            cy.get('#show-achievements').click();
            cy.get('#show-friends').click();
            cy.get('button').contains('Save profile settings').click();
            waitShort();

            cy.get('.dropDownMenuWrapper').click();
            cy.get('a').contains('Profile').click();
            waitShort();

            cy.get('.content-block').should('not.exist');

            cy.visit(profileSettingsUrl);
            waitShort();

            cy.get('#show-reward').click();
            cy.get('#show-achievements').click();
            cy.get('#show-friends').click();
            cy.get('button').contains('Save profile settings').click();
            waitShort();

            cy.get('.dropDownMenuWrapper').click();
            cy.get('a').contains('Profile').click();
            waitShort();

            cy.get('.content-block').contains('Friends').should('exist');
            cy.get('.content-block').contains('Achievements').should('exist');
        });
    });

    describe('Reward settings', () => {
        const charName = getRandomString();
        const villName = getRandomString();
        const newCharName = getRandomString();
        it('User can make a new character and switch to it', () => {
            waitLong();
            cy.visit(rewardSettingsUrl);
            waitShort();

            cy.get('#CHARACTER').check();
            cy.get('#NEWchar').check();
            cy.get('#new-object-name').type(charName);
            cy.get('button').contains('Save settings').click();
            waitShort();
        });
        it('User can switch to village', () => {
            cy.visit(rewardSettingsUrl);
            waitShort();

            cy.get('#VILLAGE').check();
            cy.get('#NEWvill').check();
            cy.get('#new-object-name').type(villName);
            cy.get('button').contains('Save settings').click();
            waitShort();
        });
        it('User can switch back to character', () => {
            cy.visit(rewardSettingsUrl);
            waitShort();

            cy.get('#CHARACTER').check();
            cy.contains(charName).click();
            cy.get('button').contains('Save settings').click();
            waitShort();
        });
        it('User can rename a character', () => {
            cy.visit(rewardSettingsUrl);
            waitShort();

            cy.get('table').contains(charName).parent().find(editButton).click();
            waitShort();

            cy.get('#name').type('{selectAll}' + newCharName);
            cy.get('button').contains('Update name').click();
            waitShort();

            cy.get('table').should('contain.text', newCharName);
        });
        it('User can delete a character', () => {
            cy.visit(rewardSettingsUrl);
            waitShort();

            cy.get('table').contains(newCharName).parent().find(deleteButton).click();
            cy.on('window:confirm', () => true);
            waitShort();
            
            cy.get('table').should('not.contain.text', newCharName);
        });
    });
});