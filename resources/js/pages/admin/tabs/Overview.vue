<template>
    <div class="overview-box">
        <div v-for="(item, key, index) in overview" :key="index" class="overview">
            {{$t(key)}}: {{item}}
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
    }
}
</style>