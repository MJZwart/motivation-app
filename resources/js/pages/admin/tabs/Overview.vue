<template>
    <div v-if="overview" class="overview-box">
        <div v-for="(item, key, index) in overview" :key="index" class="overview content-block">
            <span class="key">{{$t(key)}}</span>
            <span v-if="(typeof item === 'number')">
                <span class="item">{{item}}</span>
            </span>
            <span v-else>
                <span class="item">{{ item.total }}</span>
                <span class="details">{{ $t('guests') }}: {{ item['guests'] }} ({{ percentageOfGuests(item) }})</span>
            </span>
            <span class="details">{{ $t(`${key}-details`) }}</span>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';
import type {Overview} from 'resources/types/admin';
const adminStore = useAdminStore();

onMounted(async() => {
    overview.value = await adminStore.getOverview();
    loading.value = false;
});

const loading = ref(true);
const overview = ref<Overview | null>(null);

function percentageOfGuests(item: {total: number, guests: number}) {
    return Math.round((item.guests / item.total) * 100) + '%';
}
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
            display:block;
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