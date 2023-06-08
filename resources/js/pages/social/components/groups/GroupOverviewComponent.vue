<template>
    <div class="d-flex content-block striped">
        <div class="left-side">
            <h5>{{group.name}}</h5>
            <p>{{ group.description }}</p>
        </div>
        <div class="right-side">
            <template v-if="group.is_member">
                <p>
                    <b>{{$t('rank')}}: </b>
                    <GroupRankIcon v-if="group.rank" :rank="group.rank" />
                    {{group.rank?.name}}
                </p>
                <p>
                    <b>{{$t('member-since')}}:</b> {{ group.joined ? parseDateTime(group.joined) : '' }}
                </p>
            </template>
            <template v-else>
                <p>
                    <b>{{group.is_public ? $t('public') : $t('private')}}</b>
                </p>
                <p>
                    <b>{{group.require_application ? $t('requires-application') : $t('free-to-join')}}</b>
                </p>
            </template>
            <p>
                <b>{{$t('members')}}:</b> {{group.members}}
            </p>
        </div>
        <div class="details-side">
            <Tooltip :text="$t('view')">
                <Icon :icon="DETAILS" class="details-icon" @click="showGroupsDetails()" />
            </Tooltip>
        </div>
    </div>
</template>

<script setup lang="ts">
import type {Group} from 'resources/types/group';
import GroupRankIcon from './page-components/GroupRankIcon.vue';
import {parseDateTime} from '/js/services/dateService';
import {DETAILS} from '/js/constants/iconConstants';
import {useRouter} from 'vue-router';

const props = defineProps<{group: Group}>();

const router = useRouter();

function showGroupsDetails() {
    router.push({path: `/group/${props.group.id}`});
}
</script>

<style lang="scss">
.left-side {
    width: 60%;
}
.right-side {
    width: 30%;
}
.details-side {
    width: 10%;
}
.left-side p, .right-side p{
    margin:0.35rem;
}
.striped.content-block:nth-of-type(2n+1) {
    background-color: var(--nth-of-type);
}
.content-block.clickable:hover {
    background-color: var(--hover);
}
</style>