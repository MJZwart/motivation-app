<template v-if="errorMsg">
    <div class="d-block invalid-feedback">{{ errorMsg }}</div>
</template>

<script>
import {mapGetters} from 'vuex';
export default {
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