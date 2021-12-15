import Vue from 'vue';

// from: https://alligator.io/vuejs/global-event-bus/

const toastService = new Vue();

const messageHandler = ({message, key}) => {
    const {title, variant} = getTitleAndVariant(key);

    toastService.$app.$bvToast.toast(message, {
        title,
        toaster: 'b-toaster-top-center',
        solid: true,
        autoHideDelay: 5000,
        appendToast: true,
        variant,
    });
};

const getTitleAndVariant = (variant) => {
    let variables = {};
    switch(variant){
        case 'danger':
        case 'error':
            variables.title = 'Error';
            variables.variant = 'danger';
            break;
        case 'success':
            variables.title = 'Success';
            variables.variant = 'success';
            break;
        case 'warning':
            variables.title = 'Warning';
            variables.variant = 'warning';
            break;
        case 'info':
        default:
            variables.title = 'Info';
            variables.variant = 'info';
            break;
    }
    return variables;
}

toastService.$on('message', messageHandler);

/**
 * How to send a toast:
 * toastService.$emit('message', {message: insertMessage, variant: insertVariant});
 * Info: blue
 * Warning: yellow
 * Danger: red
 * Success: green
 */

export default toastService;
