import {describe, it, expect, vi} from 'vitest';
import {mount} from '@vue/test-utils';
// import Message from '/resources/js/pages/messages/components/Message.vue';
import MessageComp from '/js/pages/messages/components/Message.vue';
import {Message} from 'resources/types/message';
// '/js/pages/messages/components/Message.vue';

// * Test data
const testMessageFromUser = {
    id: 0,
    created_at: new Date('2023-01-01 10:00:00'),
    sender: {
        id: 41,
        username: 'admin',
    },
    recipient: {
        id: 40,
        username: 'test',
    },
    message: 'This is a message',
    read: false,
    visible: true,
    sent_by_user: true,
} as Message;

const testMessageToUser = {
    id: 0,
    created_at: new Date('2023-01-01 10:00:00'),
    sender: {
        id: 40,
        username: 'testname',
    },
    recipient: {
        id: 41,
        username: 'admin',
    },
    message: 'This is a second message',
    read: false,
    visible: true,
    sent_by_user: false,
} as Message;
// * End test data

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

// eslint-disable-next-line max-lines-per-function
describe('Message component', () => {
    afterEach(() => {
        vi.restoreAllMocks();
    });
    it('mounts the component with the given message', () => {
        // Arrange
        const wrapper = mount(MessageComp, {props: {message: testMessageFromUser}});

        // Act
        const messageText = wrapper.get('p').text();

        // Assert
        expect(messageText).toMatch('This is a message');
    });
    it('shows the username of the sender if the sender is not the user', () => {
        // Arrange
        const wrapper = mount(MessageComp, {props: {message: testMessageToUser}});

        // Act
        const messageText = wrapper.get('p').text();

        // Assert
        expect(messageText).toMatch('testname');
    });
    describe('hover and emits', () => { 
        it('should emit a message when clicking the delete button', async () => {
            // Arrange
            const wrapper = mount(MessageComp, {props: {message: testMessageFromUser}});

            // Act
            await wrapper.get('div').trigger('mouseover');
            const classes = wrapper.get('span').classes();

            // Assert
            expect(classes).toMatch('ml-auto');

            // Act
            wrapper.get('icon').trigger('click')
            // Assert
            expect(wrapper.emitted()['deleteMessage'][0]).toEqual([testMessageFromUser]);
        });
        it('should no longer have the delete icon on mouseleave', async() => {
            
            // Arrange
            const wrapper = mount(MessageComp, {props: {message: testMessageFromUser}});

            // Act
            await wrapper.get('div').trigger('mouseover');
            const classes = wrapper.get('div').classes();

            // Assert
            expect(classes).toMatch('hover');

            // Act
            await wrapper.get('div').trigger('mouseleave');
            const newClasses = wrapper.get('div').classes();
            expect(newClasses).not.toMatch('hover')
        });
    });
});