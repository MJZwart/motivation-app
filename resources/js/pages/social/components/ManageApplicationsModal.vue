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

<script setup>
import {useGroupStore} from '/js/store/groupStore';
import {ref, onMounted} from 'vue';
const groupStore = useGroupStore();

const emit = defineEmits(['reloadGroups']);
const loading = ref();

/** @type {import('resources/types/group').Application} */
const applications = ref();
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
        type: Object,
        required: true,
    },
});

/** @param {import('resources/types/group').Application} application */
async function rejectApplication(application) {
    await groupStore.rejectApplication(application);
    load();
}

/** @param {import('resources/types/group').Application} application */
async function acceptApplication(application) {
    await groupStore.acceptApplication(application);
    emit('reloadGroups');
    load();
}

/** @param {import('resources/types/group').Application} application */
async function banApplication(application) {
    await groupStore.banApplication(application);
    emit('reloadGroups');
    load();
}
</script>