<template>
    <div>
        <h3>Admin panel</h3>
        <div>
            <h4>Send notification to all users</h4>
            <b-form @submit.prevent="sendNotification">
                <b-form-group
                    label="Title" 
                    label-for="title">
                    <b-form-input 
                        type="text" 
                        id="title" 
                        name="title" 
                        :placeholder="$t('title')" 
                        v-model="notification.title" />
                </b-form-group>
                <b-form-group
                    label="Text" 
                    label-for="text">
                    <b-form-textarea 
                        rows="5"
                        type="text" 
                        id="text" 
                        name="text"
                        v-model="notification.text"></b-form-textarea>
                </b-form-group>

                <b-button type="submit" block>Send notification</b-button>
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
        sendNotification(){
            this.$store.dispatch('admin/sendNotification', this.notification);
        },
    },
    
}
</script>