<template>
    <div>
        <div class="friends">
            <span class="frame-title">Friends</span>
            <div class="side-border bottom-border">
                <ul class="friends-list">
                    <li v-for="(value, index) in user.friends" :key="index">
                        <b-icon-person-x-fill class="icon-small"></b-icon-person-x-fill> 
                        <b-icon-envelope class="icon-small"></b-icon-envelope> 
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
    },
    computed: {
        ...mapGetters({
            user: 'getUser',
        }),
    },
}
</script>


<style>
.friends-list{
    margin-top:0;
    list-style: none;
    padding-inline-start:0;
    font-size:14px;
}
</style>