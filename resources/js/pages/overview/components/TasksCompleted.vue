<template>
    <div class="form-group">
        {{$t('date-range')}}
        <Datepicker 
            v-model="dateRange" 
            range autoApply :locale="currentLang"
            :maxDate="new Date()"
        />
    </div>
    {{ tasksCompleted }}
</template>

<script setup lang="ts">
import {computed, onMounted, ref, watchEffect} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import {currentLang} from '/js/services/languageService';
import {useUserStore} from '/js/store/userStore';
const taskStore = useTaskStore();
const userStore = useUserStore();

const tasksCompleted = ref();
const user = computed(() => userStore.user);
const dateRange = ref([parseDateString(user.value?.joined), parseDateString(new Date())]);

onMounted(async() => {
    tasksCompleted.value = await taskStore.getTaskCompletions();
});

watchEffect(() => {
    if (user.value)
        dateRange.value = [parseDateString(user.value.joined), parseDateString(new Date())];
})

function parseDateString(date: Date | string | undefined) {
    if (!date) return '';
    let dateToParse = date;
    if (typeof dateToParse !== 'string') {
        dateToParse = dateToParse.toISOString();
    }
    return dateToParse.slice(0, 10)
}
</script>