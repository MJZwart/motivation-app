import {Component, Raw} from 'vue'

export type FormModal<T = unknown> = {
    title: string;
    form: T;
    // eslint-disable-next-line no-unused-vars
    submitEvent: (edited: T) => Promise<void> | void;
    component: Raw<Component>;
}

export type ShowModal<T = unknown> = {
    title: string;
    props: T;
    component: Raw<Component>;
}

export type Modal = {
    component: Raw<Component>;
    modal: FormModal | ShowModal;
}