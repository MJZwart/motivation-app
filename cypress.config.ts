/* eslint-disable */
/* @ts-nocheck */
import {defineConfig} from 'cypress';

export default defineConfig({
    projectId: 'ake59w',
    e2e: {
        baseUrl: 'http://localhost:8000',
        experimentalSessionAndOrigin: true,
        setupNodeEvents(on, config) {
        // implement node event listeners here
            on('task', {
                setUserData: userData => {
                    global.userData = userData;
                    return null;
                },
                getUserData: () => global.userData ?? {},
            });
            return config;
        },
        video: false,
        screenshotOnRunFailure: true,
    },
});
