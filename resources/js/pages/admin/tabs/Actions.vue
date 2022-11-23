<template>
    <div>
        <h3>{{ $t('actions') }}</h3>
        {{$t('filter-by')}}:
        <div>
            <div class="form-group">
                {{$t('type')}}
                <Multiselect
                    v-model="activeFilters.type"
                    :options="actionTypes"
                    mode="tags"
                    valueProp="action_type"
                    label="action_type"
                    :placeholder="$t('select-action-type')"
                />
            </div>
            <div class="form-group">
                {{$t('users')}}
                <Multiselect
                    v-model="activeFilters.users"
                    :options="users"
                    searchable
                    label="username"
                    valueProp="id"
                    :placeholder="$t('select-users')"
                    mode="tags" />
            </div>
            <div class="form-group">
                {{$t('date-range')}}
                <Datepicker 
                    v-model="activeFilters.date" 
                    range autoApply :maxDate="new Date()" :locale="currentLang"
                />
            </div>
            <button @click="filterActions">Go</button>
        </div>
        <Table
            v-if="filteredActions[0]"
            :items="filteredActions"
            :fields="filteredActionsFields"
            class="actions-table table-striped table-hover"
            :itemsPerPage=25
            sort="timestamp"
            :sortAsc=false
        >
            <template #user="item">
                {{item.item.user ? item.item.user.username : $t('none')}}
            </template>
            <template #user_agent="item">
                {{parseUserAgent(item.item.user_agent)}}
            </template>
        </Table>
        <span v-else>{{$t('no-results')}}</span>

    </div>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';
import type {StrippedUser} from 'resources/types/user';
import type {ActionType, Actions, ActionFilters} from 'resources/types/admin';
import Multiselect from '@vueform/multiselect';
import Table from '/js/components/global/Table.vue';
import platform from 'platform';
import {currentLang} from '/js/services/languageService';
import {getYesterdayDate} from '/js/services/dateService';
import {filteredActionsFields} from '/js/constants/adminConstants';

const adminStore = useAdminStore();

const actionTypes = ref<ActionType[]>([]);
const users = ref<StrippedUser[]>([]);
const activeFilters = ref<ActionFilters>({
    type: [],
    users: [],
    date: [
        getYesterdayDate(), new Date(),
    ],
});
const filteredActions = ref<Actions[]>([]);

onMounted(async() => {
    const data = await adminStore.getActionFilters();
    actionTypes.value = data.types;
    users.value = data.users;
    filteredActions.value = await adminStore.getActionsWithFilters(activeFilters.value);
});

async function filterActions() {
    filteredActions.value = await adminStore.getActionsWithFilters(activeFilters.value);
}

function parseUserAgent(userAgent: string) {
    return platform.parse(userAgent);
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style lang="scss" scoped>
.actions-table {
    font-size: small;
}
</style>