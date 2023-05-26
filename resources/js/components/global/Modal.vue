<template>
    <Transition name="modal">
        <div v-if="show" class="modal-mask">
            <div class="modal-wrapper" @mousedown="$emit('close')">
                <div class="modal-container" @mousedown.stop="">
                    <slot name="header">
                        <div v-if="header" class="modal-header">
                            <h5 class="modal-title">{{title}}</h5>
                            <button class="close" @click="$emit('close')">×</button>
                        </div>
                    </slot>

                    <div class="modal-body">
                        <slot />
                    </div>

                    <slot name="footer">
                        <div v-if="footer">
                            <button 
                                class="button-cancel mr-1" 
                                @click="$emit('close')">
                                Cancel
                            </button>
                            <button
                                class="primary"
                                @click="$emit('close')">
                                OK
                            </button>
                        </div>
                    </slot>
                </div>
            </div>
        </div>
    </Transition>
</template>

<script setup>
defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        required: false,
    },
    footer: {
        type: Boolean,
        required: false,
        default: false,
    },
    header: {
        type: Boolean,
        required: false,
        default: true,
    },
});
</script>

<style lang="scss">

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 90%;
  max-width: 500px;
  max-height: 95vh;
  overflow-y: auto;
  margin: 0px auto;
  background-color: var(--background-2);
  border-radius: 0.3rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
}
.modal-mask.s .modal-container {
    max-width: 300px;
}
.modal-mask.m .modal-container {
    max-width: 500px;
}
.modal-mask.l .modal-container {
    max-width: 700px;
}
.modal-mask.xl .modal-container {
    max-width: 900px;
}
.modal-mask.m-override .modal-container {
    max-width: 500px !important;
}

.modal-default-button {
  float: right;
}
.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    border-top-left-radius: calc(0.3rem - 1px);
    border-top-right-radius: calc(0.3rem - 1px);
    background-color: var(--primary);
    .modal-title {
        line-height: 1.5;
        font-size: 1.25rem;
        margin: 0;
        color: var(--primary-text);
    }
    .close {
        padding: 1rem;
        margin: -1rem -1rem -1rem auto;
        background-color: transparent;
        border: 0;
        color: var(--primary-text);
        font-size: 1.5rem;
        font-family: var(--bold-font);
        line-height: 1;
        text-shadow: 0 1px 0 var(--background-2);
        opacity: 0.5;
    }
}

.modal-body {
    position: relative;
    flex: 1 1 auto;
    padding: 1rem;
    overflow-y: auto;
    height: 80%;
}
</style>