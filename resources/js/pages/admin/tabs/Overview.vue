<template>
    <div class="overview-box">
        <div v-for="(item, key, index) in overview" :key="index" class="overview content-block">
            <span class="key">{{$t(key)}}</span>
            <span class="item">{{item}}</span>
            <span class="details">{{$t(`${key}-details`)}}</span>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();

onMounted(async() => {
    overview.value = await adminStore.getOverview();
    loading.value = false;
});

const loading = ref(true);
const overview = ref({});
</script>

<style lang="scss" scoped>
@import '../../../../assets/scss/variables';
.overview-box {
    display: flex;
    gap: 0.5rem;
    flex-flow: wrap;
    padding: 0.5rem;
    .overview {
        padding: 1rem;
        width: 15rem;
        height: 10rem;
        background-color: var(--background-2);
        box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.25);
        .key {
            font-family: var(--border-color);
            font-size: 1rem;
            display: block;
        }
        .item {
            color: var(--primary)-as-text;
            font-size: 2.5rem;
            display: block;
        }
        .details {
            font-family: var(--light-font);
            color: var(--grey);
        }
    }
}
@media (max-width: 650px) {
    .overview-box .overview {
        width: 14rem;
        height: 9rem;
    }
}
@media (max-width: 600px) {
    .overview-box .overview {
        width: 12rem;
        height: 9rem;
    }
}
@media (max-width: 550px) {
    .overview-box .overview {
        width: 10rem;
        height: 9rem;
    }
}
@media (max-width: 450px) {
    .overview-box .overview {
        width: 8rem;
        height: 8rem;
        .key {
            font-size: 0.8rem;
        }
        .item {
            font-size: 2rem;
        }
        .details {
            font-size: small;
        }
    }
}
</style>