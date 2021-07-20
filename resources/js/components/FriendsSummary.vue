<template>
    <div>
        <div class="friends">
            <span class="frame-title">Friends</span>
            <div class="side-border bottom-border">
                <ul class="summary-list">
                    <li v-for="(value, index) in user.friends" :key="index">
                        <b-icon-person-x-fill class="icon-small" @click="removeFriend(value)"></b-icon-person-x-fill> 
                        <!-- <b-icon-envelope class="icon-small"></b-icon-envelope>  -->
                        {{value.friend}}
                    </li>
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
        removeFriend(friend){
            console.log(friend);
            if(confirm("Areyou sure you wish to remove " + friend.friend + " as friend?")){
                this.$store.dispatch('friend/removeFriend', friend.id);
            }
        }
    },
    computed: {
        ...mapGetters({
            user: 'user/getUser',
        }),
    },
}
</script>


<style>
.summary-list{
    margin-top:0;
    list-style: none;
    padding-inline-start:0;
    font-size:14px;
}
</style>