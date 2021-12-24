<template v-if="errorMsg">
    <b-form-invalid-feedback :state="false">{{ errorMsg }}</b-form-invalid-feedback>
</template>

<script>
import {BFormInvalidFeedback} from 'bootstrap-vue';
import {mapGetters} from 'vuex';
export default {
    components: {BFormInvalidFeedback},
    props: {
        name: {type: String, required: true},
    },
    computed: {
        ...mapGetters({
            responseMessage: 'getErrorMessages',
        }),
        errorMsg() {
            const errors = this.responseMessage;
            if (!this.name || !errors) {
                return '';
            }
            return (errors[this.name] || [])[0] || '';
        },
    },
}
</script>