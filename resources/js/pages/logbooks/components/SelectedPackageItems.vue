<template>
    <ul>
        <selected-package-item 
            v-for="p in orderBy(packages, ['formatNumber'], ['asc'])"
            :key="p.id"
            :pckg="p"
            :can-delete="canDelete"
        />
    </ul>
</template>

<script>
import { orderBy } from 'lodash-es'

export default {
    props: {
        packages: {
            type: Array,
            required: true
        },
        canDelete: {
            type: Boolean,
            required: false,
            default: true
        }
    },

    methods: {
        orderBy
    }
}
</script>