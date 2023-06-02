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
            @submit="$emit('submit', {user, hideMessages})"
            @cancel="$emit('close')"
        />
    </div>
</template>

<script setup lang="ts">
import SimpleCheckbox from '/js/components/global/small/SimpleCheckbox.vue';
import {StrippedUser} from 'resources/types/user';
import {ref} from 'vue';
import FormControls from '/js/components/global/FormControls.vue';
import {deepCopy} from '/js/helpers/copy';

const props = defineProps<{form: StrippedUser}>();
defineEmits(['close', 'submit']);

const user = ref(deepCopy(props.form));

const hideMessages = ref(false);
</script>