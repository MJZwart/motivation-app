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
                    @click="close" />
            </Tooltip>
        </div>
    </div>    
</template>

<script setup>
import {onMounted, ref} from 'vue';
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();

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
    notEmpty: {
        type: Boolean,
        required: false,
        default: true,
    },
});
const emit = defineEmits(['save']);

const edit = ref(false);
const itemToEdit = ref('');

onMounted(() => setValue());

/** 
 * Validation based on props. To add validation, first add a prop, then add the 
 * desired functionality in this. Return a false when invalid and specify the error.
 */
function validate() {
    if (props.notEmpty && itemToEdit.value == '') {
        setErrors(`The ${props.name} field cannot be empty`);
        return false;
    }
    return true;
}

/**
 * Sets the error message on the editable field using the built in BaseFormError
 * using the props.name as key.
 */
function setErrors(error) {
    const errorObject = {};
    errorObject[props.name] = [error];
    mainStore.setErrorMessages(errorObject);
}

/**
 * Confirms the new value and emits it back to the parent component. Please add
 * validation rules where necessary to ensure the back-end failing doesn't cause
 * the Editable to close too soon.
 */
function save() {
    if (!validate()) return;
    emit('save', {[props.name]: itemToEdit.value});
    close();
}

/**
 * Resets value and closes the input field.
 */
function close() {
    setValue();
    edit.value = false;
}
function setValue() {
    itemToEdit.value = props.item
}
</script>