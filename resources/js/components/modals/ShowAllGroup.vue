<template>
    <div v-if="group">
        <div class="group">
            <p class="group-title">{{group.name}}</p>
            <p class="group-description">{{group.description}}</p>
            <p class="members">Members: </p>
            <div v-for="member in group.members" :key="member.id" class="member">
                <p class="member">{{member.username}} {{member.rank}}</p>
            </div>
            <b-button type="button" @click="close">{{$t('close-group-button')}}</b-button>
            <b-button type="button" @click="joinGroup">{{$t('join-group-button')}}</b-button>
        </div>
    </div>
</template>

<script>
import BaseFormError from '../BaseFormError.vue';
import Vue from 'vue';

export default {
    components: {BaseFormError},
    props: {
        group: {
            type: Object,
            required: true,
        },
    },
    data () {
        return {
        }
    },
    methods: {
        joinGroup() {
            this.$store.dispatch('groups/joinGroup', this.group).then(() => {
                this.$emit('reloadGroups');
                this.close();
            });
        },
        close() {
            this.$emit('close');
        }
    }
}
</script>