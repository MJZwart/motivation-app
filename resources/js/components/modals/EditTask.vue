<template>
    <div v-if="editedTask">
        <b-form @submit.prevent="updateTask">
            <b-form-group
                :label="$t('task-name')" 
                label-for="name">
                <b-form-input 
                    type="text" 
                    id="name" 
                    name="name" 
                    :placeholder="$t('name')" 
                    v-model="editedTask.name" />
                <base-form-error name="name" /> 
            </b-form-group>
            <b-form-group
                :label="$t('description-optional')" 
                label-for="description">
                <b-form-input 
                    type="text" 
                    id="description" 
                    name="description" 
                    :placeholder="$t('description')" 
                    v-model="editedTask.description" />
            </b-form-group>
            <b-form-group
                :label="$t('type')" 
                label-for="type">
                <b-form-select
                    name="type"
                    id="type"
                    :options="taskTypes"
                    v-model="editedTask.type" />
                <base-form-error name="type" /> 
            </b-form-group>
            <b-form-group
                :label="'Difficulty: '+editedTask.difficulty+'/5'" 
                label-for="difficulty">
                <b-form-input 
                    type="range"
                    name="difficulty"
                    id="difficulty"
                    min="1"
                    max="5"
                    value="3"
                    v-model="editedTask.difficulty" />
                <base-form-error name="difficulty" /> 
            </b-form-group>
            <b-form-group
                :label="$t('repeatable')" 
                label-for="repeatable">
                <b-form-select
                    name="repeatable"
                    id="repeatable"
                    :options="repeatables"
                    v-model="editedTask.repeatable" />
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
import { TASK_TYPES, REPEATABLES } from '../../constants/taskConstants';

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
    mounted(){
        this.task ? this.editedTask = Vue.util.extend({}, this.task) : this.editedTask = {};
    },
    methods: {
        updateTask(){
            this.$store.dispatch('task/updateTask', this.editedTask).then(response => {
                this.close();
            });
        },
        close(){
            this.$emit('close');
        }
    },
}
</script>
