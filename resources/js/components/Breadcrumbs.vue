<template>
    <div v-if="breadcrumbsVisible" class="breadcrumbs">
        <a @click="router.back()">{{$t('back')}}</a>
    </div>
</template>

<script setup lang="ts">
import {breadcrumbsVisible} from '/js/services/breadcrumbService';
import {useRouter} from 'vue-router';
const router = useRouter();

</script>

<style lang="scss" scoped>
.breadcrumbs {
    margin-left: 1rem;
    margin-top: 0.5rem;
}
</style>