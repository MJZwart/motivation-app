<template>
    <div>
        <b-form @submit.prevent="submitTask">
            <b-form-group 
                :label="$t('task-name')" 
                label-for="name">
                <b-form-input 
                    id="name" 
                    v-model="task.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')" />
                <base-form-error name="name" /> 
            </b-form-group >
            <b-form-group
                :label="$t('description-optional')" 
                label-for="description">
                <b-form-input  
                    id="description" 
                    v-model="task.description"
                    type="text" 
                    name="description" 
                    :placeholder="$t('description')"  />
            </b-form-group>
            <b-form-group
                :label="$t('type')" 
                label-for="type">
                <b-form-select
                    id="type"
                    v-model="task.type"
                    name="type"
                    :options="taskTypes" />
                <base-form-error name="type" /> 
            </b-form-group>
            <b-form-group
                :label="$t('difficulty')+': '+task.difficulty+'/5'"
                label-for="difficulty">
                <b-form-input 
                    id="difficulty"
                    v-model="task.difficulty"
                    type="range"
                    name="difficulty"
                    min="1"
                    max="5"
                    value="3" />
                <base-form-error name="difficulty" /> 
            </b-form-group>
            <b-form-group
                :label="$t('repeatable')" 
                label-for="repeatable">
                <b-form-select
                    id="repeatable"
                    v-model="task.repeatable"
                    name="repeatable"
                    :options="repeatables" />
                <base-form-error name="repeatable" /> 
            </b-form-group>
            <b-form-group>
                <p v-if="taskList">{{ $t('task-list') }}: {{taskList.name}}</p>
                <p v-if="superTask">{{ $t('subtask-of') }}: {{superTask.name}}</p>
            </b-form-group>
            <b-button type="submit" block>{{ $t('create-new-task') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
        </b-form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
import {TASK_TYPES, REPEATABLES} from '../../constants/taskConstants';

export default {
    components: {
        BaseFormError,
    },
    props: {
        taskList: {
            /** @type {import('../../../types/task').TaskList} */
            type: Object,
            required: true,
        },
        superTask: {
            /** @type {import('../../../types/task').Task} */
            type: Object,
            required: false,
        },
    },
    data() {
        return {
            /** @type {import('../../../types/task').Task} */
            task: {
                difficulty: 3,
                type: 1,
                repeatable: 'NONE',
            },
            taskTypes: TASK_TYPES,
            repeatables: REPEATABLES,
        }
    },
    methods: {
        submitTask() {
            this.task.super_task_id = this.superTask ? this.superTask.id : null;
            this.task.task_list_id = this.taskList.id || null;
            var self = this;
            this.$store.dispatch('task/storeTask', this.task).then(function() {
                self.close();
            });
        },
        close() {
            this.$emit('close');
        },
    },
}
</script>
