<template>
    <div>
        <div v-if="!edit">
            {{item}}
            <b-icon-pencil-square
                :id="'edit-item' + index"
                class="icon small"
                @click="edit = true" />
            <b-tooltip :target="'edit-item' + index">{{ $t('edit') }}</b-tooltip>
        </div>
        <div v-else>
            <b-form-textarea
                :id="name"
                v-model="itemToEdit"
                :name="name"
                :rows="rows" />
            <base-form-error :name="name" /> 

            <b-icon-check-square
                :id="'save-' + index"
                class="icon small green"
                @click="save" />
            <b-tooltip :target="'save-' + index">{{ $t('save') }}</b-tooltip>

            <b-icon-x
                :id="'cancel-' + index"
                class="icon small red"
                @click="cancel" />
            <b-tooltip :target="'cancel-' + index">{{ $t('cancel') }}</b-tooltip>
        </div>
    </div>    
</template>

<script>
import BaseFormError from '../BaseFormError.vue';
export default {
    components: {BaseFormError},
    props: {
        item: {
            type: String,
            required: true,
        },
        index: {
            type: Number,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
        rows: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            edit: false,
            itemToEdit: null,
        }
    },
    mounted() {
        this.itemToEdit = this.item;
    },
    methods: {
        save() {
            this.$emit('save', {[this.name]: this.itemToEdit});
            this.cancel();
        },
        cancel() {
            this.itemToEdit = this.item;
            this.edit = false;
        },
    },
}
</script>