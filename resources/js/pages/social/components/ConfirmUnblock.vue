<template>
    <div>
        {{$t('unblock-user-confirmation', {user: blockedUser.blocked_user})}}
        <div class="form-group">
            <SimpleCheckbox 
                id="restore_messages" 
                v-model="restoreMessages" 
                name="restore_messages" />
            <label for="restore_messages" class="pointer" @click="restoreMessages = !restoreMessages">
                {{$t('restore-messages')}}
            </label>
        </div>
        <div class="d-flex">
            <button class="ml-auto mr-2" @click="emit('close')">{{ $t('cancel') }}</button>
            <SubmitButton @click="confirmUnblock" />
        </div>
    </div>
</template>

<script setup lang="ts">
import SimpleCheckbox from '/js/components/global/small/SimpleCheckbox.vue';
import {Blocked} from 'resources/types/user';
import {ref} from 'vue';
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import {useUserStore} from '/js/store/userStore';

const userStore = useUserStore();

const props = defineProps<{blockedUser: Blocked}>();
const emit = defineEmits(['close']);
const restoreMessages = ref(true);

async function confirmUnblock() {
    await userStore.unblockUser(props.blockedUser.id, {'restoreMessages': restoreMessages.value});
    emit('close', true);
}
</script>