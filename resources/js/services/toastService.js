import Vue from 'vue';

// from: https://alligator.io/vuejs/global-event-bus/

const toastService = new Vue();

const messageHandler = ({message, variant}) => {
    const title = getTitle(variant);

    toastService.$app.$bvToast.toast(message, {
        title,
        toaster: 'b-toaster-top-center',
        solid: true,
        autoHideDelay: 5000,
        appendToast: true,
        variant,
    });
};

const getTitle = (variant) => {
    let title;
    switch(variant){
        case 'danger':
            title = 'Error';
            break;
        case 'success':
            title = 'Success';
            break;
        case 'warning':
            title = 'Warning';
            break;
        case 'info':
            title = 'Info';
            break;
    }
    return title;
}

toastService.$on('message', messageHandler);

/**
 * How to send a toast:
 * toastService.$emit('message', {message: insertMessage, variant: insertVariable});
 * Info: blue
 * Warning: yellow
 * Danger: red
 * Success: green
 */

export default toastService;
