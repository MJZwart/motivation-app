<template>
    <div ref="modalTemplate">
        <div class="modal-mask">
            <div class="modal-wrapper" @mousedown="$emit('close')">
                <div class="modal-container" @mousedown.stop="">
                    <div class="modal-header">
                        <h5 class="modal-title">{{modal.title}}</h5>
                        <button class="close" @click="$emit('close')">×</button>
                    </div>

                    <div class="modal-body">
                        <component 
                            :is="modal.component"
                            :form="modal.form"
                            @submit="submit"
                            @close="$emit('close')"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {FormModal} from './modals';

const props = defineProps<{modal: FormModal}>();
const emit = defineEmits(['close']);

const modalTemplate = ref<HTMLDivElement>();

async function submit(editedItem: unknown) {
    try {
        await props.modal.submitEvent(editedItem);
        emit('close');
    } catch (e) {
        //
    }
}
</script>