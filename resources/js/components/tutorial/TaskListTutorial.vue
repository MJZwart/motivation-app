<template>
    <div>
        <b>{{ $t('manage-task-list') }}</b>
        <p>
            <FaIcon :icon="['far', 'pen-to-square']" class="small primary" />
            {{ $t('edit-task-tutorial') }}
        </p>
        <p>
            <FaIcon icon="trash" class="small red" />
            {{ $t('delete-task-tutorial') }}
        </p>
        <p>
            <FaIcon icon="square-plus" class="small green" />
            {{ $t('new-sub-task-tutorial') }}
        </p>
        <p>
            <FaIcon :icon="['far', 'square-check']" class="small green" />
            {{ $t('complete-task-tutorial') }}
        </p>
    </div>
</template>