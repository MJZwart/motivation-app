import {Component, markRaw, ref} from 'vue';
import {Modal} from './modals';
import FormModalVue from './FormModal.vue';
import ShowModalVue from './ShowModal.vue';
import ConfirmModalVue from './ConfirmModal.vue';
import SendMessageVue from '/js/pages/messages/components/SendMessage.vue';
import {clearErrors} from '/js/services/errorService';
import i18n from '../../i18n';

const { t } = i18n.global;

// * Managing modal state
export const modals = ref<Modal[]>([]);

export function deleteModal(index: number) {
    modals.value.splice(index);
}

// * Types of modals
export function formModal<T = unknown>(
    form: T, 
    component: Component, 
    // eslint-disable-next-line no-unused-vars
    submitEvent: (edited: T) => Promise<void> | void, 
    title: string) {
    const modal = {
        form: form,
        component: markRaw(component),
        submitEvent: submitEvent,
        title: title,
    }
    clearErrors();
    // @ts-ignore
    modals.value.push({component: markRaw(FormModalVue), modal: modal});
}

export function showModal<T = unknown>(
    props: T,
    component: Component,
    title: string) {
    const modal = {
        props: props,
        component: markRaw(component),
        title: title,
    }
    clearErrors();
    modals.value.push({component: markRaw(ShowModalVue), modal: modal});
}

export function confirmModal(
    text: string,
    confirmFunction: () => void,
    title = t('confirm'),
    confirmText = t('yes'),
    cancelText = t('cancel'),
){
    const modal = {text, confirmFunction, title, confirmText, cancelText}
    clearErrors();
    modals.value.push({component: markRaw(ConfirmModalVue), modal});
}

export function sendMessageModal(username: string, user_id: string | number) {
    showModal({user: {id: user_id, username}}, SendMessageVue, 'send-message');
}