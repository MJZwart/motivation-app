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
        <FormControls
            @submit="confirmBlock"
            @cancel="$emit('close')"
        />
    </div>
</template>

<script setup lang="ts">
import SimpleCheckbox from '/js/components/global/small/SimpleCheckbox.vue';
import {StrippedUser} from 'resources/types/user';
import {ref} from 'vue';
import {useUserStore} from '/js/store/userStore';
import FormControls from '/js/components/global/FormControls.vue';

const userStore = useUserStore();

const props = defineProps<{user: StrippedUser}>();
const emit = defineEmits(['close']);
const hideMessages = ref(false);

async function confirmBlock() {
    await userStore.blockUser(props.user.id, {'hideMessages': hideMessages.value});
    emit('close', true);
}
</script>