import Vue from 'vue';

// from: https://alligator.io/vuejs/global-event-bus/

const toastService = new Vue();

/**
 * 
 * @param {import('resources/types/toast').ToastMessage} param0 
 */
const messageHandler = ({message, variant}) => {
    const title = variant == 'danger' ? 'Error' : 'Success';

    // @ts-ignore
    toastService.$app.$bvToast.toast(message, {
        title,
        toaster: 'b-toaster-top-center',
        solid: true,
        autoHideDelay: 5000,
        appendToast: true,
        variant,
    });
};

toastService.$on('message', messageHandler);

export default toastService;
