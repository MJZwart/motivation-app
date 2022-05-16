<template>
    <Loading v-if="loading" />
    <div v-else>
        <template v-for="application in applications" :key="application.id">
            <p>
                {{`${application.username}, applied on: ${application.applied_at}`}}
                <button class="m-1" @click="rejectApplication(application)">
                    {{'reject'}}
                </button>
                <button class="m-1" @click="acceptApplication(application)">
                    {{'accept'}}
                </button>
            </p>
        </template>
    </div>
</template>

<script setup>
import {useGroupStore} from '/js/store/groupStore';
import {ref, onMounted, computed} from 'vue';
const groupStore = useGroupStore();

const emit = defineEmits(['reloadGroups']);
const loading = ref(true);
onMounted(() => {
    load();
});
async function load() {
    await groupStore.fetchApplications(props.group);
    loading.value = false;
}

const applications = computed(() => groupStore.applications);

const props = defineProps({
    group: {
        type: Object,
        required: true,
    },
});

async function rejectApplication(application) {
    await groupStore.rejectApplication(application);
    load();
}

async function acceptApplication(application) {
    await groupStore.acceptApplication(application);
    emit('reloadGroups');
    load();
}
</script>