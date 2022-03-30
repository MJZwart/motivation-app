<template>
    <div class="toast-temp">
        <div class="header" :class="toast.type">
            <h3>{{toast.title}}</h3>
        </div>
        <div class="content">
            <p>{{toast.message}}</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Toasts',
    props: {
        toast: {
            type: Object,
            required: true,
        },
    },
    created() {
        setTimeout(() => {
            // this.dismissToast();
        }, 5000);
    },
    methods: {
        dismissToast() {
            this.$store.commit('clearToast', this.toast.title);
        },
    },
}
</script>

<style lang="scss" scoped>
// https://derrickotte.medium.com/how-to-create-a-toasts-component-in-vue-3-with-vuex-1d8134541133
:root {
  --cream: #faf8f7;
  --light-grey: #e7e7e7;
  --grey: #cccccc;
  --white: #FFFFFF;
  --green: #009900;
  --red: #990000;
  --blue: #009999;
}

.success, .info, .error {
  color: #FFFFFF;
}

.success {
  background-color: #009900;
}

.info {
  background-color: #009999;
}

.error {
  background-color: #990000;
}
.toast-temp {
    width: 500px;
    min-height: 50px;
    display: flex;
    flex-direction: row;
    margin-bottom: 1rem;
    padding: 1rem;
    box-sizing: border-box;
    background-color: #faf8f7;
    justify-content: center;
    border-top-left-radius: 1rem;
    border-bottom-right-radius: 1rem;
    border-top-right-radius: .5rem;
    box-shadow: 0 0 .5rem #cccccc;
}
.toast-temp p {
    font-weight: 100;
}

.toast-temp .sidebar {
    width: .5rem;
}

.toast-temp .content, .toast .header, .toast .text {
    width: 100%;
    display: flex;
}

.toast-temp .content {
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding-left: 1rem;
    box-sizing: border-box;
}

.toast-temp .header, .toast-temp .text {
    flex-direction: row;
    justify-content: flex-start;
    text-align: left;
}

.toast-temp .header {
    height: 25px;
    margin-bottom: 1rem;
}

.toast-temp .header button {
    margin-left: auto;
    text-decoration: none;
    border: none;
    cursor: pointer;
    background: none;
    font-size: 1rem;
}

.toast-temp .header button:hover {
    background: #e7e7e7;
}

.toast-temp .content {
    flex-grow: 1;
}
</style>