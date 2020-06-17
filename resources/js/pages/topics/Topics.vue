<template>
    <div class="flex flex-col items-center w-full lg:w-9/12 py-16 mx-auto">
        <nav 
            class="flex justify-end w-full items-center"
            v-if="!creating && !updating"
        >
            <a 
                href=""
                @click.prevent="creating = true"
                class="btn btn-text"
            >Add topics</a>
        </nav>

        <topics-create 
            v-if="creating"
        />

        <topics-edit 
            v-if="updating"
        />

        <topics-index 
            v-if="!creating && !updating"
        />
    </div>
</template>

<script>
export default {
     data() {
        return {
            creating: false,
            updating: false
        }
    },

    mounted () {
        window.events.$on('topics:edit', () => {
            this.updating = true
        })

        window.events.$on('topics:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('topics:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>