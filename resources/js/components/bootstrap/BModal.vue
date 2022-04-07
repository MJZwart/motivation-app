<template>
    <Transition name="modal">
        <div v-if="show" class="modal-mask">
            <div class="modal-wrapper" @click="$emit('close')">
                <div class="modal-container" @click.stop="">
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
                                class="btn btn-primary mr-1" 
                                @click="$emit('close')">
                                Cancel
                            </button>
                            <button
                                class="btn btn-primary"
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

<script>
export default {
    props: {
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
            default: true,
        },
        header: {
            type: Boolean,
            required: false,
            default: true,
        },
    },
}
</script>

<style lang="scss" scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: opacity 0.3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}

.modal-container {
  width: 500px;
  margin: 0px auto;
  background-color: #fff;
  border-radius: 0.3rem;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
}
.modal-mask.s .modal-container {
    width: 300px;
}
.modal-mask.l .modal-container {
    width: 700px;
}
.modal-mask.xl .modal-container {
    width: 900px;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
  display: flex;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter-from {
    // transform: none;
    opacity: 0;
}

.modal-leave-to {
    // transition: transform 0.3s ease-out;
    opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
    transform: opacity 0.15s linear;
}
</style>