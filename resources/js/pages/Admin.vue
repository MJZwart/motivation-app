<template>
    <div>
        <h3>{{ $t('admin-panel') }}</h3>
        <div>
            <h4>{{ $t('send-notification-to-users') }}</h4>
            <b-form @submit.prevent="sendNotification">
                <b-form-group
                    :label="$t('title')" 
                    label-for="title">
                    <b-form-input 
                        id="title" 
                        v-model="notification.title" 
                        type="text" 
                        name="title" 
                        :placeholder="$t('title')" />
                </b-form-group>
                <b-form-group
                    :label="$t('text')" 
                    label-for="text">
                    <b-form-textarea 
                        id="text" 
                        v-model="notification.text" 
                        rows="5"
                        type="text" 
                        name="text"/>
                </b-form-group>

                <b-button type="submit" block>{{ $t('send-notification') }}</b-button>
            </b-form>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
export default {
    components: { },
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
    },
    data() {
        return {
            notification: {},
        }
    },
    computed: {
        ...mapGetters({
            
        }),
    },
    methods: {
        /** Sends notification to all members */
        sendNotification() {
            this.$store.dispatch('admin/sendNotification', this.notification);
        },
    },
    
}
</script>