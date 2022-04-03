<template>
    <div>
        <h5>{{ sendMessageTitle }}</h5>
        <b-form @submit.prevent="sendMessage">
            <div class="form-group">
                <label for="message">{{$t('message')}}</label>
                <b-form-textarea 
                    id="message" 
                    v-model="message.message"
                    name="message" 
                    rows=3
                    :placeholder="$t('send-message-placeholder')"  />
                <base-form-error name="message" /> 
            </div>
            <b-button type="submit" block>{{ $t('send-message') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
        </b-form>
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
