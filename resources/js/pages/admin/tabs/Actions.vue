<template>
    <div>
        <h3>{{ $t('actions') }}</h3>
        {{$t('filter-by')}}:
        <div>
            <div class="form-group">
                {{$t('type')}}
                <Multiselect
                    v-model="activeFilters.types"
                    :options="possibleFilters.types"
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
                    :options="possibleFilters.users"
                    searchable
                    label="username"
                    valueProp="id"
                    :placeholder="$t('select-users')"
                    mode="tags" />
            </div>
            <div class="form-group">
                {{$t('date-range')}}
                <Datepicker 
                    v-model="activeFilters.minDate" 
                    range autoApply :locale="currentLang"
                    :minDate="possibleFilters.minDate" :maxDate="new Date()"
                />
            </div>
            <button @click="filterActions">{{$t('search')}}</button>
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
            <template #user="row">
                {{row.item.user ? row.item.user.username : $t('none')}}
            </template>
            <template #user_agent="row">
                {{parseUserAgent(row.item.user_agent)}}
            </template>
        </Table>
        <span v-else>{{$t('no-results')}}</span>

    </div>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue';
import type {Actions, ActionFilters} from 'resources/types/admin';
import Multiselect from '@vueform/multiselect';
import Table from '/js/components/global/Table.vue';
import {currentLang} from '/js/services/languageService';
import {getYesterdayDate} from '/js/services/dateService';
import {filteredActionsFields} from '/js/constants/adminConstants';
import {parseUserAgent} from '/js/services/platformService';
import axios from 'axios';

const possibleFilters = ref<ActionFilters>({
    types: [],
    users: [],
    minDate: [],
});
const activeFilters = ref<ActionFilters>({
    types: [],
    users: [],
    minDate: [
        getYesterdayDate(), new Date(),
    ],
});
const filteredActions = ref<Actions[]>([]);

onMounted(async() => {
    const {data} = await axios.get('/admin/action/filters');
    possibleFilters.value = data;
    filteredActions.value = await getActionsWithFilters(activeFilters.value);
});

async function filterActions() {
    filteredActions.value = await getActionsWithFilters(activeFilters.value);
}

async function getActionsWithFilters(filters: ActionFilters) {
    const {data} = await axios.post('/admin/action/filters', filters);
    return data.data;
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style lang="scss" scoped>
.actions-table {
    font-size: small;
}
</style>