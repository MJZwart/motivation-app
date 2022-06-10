<template>
    <div>
        <b>{{ $t('outgoing-friend-requests') }}</b>
        <p>
            {{ $t('outgoing-friend-requests-tutorial') }}
        </p>
        <p>
            <FaIcon :icon="['far', 'rectangle-xmark']" class="icon small red" />
            {{ $t('outgoing-friend-cancel-tutorial')}}
        </p>
    </div>
</template>