describe('Tasks', () => {
    beforeEach(() => {
        cy.login();
    });
    const testTask = Math.random().toString(36).substring(2,7);
    const testDescription = Math.random().toString(36).substring(2,7);

    // describe('Check that you can create a task', () => {
    //     it('can create a new task', () => {
    //         cy.get('.new-task').first().click();
    //         cy.wait(1);
    //         cy.get('h5').should('have.text', 'New task');
            
    //         cy.get('#name').type(testTask);
    //         cy.contains('Create new task').click();
    //         cy.get('.card').first().should('contain.text', testTask);
    //     });
    //     it('can edit the newly created task', () => {
    //         cy.contains(testTask).parent().find('.fa-pen-to-square').click();
    //         cy.wait(1);
    //         cy.get('h5').should('have.text', 'Edit task');

    //         cy.get('#description').type(testDescription);
    //         cy.get('#difficulty').invoke('val', 1).trigger('input');
    //         cy.get('button').contains('Edit task').click();
    //         cy.wait(5);

    //         cy.get('.card').first().should('contain.text', testDescription);
    //         cy.contains(testTask).parent().parent().get('.task-sidebar').should('have.class', 'diff-1');
    //     });
    //     it('can complete the newly created task and get rewarded', () => {
    //         //Fetch existing character to compare exp
    //         cy.contains('Experience:').invoke('text').then((currentExp) => {
    //             cy.contains(testTask).parent().find('.fa-square-check').click();
    //             cy.wait(1000);
    //             cy.contains('Experience:').invoke('text').should('not.eq', currentExp);
    //             cy.get('.card').first().should('not.contain.text', testTask);
    //         });
    //     });
    // });

    const testTaskList = Math.random().toString(36).substring(2,7);
    const testTaskListEdit = Math.random().toString(36).substring(2,7);
    const testTaskListWithTask = Math.random().toString(36).substring(2,7);
    const taskListToDelete = Math.random().toString(36).substring(2,7);
    const testTask1 = Math.random().toString(36).substring(2,7);

    describe('Check that you can manage task lists', () => {
        //No task list exists within the test scope
        it('can create a new task list', () => {
            cy.intercept('/api/tasklists').as('storeTasklist');

            cy.get('.new-task-list-button').click();
            cy.get('h5').should('have.text', 'New task list');
            
            cy.get('#name').type(testTaskList);
            cy.get('#create-new-task-list-button').click();
            cy.wait('@storeTasklist').then(() => {
                cy.wait(1000);
                cy.get('.task-list').last().should('contain.text', testTaskList);
            });
        });
        //Task list with the name $testTaskList has been created
        it('can edit the new task list', () => {
            cy.get('.task-list').contains(testTaskList).find('.fa-pen-to-square').click();
            cy.get('h5').should('have.text', 'Edit task list');
            
            cy.get('#name').type(testTaskListEdit);
            cy.contains('Update task list').click();
            cy.wait(5000);
            cy.get('.task-list').last().should('contain.text', testTaskList + testTaskListEdit);
        });
        //Task list is now called $testTaskListEdit
        it('can delete an task list with tasks, merging them into another', () => {
            //Create new task list with the name testTaskListWithTask
            cy.intercept('/api/tasklists').as('storeTasklist');
            cy.intercept('/api/tasks').as('storeTask');
            cy.get('.new-task-list-button').click();
            cy.get('h5').should('have.text', 'New task list');
            cy.get('#name').type(testTaskListWithTask);
            cy.get('#create-new-task-list-button').click();
            cy.wait(1000);
            cy.wait('@storeTasklist').then(() => {
                cy.wait(1000);
                cy.get('.task-list').last().should('contain.text', testTaskListWithTask);
            
                //Create a task in this list with the name $testTask1
                cy.get('.task-list').contains(testTaskListWithTask).parent().parent().contains('New task').click();
                cy.get('h5').should('have.text', 'New task');
                
                cy.get('#name').type(testTask1);
                cy.contains('Create new task').click();
                cy.wait('@storeTask').then(() => {
                    cy.wait(1000);
                    cy.get('.task-list').contains(testTaskListWithTask).parent().should('contain.text', testTask1);

                    //Delete the task list and merge
                    cy.get('.task-list').contains(testTaskList).find('.fa-trash').click();
                    cy.get('h5').should('have.text', 'Delete task list');
                    cy.get('#deleteOption').select(1).should('contain.text', 'Merge with');
                    cy.get('.block').contains('Delete task list').click();
                    cy.wait(1000);

                    cy.get('.task-list').should('not.contain.text', testTaskListWithTask);
                    cy.get('.task-list').should('contain.text', testTask1);
                });
            });
        });
        it('can delete an empty task list', () => {
            cy.intercept('/api/tasklists').as('storeTasklist');
            cy.get('.new-task-list-button').click();
            cy.get('h5').should('have.text', 'New task list');
            
            cy.get('#name').type(taskListToDelete);
            cy.get('#create-new-task-list-button').click();
            cy.wait('@storeTasklist').then(() => {
                cy.get('.task-list').last().should('contain.text', taskListToDelete);

                cy.get('.task-list').contains(taskListToDelete).find('.fa-trash').click();
                cy.get('h5').should('have.text', 'Delete task list');
                cy.get('.block').contains('Delete task list').click();
                cy.on('window:confirm', () => true);
                cy.wait(5000);

                cy.get('.task-list').should('not.contain.text', taskListToDelete);

                //Cleanup
            });
        });
    });
});