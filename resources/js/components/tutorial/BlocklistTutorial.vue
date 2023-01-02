<template>
    <div>
        <b>{{ $t('blocklist') }}</b>
        <p>
            {{ $t('blocklist-tutorial') }}
        </p>
        <p>
            <Icon :icon="UNLOCK" class="icon green" />
            {{ $t('blocklist-unblock-tutorial') }}
        </p>
    </div>
</template>

<script setup lang="ts">
import {UNLOCK} from '/js/constants/iconConstants';
</script>