//Test to see if it works at all

before(() => {
    cy.login();
    // Cypress.Cookies.defaults({preserve: ['Authorization']});
});

describe('test testing for cypress', () => {
    it('should log in', () => {
        cy.get('.home-grid').should('exist');
    })
})