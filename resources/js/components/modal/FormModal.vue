<template>
    <div ref="modalTemplate">
        <Transition name="modal">
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
                                @submit="catchSubmit"
                                @close="$emit('close')"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';
import {FormModal} from './modals';

const props = defineProps<{modal: FormModal}>();
defineEmits(['close']);

const modalTemplate = ref<HTMLDivElement>();

function catchSubmit(e) {
    props.modal.submitEvent(e);
}
</script>