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
        <SubmitButton class="block" @click="submit">{{ $t('delete-task-list-confirm') }}</SubmitButton>
        <button type="button" class="block button-cancel" @click="$emit('close')">{{ $t('cancel') }}</button>
        <BaseFormError name="error" />
    </div>
</template>

<script setup lang="ts">
import {Task, TaskList} from 'resources/types/task';
import {onMounted, computed, ref} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import {deepCopy} from '/js/helpers/copy';
const taskStore = useTaskStore();

const props = defineProps<{form: TaskList}>();
const emit = defineEmits<{
    (event: 'close'): void,
    (event: 'submit', data: {option: string | number, tasks: Task[]}): void,
}>();
(['close', 'submit']);

const taskList = ref<TaskList>(deepCopy(props.form));

onMounted(async() => {
    allOtherLists.value = await taskStore.getOtherTaskLists(taskList.value.id);
});

const allOtherLists = ref<{name: string, id: number}[]>([]);

const deleteOption = ref<string | number>('delete');

const taskListHasTasks = computed(() => !!taskList.value && !!taskList.value.tasks[0]);
const taskListTasks = computed<Task[]>(() => (taskList.value ? taskList.value.tasks : []));

/** If the user chooses to merge existing tasks into another tasklist, merge those first, then delete the list. */
async function submit() {
    const data = {option: deleteOption.value, tasks: taskListTasks.value};
    emit('submit', data);
}
</script>
