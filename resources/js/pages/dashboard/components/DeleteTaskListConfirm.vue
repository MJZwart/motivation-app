<template>
    <div>
        <p>{{ $t('are-you-sure-delete', [taskList.name]) }}</p>
        <div v-if="taskListHasTasks" class="form-group">
            <p>
                {{ $t('task-list-has-tasks', [taskListTasks.length]) }}
            </p>

            <div class="form-group">
                <select id="deleteOption" v-model="deleteOption">
                    <option value="delete" selected>{{ $t('delete') }}</option>
                    <option v-for="option in allOtherLists" :key="option.id" :value="option.id">
                        {{ $t('merge-with') }} {{ option.name }}
                    </option>
                </select>
            </div>
        </div>        
        <FormControls
            :submit-text="$t('delete-task-list-confirm')"
            @submit="submit"
            @cancel="$emit('close')"
        />
        <BaseFormError name="error" />
    </div>
</template>

<script setup lang="ts">
import type {Task, TaskList} from 'resources/types/task';
import {onMounted, computed, ref} from 'vue';
import {deepCopy} from '/js/helpers/copy';
import FormControls from '/js/components/global/FormControls.vue';
import axios from 'axios';
import { tasks } from '/js/services/taskService';

const props = defineProps<{form: TaskList}>();
const emit = defineEmits<{
    (event: 'close'): void,
    (event: 'submit', option: string | number): void,
}>();
(['close', 'submit']);

const taskList = ref<TaskList>(deepCopy(props.form));

onMounted(async() => {
    const {data} = await axios.get(`/tasklists/other/${taskList.value.id}`);
    allOtherLists.value = data;
});

const allOtherLists = ref<{name: string, id: number}[]>([]);

const deleteOption = ref<string | number>('delete');

const taskListHasTasks = computed(() => tasks.value.some(task => task.task_list_id === taskList.value.id));
const taskListTasks = computed<Task[]>(() => tasks.value.filter(task => task.task_list_id === taskList.value.id));

async function submit() {
    emit('submit', deleteOption.value);
}
</script>
