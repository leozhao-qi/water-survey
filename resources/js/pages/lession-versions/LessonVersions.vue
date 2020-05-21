<template>
    <div class="flex flex-col items-center w-full lg:w-9/12 py-16 mx-auto">
        <nav 
            class="flex justify-end w-full items-center"
        >
            <a 
                :href="`${urlBase}/lesson-versions/create`"
                class="btn btn-text"
            >Create new version</a>
        </nav>

        <lesson-versions-edit 
            v-if="updating"
        />

        <lesson-versions-index 
            v-if="!updating"
        />
    </div>
</template>

<script>
export default {
     data() {
        return {
            updating: false
        }
    },

    mounted () {
        window.events.$on('lesson-versions:edit', () => {
            this.updating = true
        })

        window.events.$on('lesson-versions:edit-cancel', level => {
            this.updating = false
        })
    }
}
</script>