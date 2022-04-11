<template>
    <div class="row">
        <div class="tabs col-2">
            <button 
                :class="activeTab('Groups')" 
                class="tab-item"
                @click="currentTabComponent = 'Groups'">
                {{ $t('groups') }}
            </button>
            <button 
                :class="activeTab('Friends')" 
                class="tab-item"
                @click="currentTabComponent = 'Friends'">
                {{ $t('friends') }}
            </button>
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
    methods: {
        activeTab(component) {
            if (component == this.currentTabComponent) return 'active-tab';
            return 'tab';
        },
    },
}
</script>
