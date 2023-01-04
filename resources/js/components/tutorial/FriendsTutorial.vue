<template>
    <div>
        <b>{{ $t('friends') }}</b>
        <p>
            {{ $t('friends-tutorial') }}
        </p>
        <p>                            
            <Icon :icon="CROSS_SQUARE" class="cross-square-icon red" />
            {{ $t('friends-remove-tutorial') }}
        </p>
        <p>
            <Icon :icon="MAIL" class="mail-icon primary" />
            {{ $t('friends-message-tutorial') }}
        </p>
    </div>
</template>

<script setup lang="ts">
import {CROSS_SQUARE, MAIL} from '/js/constants/iconConstants';

</script>

<style scoped>
.primary {
    color: var(--primary);
}
</style>