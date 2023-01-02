<template>
    <div>
        <b>{{ $t('manage-task-list') }}</b>
        <p>
            <Icon :icon="EDIT" class="primary" />
            {{ $t('edit-task-tutorial') }}
        </p>
        <p>
            <Icon :icon="TRASH" class="red" />
            {{ $t('delete-task-tutorial') }}
        </p>
        <p>
            <Icon :icon="CREATE" class="green" />
            {{ $t('new-sub-task-tutorial') }}
        </p>
        <p>
            <Icon :icon="CHECK_SQUARE" class="green" />
            {{ $t('complete-task-tutorial') }}
        </p>
    </div>
</template>

<script setup lang="ts">
import {CREATE, CHECK_SQUARE, TRASH, EDIT} from '/js/constants/iconConstants';

</script>

<style scoped>
.primary {
    color: var(--primary);
}
</style>