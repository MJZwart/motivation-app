<template>
    <table :class="className">
        <thead>
            <tr>
                <th v-for="(field, index) in fields" :key="index">{{ field.label }}</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(item, index) in items" :key="index">
                <td v-for="(field, index) in fields" :key="index">{{item[field.key]}}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        items: {
            type: Array,
            required: true,
        },
        fields: {
            type: Array,
            required: true,
        },
        options: {
            type: Array,
            required: false,
        },
    },
    computed: {
        className() {
            let className = 'table ';
            for (const item of this.options) {
                className += ' ' + item;
            }
            return className;
        },
    },
}
</script>