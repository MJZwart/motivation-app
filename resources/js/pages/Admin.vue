<template>
    <div>
        <h3>Admin panel</h3>
        <div>
            <h4>Send notification to all users</h4>
            <form @submit.prevent="sendNotification">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        placeholder="Title" 
                        v-model="notification.title" />
                </div>
                <div class="form-group">
                    <label for="text">Text</label>
                    <textarea 
                        rows="5"
                        type="text" 
                        id="text" 
                        name="text"
                        v-model="notification.text"></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="long-button">Send notification</button>
                </div>
            </form>
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


<style>

</style>