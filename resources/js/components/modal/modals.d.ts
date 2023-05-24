import {Component} from 'vue'

export type FormModal<T = unknown> = {
    title: string;
    form: T;
    // eslint-disable-next-line no-unused-vars
    submitEvent: (edited: T) => Promise<void> | void;
    component: Component;
}

export type Modal = {
    component: Component;
    modal: FormModal;
}