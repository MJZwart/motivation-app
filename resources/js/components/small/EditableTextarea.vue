<template>
    <div>
        <div v-if="!edit">
            {{item}}
            <Tooltip :text="$t('edit')">
                <FaIcon 
                    :id="'edit-item-' + index"
                    :icon="['far', 'pen-to-square']"
                    class="icon small"
                    @click="edit = true" />
            </Tooltip>
        </div>
        <div v-else>
            <textarea
                :id="name"
                v-model="itemToEdit"
                :name="name"
                :rows="rows" />
            <BaseFormError :name="name" /> 

            <Tooltip :text="$t('save')">
                <FaIcon 
                    :id="'save-' + index"
                    :icon="['far', 'square-check']"
                    class="icon small green"
                    @click="save" />
            </Tooltip>

            <Tooltip :text="$t('cancel')">
                <FaIcon 
                    :id="'cancel-' + index"
                    :icon="['far', 'rectangle-x-mark']"
                    class="icon small red"
                    @click="cancel" />
            </Tooltip>
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