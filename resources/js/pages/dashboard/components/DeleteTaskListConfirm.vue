<template>
    <div v-if="taskListToDelete">
        <form @submit.prevent="deleteTaskList">
            <p>{{ $t('are-you-sure-delete', [taskListToDelete.name]) }}</p>
            <div v-if="taskListHasTasks" class="form-group">
                <p>
                    <!-- TODO fix the | -->
                    {{ $t('task-list-has-tasks', [taskListTasks.length]) }}
                </p>

                <div class="form-group">
                    <select id="deleteOption" v-model="deleteOption">
                        <option value="delete" selected>{{ $t('delete') }}</option>
                        <option v-for="option in taskLists" :key="option.id" :value="option.id">
                            {{ $t('merge-with') }} {{ option.name }}
                        </option>
                    </select>
                </div>
            </div>
            <SubmitButton class="block">{{ $t('delete-task-list-confirm') }}</SubmitButton>
            <button type="button" class="block button-cancel" @click="close">{{ $t('cancel') }}</button>
            <BaseFormError name="error" />
        </form>
    </div>
</template>

<script setup lang="ts">
import {Task, TaskList} from 'resources/types/task';
import {onMounted, computed, ref} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
const taskStore = useTaskStore();

const prop = defineProps<{taskList: TaskList}>();
const emit = defineEmits(['close']);

onMounted(() => {
    taskListToDelete.value = prop.taskList;
});

const taskListToDelete = ref<TaskList | null>(null);
const deleteOption = ref<string | number>('delete');

const taskListHasTasks = computed(() => !!taskListToDelete.value && !!taskListToDelete.value.tasks[0]);
const taskListTasks = computed<Task[]>(() => (taskListToDelete.value ? taskListToDelete.value.tasks : []));

//Gets all the other existing taskLists, without the one the user is trying to delete
const taskLists = computed(() => taskStore.taskLists.filter(item => item != taskListToDelete.value));
/** If the user chooses to merge existing tasks into another tasklist, merge those first, then delete the list. */
async function deleteTaskList() {
    if (!taskListToDelete.value) return;
    if (deleteOption.value != 'delete') {
        const data = {taskListId: deleteOption.value, tasks: taskListTasks.value};
        await taskStore.mergeTasks(data);
    }
    await taskStore.deleteTaskList(taskListToDelete.value.id);
    close();
}
function close() {
    taskListToDelete.value = null;
    emit('close');
}
</script>
