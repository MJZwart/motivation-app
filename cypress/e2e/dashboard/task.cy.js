import {getRandomString, waitShort, waitLong} from '../../support/commands';
import {deleteButton, completeTaskButton, editButton} from '../../support/constants';

describe('Tasks', () => {
    beforeEach(() => {
        cy.login();
    });
    const testTask = getRandomString();
    const testDescription = getRandomString();

    describe('Check that you can create a task', () => {
        it('can create a new task', () => {
            //Click the new task button
            cy.get('.new-task').first().click();
            waitShort();
            cy.get('h5').should('have.text', 'New task');
            
            //Create the new task and assert that it exists. May needs a wait or intercept if it fails inconsistently
            cy.get('#name').type(testTask);
            cy.contains('Create new task').click();
            waitShort();
            cy.get('.task-list').first().should('contain.text', testTask);
        });
        it('can edit the newly created task', () => {
            //Get the test task and click the edit button
            cy.contains(testTask).parent().find(editButton).click();
            waitShort();
            cy.get('h5').should('have.text', 'Edit task');

            //Change description and difficulty
            cy.get('#description').type(testDescription);
            cy.get('#difficulty').invoke('val', 1).trigger('input');
            cy.get('button').contains('Edit task').click();
            waitShort();

            //Assert that the new description is entered and the sidebar reflects the new difficulty
            cy.get('.task-list').first().should('contain.text', testDescription);
            cy.contains(testTask).parent().parent().get('.task-sidebar').should('have.class', 'diff-1');
        });
        it('can complete the newly created task and get rewarded', () => {
            //Fetch existing character to compare exp
            cy.contains('Experience:').invoke('text').then((currentExp) => {
                //Completes the task
                cy.contains(testTask).parent().find(completeTaskButton).click();
                waitShort();
                //Checks the experience to see it is higher
                cy.contains('Experience:').invoke('text').should('not.eq', currentExp);
                cy.get('.task-list').first().should('not.contain.text', testTask);
            });
        });
    });

    const testTaskList = getRandomString();
    const testTaskListEdit = getRandomString();
    const testTaskListWithTask = getRandomString();
    const taskListToDelete = getRandomString();
    const testTask1 = getRandomString();

    describe('Check that you can manage task lists', () => {
        //No task list exists within the test scope
        it('can create a new task list', () => {

            //Create a new task list
            cy.get('.new-task-list-button').click();
            cy.get('h5').should('have.text', 'New task list');
            
            cy.get('#name').type(testTaskList);
            cy.get('button').contains('Create new task list').click();

            waitShort();
            //Assert that it is made 
            cy.get('.task-list').last().should('contain.text', testTaskList);
        });
        it('can edit the new task list', () => {
            //Find the newly made task list and click edit
            cy.get('.task-list').contains(testTaskList).parent().find(editButton).click();
            cy.get('h5').should('have.text', 'Edit task list');
            
            //Adds on to the name and asserts that it has been edited
            cy.get('#name').type(testTaskListEdit);
            cy.get('button').contains('Edit task list').click();
            waitLong();
            //After asserting, delete the task list
            cy.get('.task-list').last().should('contain.text', testTaskList + testTaskListEdit).find(deleteButton).click();
            cy.get('h5').should('have.text', 'Delete task list');
            cy.get('button').contains('Delete task list').click();
        });
        it('can delete an task list with tasks, merging them into another', () => {
            //Create new task list with the name testTaskListWithTask
            cy.get('.new-task-list-button').click();
            cy.get('h5').should('have.text', 'New task list');
            cy.get('#name').type(testTaskListWithTask);
            cy.get('button').contains('Create new task list').click();
            waitShort();

            cy.get('.task-list').last().should('contain.text', testTaskListWithTask);
        
            //Create a task in this list with the name $testTask1
            cy.get('.task-list').contains(testTaskListWithTask).parent().parent().contains('New task').click();
            cy.get('h5').should('have.text', 'New task');
            
            cy.get('#name').type(testTask1);
            cy.contains('Create new task').click();

            waitShort();
            cy.get('.task-list').contains(testTaskListWithTask).parent().parent().should('contain.text', testTask1);

            //Delete the task list and merge
            cy.get('.task-list').contains(testTaskListWithTask).parent().find(deleteButton).click();
            cy.get('h5').should('have.text', 'Delete task list');
            cy.get('#deleteOption').select(1).should('contain.text', 'Merge with');
            cy.get('button').contains('Delete task list').click();
            waitShort();

            //Asserting the task list has been deleted but the task remains
            cy.get('.task-list').should('not.contain.text', testTaskListWithTask);
            cy.get('.task-list').should('contain.text', testTask1);
            //Delete the task for cleanup
            cy.contains(testTask1).parent().find(deleteButton).click();
            cy.get('.task-list').should('contain.text', testTask1);
        });
        it('can delete an empty task list', () => {
            //Creates a new task list
            cy.get('.new-task-list-button').click();
            cy.get('h5').should('have.text', 'New task list');
            
            cy.get('#name').type(taskListToDelete);
            cy.get('button').contains('Create new task list').click();
            
            waitShort();
            //Find the newly made task list and press delete
            cy.get('.task-list').last().should('contain.text', taskListToDelete);

            cy.get('.task-list').contains(taskListToDelete).parent().find(deleteButton).click();
            cy.get('h5').should('have.text', 'Delete task list');
            cy.get('button').contains('Delete task list').click();
            cy.on('window:confirm', () => true);
            waitShort();

            cy.get('.task-list').should('not.contain.text', taskListToDelete);
        });
    });
});