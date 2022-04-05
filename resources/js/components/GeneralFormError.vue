<template>
    <div>
        <div v-for="error in responseMessage" :key="error.index" class="d-block invalid-feedback">
            {{ error[0] }}
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
export default {
    computed: {
        ...mapGetters({
            responseMessage: 'getErrorMessages',
        }),
    },
}
</script>