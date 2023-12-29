import {Error} from 'resources/types/error';
import {ref} from 'vue';

export const errors = ref<Error | null>();

export function setErrorMessages(errorMessages: Error) {
    errors.value = errorMessages;
}
export function clearErrors() {
    errors.value = null;
} 

export function hasError(errorName: string) {
    if (!errors.value) return false;
    return !!errors.value[errorName];
}