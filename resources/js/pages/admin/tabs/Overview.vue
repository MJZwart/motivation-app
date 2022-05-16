<template>
    <div class="overview-box">
        <div v-for="(item, key, index) in overview" :key="index" class="overview">
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
        background-color: white;
        box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.25);
        .key {
            font-weight: 600;
            font-size: 1rem;
            display: block;
        }
        .item {
            color: $primary;
            font-size: 2.5rem;
            display: block;
        }
        .details {
            font-weight: 300;
            color: $grey;
        }
    }
}
</style>