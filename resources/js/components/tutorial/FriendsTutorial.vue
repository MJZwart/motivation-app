<template>
    <div>
        <b>{{ $t('friends') }}</b>
        <p>
            {{ $t('friends-tutorial') }}
        </p>
        <p>                            
            <FaIcon :icon="['far', 'rectangle-xmark']" class="icon small red" />
            {{ $t('friends-remove-tutorial') }}
        </p>
        <p>
            <FaIcon icon="envelope" class="icon small" />
            {{ $t('friends-message-tutorial') }}
        </p>
    </div>
</template>