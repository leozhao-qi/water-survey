<template>
    <ul>
        <selected-package-item 
            v-for="p in packages"
            :key="p.id"
            :pckg="p"
        />
    </ul>
</template>

<script>
export default {
    props: {
        packages: {
            type: Array,
            required: true
        }
    }
}
</script>