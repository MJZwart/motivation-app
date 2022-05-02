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
            <Input
                v-if="type == 'input'"
                :id="name"
                v-model="itemToEdit"
                :name="name" />
            <Textarea
                v-if="type == 'textarea'"
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
                    :icon="['far', 'rectangle-xmark']"
                    class="icon small red"
                    @click="cancel" />
            </Tooltip>
        </div>
    </div>    
</template>

<script setup>
import {onMounted, ref} from 'vue';
const props = defineProps({
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
    type: {
        type: String,
        required: false,
        default: 'input',
    },
    rows: {
        type: Number,
        required: false,
        default: 3,
    },
});
const emit = defineEmits(['save']);

const edit = ref(false);
const itemToEdit = ref('');

onMounted(() => setValue());

function save() {
    emit('save', {[props.name]: itemToEdit.value});
    cancel();
}
function cancel() {
    setValue();
    edit.value = false;
}
function setValue() {
    itemToEdit.value = props.item
}
</script>