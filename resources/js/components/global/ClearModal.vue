<template>
    <Transition name="modal">
        <div v-if="show" class="backdrop" @mousedown="$emit('close')">
            <div class="content scale-in-top" @mousedown.stop="">
                <component :is="item" />   
                <small class="subtext">{{ $t('click-to-close')}}</small> 
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
    item: {
        type: Object,
        required: true,
    },
})
defineEmits(['close']);
</script>

<style lang="scss">
.backdrop {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
        .content {
            width: 90%;
            max-width: 400px;
            overflow-y: auto;
            margin: auto auto;
            background-color: #fff;
            border-radius: 0.3rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
            color: black;
            padding: 1rem;
            .subtext {
                font-size: 0.8rem;
                color: grey;
                margin-top: 0.5rem;
            }
        }
}
</style>