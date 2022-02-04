<template>
    <div v-if="user">
        <b-form @submit.prevent="sendMessage">
            <b-form-group
                label="Message" 
                label-for="message">
                <b-form-input 
                    id="message" 
                    v-model="message"
                    type="text" 
                    name="message" 
                    placeholder="Type your message"  />
                <base-form-error name="message" /> 
            </b-form-group>
            <b-button type="submit" block>Send message</b-button>
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
    },
    data() {
        return {
            message: '',
        }
    },

    methods: {
        close() {
            console.log('cancel');
        },
        sendMessage() {
            console.log('send');
        },
    },

}
</script>
