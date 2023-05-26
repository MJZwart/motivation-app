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
                        <component 
                            :is="modal.component"
                            v-bind="modal.props"
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
import {ShowModal} from './modals';

defineProps<{modal: ShowModal}>();

const modalTemplate = ref<HTMLDivElement>();
</script>