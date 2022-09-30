/* eslint-disable no-unused-vars */
// load type definitions that come with Cypress module
/// <reference types="cypress" />

declare namespace Cypress {
    interface Chainable {
        /**
         * Custom command to select DOM element by data-cy attribute.
         * @example cy.getDC('greeting')
         */
        getDC(dataCy: string, deeper?: string): Chainable<JQuery<HTMLElement>>;

        /**
         * Custom command to login
         * @example cy.login()
         */
        login(): void;

        /**
         * Custom command to reset the database
         * @example cy.resetDB()
         */
        resetDB(): void;
    }
}
