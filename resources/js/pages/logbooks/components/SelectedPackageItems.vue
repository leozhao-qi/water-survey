<template>
    <ul>
        <selected-package-item 
            v-for="p in packages"
            :key="p.id"
            :pckg="p"
            :can-delete="canDelete"
        />
    </ul>
</template>

<script>
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
    }
}
</script>