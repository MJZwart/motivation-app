<template>
    <Loading v-if="loading" />
    <div v-else>
        <template v-for="application in applications" :key="application.id">
            <p>
                {{`${application.username}, applied on: ${application.applied_at}`}}
                <button class="m-1" @click="acceptApplication(application)">
                    {{ $t('accept-group-application') }}
                </button>
                <button class="m-1" @click="rejectApplication(application)">
                    {{ $t('reject-group-application') }}
                </button>
                <button class="m-1" @click="banApplication(application)">
                    {{ $t('ban-group-application') }}
                </button>
            </p>
        </template>
    </div>
</template>

<script setup lang="ts">
import {useGroupStore} from '/js/store/groupStore';
import {ref, onMounted, PropType} from 'vue';
import {Group, Application} from 'resources/types/group'
const groupStore = useGroupStore();

const loading = ref(true);

const applications = ref<Array<Application> | null>(null);

onMounted(() => {
    load();
});
async function load() {
    loading.value = true;
    applications.value = await groupStore.fetchApplications(props.group);
    loading.value = false;
}

const props = defineProps({
    group: {
        type: Object as PropType<Group>,
        required: true,
    },
});

async function rejectApplication(application: Application) {
    await groupStore.rejectApplication(application);
    load();
}

async function acceptApplication(application: Application) {
    await groupStore.acceptApplication(application);
    load();
}

async function banApplication(application: Application) {
    await groupStore.banApplication(application);
    load();
}
</script>