<template>
    <div>
        <b-form-invalid-feedback v-for="error in responseMessage" :key="error.index" :state="false">
            {{ error[0] }}
        </b-form-invalid-feedback>
    </div>
</template>

<script>
import {BFormInvalidFeedback} from 'bootstrap-vue';
import {mapGetters} from 'vuex';
export default {
    components: {BFormInvalidFeedback},
    computed: {
        ...mapGetters({
            responseMessage: 'getErrorMessages',
        }),
    },
}
</script>