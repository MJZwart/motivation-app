describe('Tasks', () => {
    beforeEach(() => {
        cy.login();
    });
    const testTask = Math.random().toString(36).substring(2,7);
    const testDescription = Math.random().toString(36).substring(2,7);

    describe('Check that you can create a task', () => {
        it('can create a new task', () => {
            cy.get('.new-task').first().click();
            cy.wait(1);
            cy.get('h5').should('have.text', 'New task');
            
            cy.get('#name').type(testTask);
            cy.contains('Create new task').click();
            cy.get('.card').first().should('contain.text', testTask);
        });
        it('can edit the newly created task', () => {
            cy.contains(testTask).parent().find('.fa-pen-to-square').click();
            cy.wait(1);
            cy.get('h5').should('have.text', 'Edit task');

            cy.get('#description').type(testDescription);
            cy.get('#difficulty').invoke('val', 1).trigger('input');
            cy.get('button').contains('Edit task').click();
            cy.wait(5);

            cy.get('.card').first().should('contain.text', testDescription);
            cy.contains(testTask).parent().parent().get('.task-sidebar').should('have.class', 'diff-1');
        });
        it('can complete the newly created task and get rewarded', () => {
            //Fetch existing character to compare exp
            cy.contains('Experience:').invoke('text').then((currentExp) => {
                cy.contains(testTask).parent().find('.fa-square-check').click();
                cy.wait(1000);
                cy.contains('Experience:').invoke('text').should('not.eq', currentExp);
                cy.get('.card').first().should('not.contain.text', testTask);
            });
        });
    });

    describe('Check that you can manage task lists', () => {
        
    });
})