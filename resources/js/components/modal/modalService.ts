import {Component, markRaw, ref} from 'vue';
import {Modal} from './modals';
import FormModalVue from './FormModal.vue';

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
    // @ts-ignore
    modals.value.push({component: markRaw(FormModalVue), modal: modal});
}