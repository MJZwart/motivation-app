<template>
    <div>
        <Loading v-if="loading || group == null" />
        <div v-else class="w-60-flex center">
            <h2>{{group.name}}</h2>
            <div class="group-page">
                <div class="tabs tabs-horizontal mb-1">    
                    <template v-for="(tab, index) in tabs" :key="index">
                        <button v-if="tab" :class="isActive(tab)" class="tab-item" @click="selectTab(tab)">
                            {{ $t(tab) }}
                        </button>
                    </template>
                </div>
                <div class="group-page-content">
                    <div v-if="currentTab === 'public'">
                        <PublicGroupInformation :group="group" />
                        <JoinGroupActions v-if="!group.rank" :group="group" />
                        <MemberGroupPageData v-if="group.rank" :group="group" />
                    </div>
                    <MemberList v-if="currentTab === 'members'" :group="group" />
                    <AdminActions v-if="currentTab === 'admin'" :group="group" />
                    <GroupMessages v-if="currentTab === 'messages'" :group="group" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onBeforeMount, ref, computed} from 'vue';
import {useGroupStore} from '/js/store/groupStore';
import {useRoute} from 'vue-router';
import {GroupPage} from 'resources/types/group';
import JoinGroupActions from './page-components/JoinGroupActions.vue';
import MemberGroupPageData from './page-components/MemberGroupPageData.vue';
import PublicGroupInformation from './page-components/PublicGroupInformation.vue';
import MemberList from './page-components/MemberList.vue';
import AdminActions from './page-components/AdminActions.vue';
import GroupMessages from './page-components/GroupMessages.vue';

const groupStore = useGroupStore();
const route = useRoute();

onBeforeMount(async() => {
    await groupStore.fetchGroup(parseInt(String(route.params.id)));
    loading.value = false;
});

const loading = ref(true);
const group = computed((): GroupPage | null => groupStore.group);

/** 
 * Tabs 
 */
const tabs = computed(() => {
    let computedTabs = ['public'];
    if (group.value?.rank) {
        computedTabs.push('members');
        computedTabs.push('messages');
    }
    if (canSeeAdminTab()) computedTabs.push('admin');
    return computedTabs;
});
function canSeeAdminTab() {
    const rank = group.value?.rank;
    if (!rank) return;
    return rank.can_delete || rank.can_edit || rank.can_manage_members;
}
const currentTab = ref('public');
function isActive(tabName: string) {
    return tabName === currentTab.value ? 'active-tab':'tab';
}
function selectTab(tabName: string) {
    currentTab.value = tabName;
}
</script>

<style lang="scss">
.group-page {
    display: flex;
    flex-direction: column;
    flex-wrap: nowrap;
    .group-page-content {
        width: 100%;
    }
    .content-block {
        padding: 0.8rem;
    }
}
</style>