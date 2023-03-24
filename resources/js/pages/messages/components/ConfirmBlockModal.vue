<template>
    <div>
        {{$t('block-user-confirmation', {user: user.username})}}
        <div class="form-group">
            <SimpleCheckbox 
                id="hide_messages" 
                v-model="hideMessages" 
                name="hide_messages" />
            <label for="hide_messages" class="pointer" @click="hideMessages = !hideMessages">
                {{$t('hide-messages')}}
            </label>
        </div>
        <div class="d-flex">
            <button class="ml-auto mr-2" @click="emit('close')">{{ $t('cancel') }}</button>
            <SubmitButton @click="confirmBlock" />
        </div>
    </div>
</template>

<script setup lang="ts">
import SimpleCheckbox from '/js/components/global/small/SimpleCheckbox.vue';
import {StrippedUser} from 'resources/types/user';
import {ref} from 'vue';
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import {useUserStore} from '/js/store/userStore';

const userStore = useUserStore();

const props = defineProps<{user: StrippedUser}>();
const emit = defineEmits(['close']);
const hideMessages = ref(false);

async function confirmBlock() {
    const blockPayload = {
        userId: props.user.id,
        hideMessages: hideMessages.value,
    };
    await userStore.blockUser(blockPayload);
    emit('close', true);
}
</script>