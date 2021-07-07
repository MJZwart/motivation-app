<template>
    <div>
        <div class="friends">
            <span class="frame-title">Friends</span>
            <div class="side-border bottom-border">
                <ul>
                    <li v-for="(value, index) in user.friends" :key="index">{{value.friend}}</li>
                </ul>
            </div>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
export default {
    mounted(){
    },
    methods: {
    },
    computed: {
        ...mapGetters({
            user: 'getUser',
        }),
    },
}
</script>


<style>

</style>