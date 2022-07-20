<template>
    <Loading v-if="loading" />
    <div v-else>
        <template v-for="application in applications" :key="application.id">
            <p>
                {{`${application.username}, applied on: ${application.applied_at}`}}
                <button class="m-1" @click="rejectApplication(application)">
                    {{ $t('reject-group-application') }}
                </button>
                <button class="m-1" @click="acceptApplication(application)">
                    {{ $t('accept-group-application') }}
                </button>
            </p>
        </template>
    </div>
</template>

<script setup lang="ts">
import {useGroupStore} from '/js/store/groupStore';
import {ref, onMounted} from 'vue';
import {Application, Group} from 'resources/types/group';

const props = defineProps<{group: Group}>();

const groupStore = useGroupStore();

const loading = ref(true);

const applications = ref<Array<Application>>();
onMounted(() => {
    load();
});
async function load() {
    loading.value = true;
    applications.value = await groupStore.fetchApplications(props.group);
    loading.value = false;
}

async function rejectApplication(application: Application) {
    await groupStore.rejectApplication(application);
    load();
}

async function acceptApplication(application: Application) {
    await groupStore.acceptApplication(application);
    load();
}
</script>