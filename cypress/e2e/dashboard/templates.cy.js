import {getRandomString, waitShort, waitLong} from '../../support/commands';
import {deleteButton, completeTaskButton, editButton} from '../../support/constants';

describe('Templates', () => {
    beforeEach(() => {
        cy.login();
        waitShort();
    });
    const testTemplate = getRandomString();
    const testEdited = getRandomString();
    const testDescription = getRandomString();

    function openTemplatesModal() {
        waitShort();
        cy.get('button').contains('Manage templates').click();
        waitShort();
    }

    describe('user can manage and use templates', () => {
        it('user can make a template', () => {
            openTemplatesModal();

            cy.get('button').contains('New template').click();
            cy.get('#name').type(testTemplate);
            cy.get('#description').type(testDescription);
            cy.get('button').contains('Create new template').click();
            waitShort();

            cy.get('.template-content').contains(testTemplate).should('exist');

            cy.get('.close').click();
            cy.get('.template-content').should('not.exist');
        });
        it('user can edit a template', () => {
            openTemplatesModal();

            cy.get('.template-content').contains(testTemplate).parent().find(editButton).click();

            cy.get('#name').type('{selectAll}' + testEdited);
            cy.get('button').contains('Edit template').click();
            waitShort();

            cy.get('.template-content').contains(testEdited).should('exist');
            cy.get('.template-content').contains(testTemplate).should('not.exist');
        });
        it('user can use a template', () => {
            const parsedText = testEdited + ' - Generic - Difficulty: 3/5';
            cy.get('button').contains('New task').first().click();
            waitShort();

            cy.get('#templates').select(parsedText);

            cy.get('#name').invoke('val').should('eq', testEdited);
            cy.get('button').contains('Create new task').click();
            waitShort();

            cy.get('.task').contains(testEdited).should('exist');
        });
        it('user can delete a template', () => {
            openTemplatesModal();

            cy.get('.template-content').contains(testEdited).parent().find(deleteButton).click();
            cy.on('window:confirm', () => true);

            cy.get('.template-content').contains(testEdited).should('not.exist');
        });
    });
});