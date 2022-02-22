<template>
    <div>
        <p class="group-title">{{group.name}}</p>
        <p class="group-description">{{group.description}}</p>
        <div v-for="member in group.members" :key="member.id" class="member">
            <p class="member">{{member.name}}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        group: {
            type: Object,
            required: true,
        },
    },
}
</script>