import { ref } from "vue";
import axios from 'axios';
import {NewMessage} from 'resources/types/message';

export const hasMessages = ref(false);
export const hasNotifications = ref(false);

// Actions
export const sendMessage = async(message: NewMessage) => {
    await axios.post('/message', message);
};
export const getUnread = async() => {
    const {data} = await axios.get('/unread');
    hasMessages.value = data.hasMessages;
    hasNotifications.value = data.hasNotifications;
};
