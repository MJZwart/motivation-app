<template>
    <div v-if="editedTask">
        <b-form @submit.prevent="updateTask">
            <b-form-group
                :label="$t('task-name')" 
                label-for="name">
                <b-form-input 
                    id="name" 
                    v-model="editedTask.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </b-form-group>
            <b-form-group
                :label="$t('description-optional')" 
                label-for="description">
                <b-form-input 
                    id="description" 
                    v-model="editedTask.description"
                    type="text" 
                    name="description" 
                    :placeholder="$t('description')"  />
            </b-form-group>
            <b-form-group
                :label="$t('type')" 
                label-for="type">
                <b-form-select
                    id="type"
                    v-model="editedTask.type"
                    name="type"
                    :options="taskTypes" />
                <base-form-error name="type" /> 
            </b-form-group>
            <b-form-group
                :label="'Difficulty: '+editedTask.difficulty+'/5'" 
                label-for="difficulty">
                <b-form-input 
                    id="difficulty"
                    v-model="editedTask.difficulty"
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
                    v-model="editedTask.repeatable"
                    name="repeatable"
                    :options="repeatables" />
                <base-form-error name="repeatable" /> 
            </b-form-group>
            <b-form-group>
                <p v-if="editedTask.taskList">{{ $t('task-list') }}: {{editedTask.taskList}}</p>
                <p v-if="editedTask.superTask">{{ $t('subtask-of') }}: {{editedTask.superTask}}</p>
            </b-form-group>
            <b-button type="submit" block>{{ $t('edit-task') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
        </b-form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
import {TASK_TYPES, REPEATABLES} from '../../constants/taskConstants';

export default {
    components: {BaseFormError},
    props: {
        task: Object,
    },
    data() {
        return {
            editedTask: {},
            taskTypes: TASK_TYPES,
            repeatables: REPEATABLES,
        }
    },
    mounted() {
        this.task ? this.editedTask = this.task : this.editedTask = {};
    },
    methods: {
        updateTask() {
            this.$store.dispatch('task/updateTask', this.editedTask).then(() => {
                this.close();
            });
        },
        close() {
            this.$emit('close');
        },
    },
}
</script>
