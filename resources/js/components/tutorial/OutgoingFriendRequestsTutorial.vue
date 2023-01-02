<template>
    <div>
        <b>{{ $t('outgoing-friend-requests') }}</b>
        <p>
            {{ $t('outgoing-friend-requests-tutorial') }}
        </p>
        <p>
            <Icon :icon="CROSS_SQUARE" class="icon red" />
            {{ $t('outgoing-friend-cancel-tutorial')}}
        </p>
    </div>
</template>

<script setup lang="ts">
import {CROSS_SQUARE} from '/js/constants/iconConstants';

</script>