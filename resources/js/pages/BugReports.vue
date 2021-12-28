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
                        v-model="bugReport.title" />
                </div>
                <div class="form-group">
                    <label for="page">Page</label>
                    <input 
                        type="text" 
                        id="page" 
                        name="page" 
                        placeholder="Page" 
                        v-model="bugReport.page" />
                </div>
                <div class="form-group">
                    <label for="image_link">Image Link</label>
                    <input 
                        type="text" 
                        id="image_link" 
                        name="image_link" 
                        placeholder="Image Link" 
                        v-model="bugReport.image_link" />
                </div>
                <div class="form-group">
                    <label for="comment">Comment</label>
                    <input 
                        type="text" 
                        id="comment" 
                        name="comment" 
                        placeholder="Comment" 
                        v-model="bugReport.comment" />
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
            bugReport: {
                title: '',
                page: '',
                image_link: '',
                comment: '',
            },
        }
    },
    methods: {
        submitBugReport(){
            this.$store.dispatch('bugReport/storeBugReport', this.bugReport);
        }
    },
    
}
</script>


<style>

</style>
