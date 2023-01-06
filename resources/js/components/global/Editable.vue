<template>
    <div>
        <div v-if="!edit">
            {{ item }}
            <Tooltip :text="$t('edit')">
                <Icon 
                    :id="'edit-item-' + index"
                    :icon="EDIT"
                    class="icon small edit-icon"
                    @click="edit = true" />
            </Tooltip>
        </div>
        <div v-else :class="{inline: inline}">
            <div class="input">
                <SimpleInput v-if="type == 'input'" :id="name" v-model="itemToEdit" :name="name" />
                <SimpleTextarea v-if="type == 'textarea'" :id="name" v-model="itemToEdit" :name="name" :rows="rows" />
            </div>

            <div class="controls">
                <Tooltip :text="$t('save')">
                    <Icon 
                        :id="'save-' + index" 
                        :icon="CHECK_SQUARE" 
                        class="check-square-icon green medium"
                        @click="save" />
                </Tooltip>
                
                <Tooltip :text="$t('cancel')">
                    <Icon 
                        :id="'cancel-' + index" 
                        :icon="CROSS_SQUARE" 
                        class="red medium cross-square-icon"
                        @click="close" />
                </Tooltip>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {Error} from 'resources/types/error';
import {onMounted, ref} from 'vue';
import {EDIT, CHECK_SQUARE, CROSS_SQUARE} from '/js/constants/iconConstants';
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
    inline: {
        type: Boolean,
        required: false,
        default: false,
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
        addError(`The ${props.name} field cannot be empty`);
        return false;
    }
    return true;
}

/**
 * Adds the error message on the editable field using the built in BaseFormError
 * using the props.name as key and commits it to the store.
 */
function addError(error: string) {
    const errorObject = {} as Error;
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
    itemToEdit.value = props.item;
}
</script>

<style lang="scss" scoped>
.inline {
    display: flex;
    .input {
        flex-grow: 3;
    }
    .controls {
        flex-grow: 1;
    }
}
</style>