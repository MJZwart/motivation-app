<template>
    <div class="row">
        <div class="card col-2">
            <button @click="currentTabComponent = 'Groups'">{{ $t('groups') }}</button>
            <button @click="currentTabComponent = 'Friends'">{{ $t('friends') }}</button>
        </div>
        <keep-alive class="col-10">
            <component :is="currentTabComponent" />
        </keep-alive>
    </div>
</template>

<script>
import Groups from '../components/tabs/social/Groups.vue';
import Friends from '../components/tabs/social/Friends.vue';

export default {
    components: {
        Groups, Friends, 
    },
    data() {
        return {
            currentTabComponent: 'Groups',
        }
    },
}
</script>
