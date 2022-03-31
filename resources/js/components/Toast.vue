<template>
    <div class="custom-toast">
        <div class="custom-toast-sidebar" :class="toastType" />
        <div class="custom-toast-content">
            <div class="custom-toast-header">
                <h4>{{toast.title || toastTitle}}</h4>
                <button @click="dismissToast">X</button>
            </div>
            <div class="text">
                <p v-for="(toast, index) in Object.values(this.toast)" :key="index">
                    {{getToastMessage(toast)}}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        toast: {
            type: Object,
            required: true,
        },
    },
    created() {
        setTimeout(() => {
            this.dismissToast();
        }, 5000);
    },
    methods: {
        dismissToast() {
            this.$store.commit('clearToast', this.toast.title);
        },
        getToastMessage(toast) {
            if (Array.isArray(toast)) return toast[0];
            return toast;
        },
    },
    computed: {
        toastTitle() {
            switch (this.toastType) {
                case 'error':
                    return 'Error';
                case 'success':
                    return 'Success';
                case 'info':
                default:
                    return 'Info';
            }
        },
        toastType() {
            for (const item of Object.keys(this.toast)) {
                if (item == 'success' || item == 'error' || item == 'info') return item;
            }
            return 'info';
        },
    },
}
</script>

<style lang="scss" scoped>
@import '../../assets/scss/variables';
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
    width: 500px;
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
        height: 25px;
        margin-bottom: 1rem;
    }
    button {
        margin-left: auto;
        border: none;
        cursor: pointer;
        background: none;
    } 
    button:hover {
        background: #e7e7e7;
    }
}
</style>