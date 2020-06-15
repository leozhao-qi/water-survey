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
            >Add level</a>
        </nav>

        <levels-create 
            v-if="creating"
        />

        <levels-edit 
            v-if="updating"
        />

        <levels-index 
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
        window.events.$on('levels:edit', level => {
            this.updating = true
        })

        window.events.$on('levels:edit-cancel', level => {
            this.updating = false
        })

        window.events.$on('levels:create-cancel', level => {
            this.creating = false
        })
    },
}
</script>