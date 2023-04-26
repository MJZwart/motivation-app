/* eslint-disable max-lines-per-function */
import {handleNotificationLink} from '/js/services/notificationLinkService';
import {createTestingPinia} from '@pinia/testing';
import {useFriendStore} from '/js/store/friendStore';
import axios from 'axios';

vi.mock('axios');

describe('Notification link handler', () => {
    beforeEach(() => {
        createTestingPinia();
    });
    describe('friend requests', () => {
        it('correctly handles a notification link to accept a friend request', async () => {
            // Arrange
            const apiType = 'POST';
            const url = '/friend/request/2/accept';
            const friendStore = useFriendStore();
            vi.spyOn(friendStore, 'acceptRequest').mockImplementation(() => Promise.resolve(true))
            // Act
            const successBool = await handleNotificationLink(apiType, url);
            // Assert
            expect(successBool).toBeTruthy();
        });
        it('correctly handles a link to deny a friend request', async() => {
            // Arrange
            const apiType = 'POST';
            const url = '/friend/request/2/deny';
            const friendStore = useFriendStore();
            vi.spyOn(friendStore, 'denyRequest').mockImplementation(() => Promise.resolve(true))
            // Act
            const successBool = await handleNotificationLink(apiType, url);
            // Assert
            expect(successBool).toBeTruthy();
        });
        it('handles a wrong link to handle a friend request', async() => {
            // Arrange
            const apiType = 'POST';
            const url = '/friend/request/2/wrong';
            // Act
            const falseBool = await handleNotificationLink(apiType, url);
            // Assert
            expect(falseBool).toBeFalsy();
        });
    });
    describe('group links', () => {
        it('can correctly handle a group invite link (accept)', async() => {
            // Arrange
            const apiType = 'POST';
            const url = '/groups/invite/2/accept';
            vi.spyOn(axios, 'post').mockImplementation(() => Promise.resolve());
            // Act
            const shouldBeTrue = await handleNotificationLink(apiType, url);
            // Assert
            expect(shouldBeTrue).toBeTruthy();
        });
        it('can correctly handle a group invite link (deny)', async() => {
            // Arrange
            const apiType = 'POST';
            const url = '/groups/invite/2/deny';
            vi.spyOn(axios, 'post').mockImplementation(() => Promise.resolve());
            // Act
            const shouldBeTrue = await handleNotificationLink(apiType, url);
            // Assert
            expect(shouldBeTrue).toBeTruthy();
        });
        it('can correctly handle a group invite link if the group has been deleted', async() => {
            // Arrange
            const apiType = 'POST';
            const url = '/groups/invite/200/deny';
            vi.spyOn(axios, 'post').mockImplementation(() => 
                Promise.reject({response: {data: {data: {error: 'GROUP_DELETED'}}}}));
            // Act
            const shouldBeTrue = await handleNotificationLink(apiType, url);
            // Assert
            expect(shouldBeTrue).toBeTruthy();
        });
        it('can correctly handle a group invite link if there is an error', async() => {
            // Arrange
            const apiType = 'POST';
            const url = '/groups/invite/200/deny';
            vi.spyOn(axios, 'post').mockImplementation(() => 
                Promise.reject({response: {data: {data: {error: 'SOMETHING_UNEXPECTED'}}}}));
            // Act
            const shouldBeFalse = await handleNotificationLink(apiType, url);
            // Assert
            expect(shouldBeFalse).toBeFalsy();
        });
    });
    describe('all other link types', () => {
        it('can handle a random post url', async() => {
            // Arrange
            const apiType = 'POST';
            const url = '/random/link/1';
            vi.spyOn(axios, 'post').mockImplementation(() => Promise.resolve());
            // Act
            const shouldBeTrue = await handleNotificationLink(apiType, url);
            // Assert
            expect(shouldBeTrue).toBeTruthy();
        });
        it('can handle a random wrong post url', async() => {
            // Arrange
            const apiType = 'POST';
            const url = '/random/link/1';
            vi.spyOn(axios, 'post').mockImplementation(() => Promise.reject());
            // Act
            const shouldBeFalse = await handleNotificationLink(apiType, url);
            // Assert
            expect(shouldBeFalse).toBeFalsy();
        });
        it('can handle a random put url', async() => {
            // Arrange
            const apiType = 'PUT';
            const url = '/random/link/1';
            vi.spyOn(axios, 'put').mockImplementation(() => Promise.resolve());
            // Act
            const shouldBeTrue = await handleNotificationLink(apiType, url);
            // Assert
            expect(shouldBeTrue).toBeTruthy();
        });
        it('can handle a random wrong put url', async() => {
            // Arrange
            const apiType = 'PUT';
            const url = '/random/link/1';
            vi.spyOn(axios, 'put').mockImplementation(() => Promise.reject());
            // Act
            const shouldBeFalse = await handleNotificationLink(apiType, url);
            // Assert
            expect(shouldBeFalse).toBeFalsy();
        });
        it('can handle a random delete url', async() => {
            // Arrange
            const apiType = 'DELETE';
            const url = '/random/link/1';
            vi.spyOn(axios, 'delete').mockImplementation(() => Promise.resolve());
            // Act
            const shouldBeTrue = await handleNotificationLink(apiType, url);
            // Assert
            expect(shouldBeTrue).toBeTruthy();
        });
        it('can handle a random wrong delete url', async() => {
            // Arrange
            const apiType = 'DELETE';
            const url = '/random/link/1';
            vi.spyOn(axios, 'delete').mockImplementation(() => Promise.reject());
            // Act
            const shouldBeFalse = await handleNotificationLink(apiType, url);
            // Assert
            expect(shouldBeFalse).toBeFalsy();
        });
    });
});