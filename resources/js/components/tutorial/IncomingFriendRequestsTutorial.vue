<template>
    <div>
        <b>{{ $t('incoming-friend-requests') }}</b>
        <p>
            {{ $t('incoming-friend-requests-tutorial') }}
        </p>
        <p>
            <Icon :icon="CHECK_SQUARE" class="check-square-icon green" />
            {{ $t('incoming-friend-accept-tutorial')}}
        </p>
        <p>
            <Icon :icon="CROSS_SQUARE" class="cross-square-icon red" />
            {{ $t('incoming-friend-deny-tutorial')}}
        </p>
    </div>
</template>

<script setup lang="ts">
import {CHECK_SQUARE, CROSS_SQUARE} from '/js/constants/iconConstants';

</script>