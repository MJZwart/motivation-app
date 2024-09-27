<template>
    <div ref="modalTemplate">
        <div class="modal-mask">
            <div class="modal-wrapper" @mousedown="$emit('close')">
                <div class="modal-container" @mousedown.stop="">
                    <div class="modal-header">
                        <h5 class="modal-title">{{$t(modal.title)}}</h5>
                        <button class="close" @click="$emit('close')">×</button>
                    </div>

                    <div class="modal-body">
                        {{ modal.text }}
                        <div class="d-flex">
                            <button class="ml-auto mr-2 cancel-button" @click="$emit('close')">{{ modal.cancelText }}</button>
                            <button type="submit" @click="confirm">{{ modal.confirmText }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {ConfirmModal} from './modals';

const props = defineProps<{modal: ConfirmModal}>();

const emit = defineEmits(['close']);

const confirm = async () => {
    await props.modal.confirmFunction();

    emit('close');
}

const modalTemplate = ref<HTMLDivElement>();
</script>