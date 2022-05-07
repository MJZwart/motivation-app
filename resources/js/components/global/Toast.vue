<template>
    <div class="custom-toast">
        <div class="custom-toast-sidebar" :class="toastType" />
        <div class="custom-toast-content">
            <div class="custom-toast-header">
                <h4>{{toast.title || toastTitle}}</h4>
                <button @click="dismissToast">X</button>
            </div>
            <div class="text">
                <p v-for="(toast, index) in Object.values(toast)" :key="index">
                    {{getToastMessage(toast)}}
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted} from 'vue';
import {useMainStore} from '/js/store/store';

const mainStore = useMainStore();

onMounted(() => {
    setTimeout(() => {
        dismissToast();
    }, 5000);
});

const props = defineProps({
    /** @type {import('resources/types/toast').Toast} */
    toast: {
        type: Object,
        required: true,
    },
});

function dismissToast() {
    mainStore.clearToast();
}
function getToastMessage(toast) {
    if (Array.isArray(toast)) return toast[0];
    return toast;
}

const toastType = computed(() => {
    for (const item of Object.keys(props.toast)) {
        if (item == 'success' || item == 'error' || item == 'info') return item;
    }
    return 'info';
});
const toastTitle = computed(() => {
    switch (toastType.value) {
        case 'error':
            return 'Error';
        case 'success':
            return 'Success';
        case 'info':
        default:
            return 'Info';
    }
});
</script>

<style lang="scss" scoped>
@import '../../../assets/scss/variables';
.success {
  background-color: #009900;
}
.info {
  background-color: #009999;
}
.error {
  background-color: #990000;
}
.custom-toast {
    color: #2c3e50;
    width: 300px;
    min-height: 50px;
    display: flex;
    flex-direction: row;
    margin-bottom: 0.5rem;
    padding: 0.8rem;
    background-color: white;
    border-top-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
    border-top-right-radius: .5rem;
    box-shadow: 0 0 .5rem $shadow-grey;

    p {
        font-weight: 100;
        margin-top: 3px;
        margin-bottom: 3px;
    } 
    .custom-toast-sidebar {
        width: .5rem;
    }
    .custom-toast-content, .custom-toast-header {
        width: 100%;
        display: flex;
    }
    .custom-toast-content {
        flex-direction: column;
        padding-left: 1rem;
        box-sizing: border-box;
    } 
    .custom-toast-header {
        height: 15px;
        margin-bottom: 1rem;
    }
    button {
        margin-left: auto;
        border: none;
        cursor: pointer;
        background: none;
        color: $primary;
        height: fit-content;
        padding-bottom: 3px;
        border-radius: 0.2rem;
    } 
}

@media (max-width:350px) {
.custom-toast {
    width: 200px;

    p {
        font-weight: 100;
        font-size: small;
    } 
    .custom-toast-sidebar {
        width: .5rem;
    }
    .custom-toast-content {
        padding-left: 0.5rem;
    } 
    .custom-toast-header {
        height: 5px;
    }
}

}
</style>