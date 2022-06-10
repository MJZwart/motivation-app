<template>
    <div>
        <b>{{ $t('incoming-friend-requests') }}</b>
        <p>
            {{ $t('incoming-friend-requests-tutorial') }}
        </p>
        <p>
            <FaIcon :icon="['far', 'square-check']" class="icon small green" />
            {{ $t('incoming-friend-accept-tutorial')}}
        </p>
        <p>
            <FaIcon :icon="['far', 'rectangle-xmark']" class="icon small red" />
            {{ $t('incoming-friend-deny-tutorial')}}
        </p>
    </div>
</template>