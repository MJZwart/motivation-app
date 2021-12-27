#TODO: create type selection
#TODO: create severity selection
#TODO: store dispatch


<template>
    <div>
        <h2>Submit a bug report.</h2>

        <div class="bugreport-form">
            <form @submit.prevent="submitBugReport">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input 
                        type="text" 
                        id="title" 
                        name="title" 
                        placeholder="Title" 
                        v-model="bugreports.title" />
                </div>
                <div class="form-group">
                    <label for="page">Page</label>
                    <input 
                        type="text" 
                        id="page" 
                        name="page" 
                        placeholder="Page" 
                        v-model="bugreports.page" />
                </div>
                <div class="form-group">
                    <label for="image_link">Image Link</label>
                    <input 
                        type="text" 
                        id="image_link" 
                        name="image_link" 
                        placeholder="Image Link" 
                        v-model="bugreports.image_link" />
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <input 
                        type="text" 
                        id="comment" 
                        name="comment" 
                        placeholder="Comment" 
                        v-model="bugreports.comment" />
                </div>
                <div class="form-group">
                    <button type="submit" class="long-button">submit bug report</button>
                </div>
            </form> 
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            bugreport: {
                title: '',
                page: '',
                image_link: '',
                comment: '',
            },
        }
    },
    methods: {
        submitBugReport(){
            this.$store.dispatch('user/register', this.bugreport);
        }
    },
    
}
</script>


<style>
    .register-form{
        width:40%;
        margin: auto auto;
    }
</style>
