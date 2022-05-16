<template>
    <div v-if="userBanToEdit">
        {{userBan}}
        <Input
            id="days"
            v-model="userBanToEdit.days"
            type="number"
            name="days"
            :label="$t('days')"
            :placeholder="$t('days')" />
        Banned until: {{bannedUntil}}
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';

const props = defineProps({
    userBan: {
        type: Object,
        required: true,
    },
});

onMounted(() => {
    userBanToEdit.value = Object.assign({}, props.userBan);
});

const userBanToEdit = ref(null);
const bannedUntil = computed(() => {
    let date = new Date(props.userBan.created_at);
    return date.addDays(userBanToEdit.value.days);
});

Date.prototype.addDays = function(days) {
    const date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}

</script>