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
        <FormControls
            @submit="$emit('submit', {blockedUser, restoreMessages})"
            @cancel="$emit('close')"
        />
    </div>
</template>

<script setup lang="ts">
import SimpleCheckbox from '/js/components/global/small/SimpleCheckbox.vue';
import {Blocked} from 'resources/types/user';
import {ref} from 'vue';
import FormControls from '/js/components/global/FormControls.vue';
import {deepCopy} from '/js/helpers/copy';

const props = defineProps<{form: Blocked}>();
defineEmits(['close', 'submit']);

const blockedUser = ref(deepCopy(props.form));

const restoreMessages = ref(true);
</script>