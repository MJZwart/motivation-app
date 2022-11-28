<template>
    <div class="custom-toast">
        <div class="custom-toast-sidebar" :class="toastType" />
        <div class="custom-toast-content">
            <div class="custom-toast-header">
                <h4>{{toast.title || $t(toastType)}}</h4>
                <button @click="dismissToast">X</button>
            </div>
            <div class="text">
                <p v-for="(toastArr, index) in Object.entries(toast)" :key="index">
                    <!-- If the toast value in this case is not a string but an array or object, it will cause problems -->
                    <span v-if="toastArr[0] === 'coinsEarned'">
                        {{$t('coins-earned')}}: <Coins :coins="parseInt(toastArr[1])" />
                    </span>
                    <span v-else>{{toastArr[1]}}</span>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted} from 'vue';
import {Toast} from 'resources/types/toast';
import {clearToast} from '/js/services/toastService';
import Coins from '/js/pages/dashboard/components/reward/Coins.vue';

onMounted(() => {
    setTimeout(() => {
        dismissToast();
    }, 5000);
});

const props = defineProps<{toast: Toast}>();

function dismissToast() {
    clearToast();
}

const toastType = computed(() => {
    for (const item of Object.keys(props.toast)) {
        if (item == 'success' || item == 'error' || item == 'info') return item;
    }
    return 'info';
});
</script>

<style lang="scss" scoped>
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
    box-shadow: 0 0 .5rem var(--box-shade);

    p {
        font-family: var(--light-font);
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
        color: var(--primary);
        height: fit-content;
        padding-bottom: 3px;
        border-radius: 0.2rem;
    } 
}

@media (max-width:350px) {
.custom-toast {
    width: 200px;

    p {
        font-family: var(--light-font);
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