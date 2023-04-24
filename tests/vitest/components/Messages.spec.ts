import {describe, it, expect, vi} from 'vitest';
import MessagesComp from '/js/pages/messages/Messages.vue';
import {mount, config, shallowMount} from '@vue/test-utils';
import {createPinia, setActivePinia} from 'pinia';
import {axiosMock} from '../mocks/axios';

vi.mock('vue-i18n', () => {
    return {
        createI18n: vi.fn(),
        useI18n:() => {
            return {
                t: (key: string) => key,
            }
        },
    }
});

config.global.mocks.$t = (key: string) => key;

function mountMessages() {
    return shallowMount(MessagesComp, 
        {global: 
            {stubs: {
                'router-link': true,
                'Loading': true,
                'SimpleTextarea': true,
                'SubmitButton': true,
                'Modal': true,
            }}});
}

const testMessage = {
    id: 50,
    message: 'this is a test message',
    created_at: '2023-01-01T12:00:00.000000Z',
    read: 0,
    sent_by_user: false,
    sender: {
        id: 11,
        username: 'testsender',
    },
}
const testConversations = {
    0: {
        id: 1,
        recipient: {
            id: 10,
            username: 'testname',
        },
        conversation_id: 12345,
        last_message: testMessage,
        messages: [testMessage],
        updated_at: '2023-01-01T12:00:00.000000Z',
        is_blocked: false,
    },
}

axiosMock.onGet('/message/conversations').replyOnce(200, {data: testConversations});
axiosMock.onPut('/message/conversation/1/read').replyOnce(200);
axiosMock.onGet('/unread').replyOnce(200, {data: {hasNotifications: false, hasMessages: false}});

// eslint-disable-next-line max-lines-per-function
describe('Messages page', () => {
    beforeEach(() => {
        setActivePinia(createPinia());
    });
    afterEach(() => {
        vi.restoreAllMocks();
    });
    it('mounts the component and only shows loading', async() => {
        // Arrange
        const wrapper = mountMessages();
        // Act
        const loadingStub = wrapper.get('loading-stub');
        const header = wrapper.get('h3');
        // Assert
        await expect(loadingStub).toBeDefined();
        await expect(header).toMatch('Messages');
    });
});
// Arrange
// Act
// Assert