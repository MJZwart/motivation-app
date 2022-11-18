<template>
    <Loading v-if="loading" />
    <div v-else>
        <template v-for="application in applications" :key="application.id">
            <p>
                {{`${application.username}, applied on: ${application.applied_at}`}}
                <button class="m-1" @click="acceptApplication(application.id)">
                    {{ $t('accept-group-application') }}
                </button>
                <button class="m-1" @click="rejectApplication(application.id)">
                    {{ $t('reject-group-application') }}
                </button>
                <button class="m-1" @click="suspendApplication(application.id)">
                    {{ $t('suspend-group-application') }}
                </button>
            </p>
        </template>
        <div v-if="applications && applications.length < 1">
            {{$t('no-applications')}}
        </div>
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
    applications.value = await groupStore.fetchApplications(props.group.id);
    loading.value = false;
}

const props = defineProps({
    group: {
        type: Object as PropType<Group>,
        required: true,
    },
});

async function rejectApplication(applicationId: number) {
    await groupStore.rejectApplication(applicationId);
    load();
}

async function acceptApplication(applicationId: number) {
    await groupStore.acceptApplication(applicationId);
    load();
}

async function suspendApplication(applicationId: number) {
    await groupStore.suspendApplication(applicationId);
    load();
}
</script>