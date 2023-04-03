import {describe, it, expect} from 'vitest';
import {mount} from '@vue/test-utils';
//@ts-ignore
import Message from '../../../resources/js/pages/messages/components/Message.vue';
// '/js/pages/messages/components/Message.vue';

const testMessage = {
    id: 0,
    created_at: '2023-01-01 10:00:00',
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
}

describe('Message component', () => {
    // describe('', () => {
    it('mounts the component', () => {
        // Arrange
        const wrapper = mount(Message, {props: {message: testMessage}});

        // Act
        const classes = wrapper.get('div').classes();

        // Assert
        expect(classes).toMatch('break-word');
    });
    // });
});