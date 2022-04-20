<template>
    <div v-if="taskListToDelete">
        <form @submit.prevent="deleteTaskList">
            <p>{{ $t('are-you-sure-delete') }} {{taskListToDelete.name}}</p>
            <div v-if="taskListHasTasks" class="form-group">
                <p>
                    <!-- TODO fix the | -->
                    {{ $t('task-list-has-tasks', [taskListTasks.length]) }} 
                </p>
            
                <div class="form-group">
                    <select 
                        id="deleteOption" 
                        v-model="deleteOption">
                        <option value="delete" selected>{{ $t('delete') }}</option>
                        <option v-for="option in taskLists" :key="option.key" :value="option.id">
                            {{ $t('merge-with') }} {{option.name}}
                        </option>
                    </select>
                </div>
            </div>
            <button type="submit" class="block">{{ $t('delete-task-list-confirm') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
            <base-form-error name="error" /> 
        </form>
    </div>
</template>


<script setup>
import {onMounted, computed, ref} from 'vue';
import BaseFormError from '../BaseFormError.vue';
import {useTaskStore} from '@/store/taskStore';
const taskStore = useTaskStore();

const prop = defineProps({
    taskList: {
        /** @type {import('resources/types/task').TaskList} */
        type: Object,
        required: true,
    },
});
const emit = defineEmits(['close']);

onMounted(() => {
    taskListToDelete.value = prop.taskList;
});

/** @type {import('resources/types/task').TaskList} */
const taskListToDelete = ref();
const deleteOption = ref('delete');

const taskListHasTasks = computed(() => !!taskListToDelete.value && !!taskListToDelete.value.tasks[0]);
const taskListTasks = computed(() => taskListToDelete.value.tasks);
//Gets all the other existing taskLists, without the one the user is trying to delete
const taskLists = computed(() =>  taskStore.taskLists.filter(item => item != taskListToDelete.value));
/** If the user chooses to merge existing tasks into another tasklist, merge those first, then delete the list. */
async function deleteTaskList() {
    if (deleteOption.value != 'delete') {
        const data = {taskListId : deleteOption.value, tasks: taskListTasks.value};
        await taskStore.mergeTasks(data);
    }
    await taskStore.deleteTaskList(taskListToDelete.value);
    close();
}
function close() {
    taskListToDelete.value = null;
    emit('close');
}
</script>
