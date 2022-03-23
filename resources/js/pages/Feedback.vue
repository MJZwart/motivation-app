<template>
    <div class="w-50 center">
        <h2>{{ $t('feedback') }}</h2>
        <p>{{ $t('feedback-explanation') }}</p>
        <b>{{ $t('feedback-features-header') }}</b>
        <p>{{ $t('feedback-features') }}</p>
        <b>{{ $t('feedback-accessibility-header') }}</b>
        <p>{{ $t('feedback-accessibility') }}</p>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
export default {

    computed: {
        ...mapGetters({
            conversations: 'message/getConversations',
        }),
    },
}
</script>