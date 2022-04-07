<template>
    <div>
        <h5>{{ sendMessageTitle }}</h5>
        <form @submit.prevent="sendMessage">
            <div class="form-group">
                <label for="message">{{$t('message')}}</label>
                <textarea 
                    id="message" 
                    v-model="message.message"
                    name="message" 
                    rows=3
                    :placeholder="$t('send-message-placeholder')"  />
                <base-form-error name="message" /> 
            </div>
            <button type="submit" class="block">{{ $t('send-message') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>

<script>
import BaseFormError from '../BaseFormError.vue';

export default {
    components: {
        BaseFormError,
    },
    props: {
        user: {
            type: Object,
            required: true,
        },
        conversation: {
            type: Object,
            required: false,
        },
    },
    data() {
        return {
            message: {
                message: '',
            },
        }
    },

    methods: {
        close() {
            this.$emit('close');
        },
        sendMessage() {
            this.message.recipient_id = this.user.id;
            if (this.conversation) {
                this.message.conversation_id = this.conversation.conversation_id;
            }
            this.$store.dispatch('message/sendMessage', this.message).then(() => {
                this.$emit('close');
            });
        },
    },
    computed: {
        sendMessageTitle() {
            return 'Send message to ' + this.user.username;
        },
    },

}
</script>
