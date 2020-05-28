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
            >Add statuses</a>
        </nav>

        <statuses-create 
            v-if="creating"
        />

        <statuses-edit 
            v-if="updating"
        />

        <statuses-index 
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
        window.events.$on('statuses:edit', () => {
            this.updating = true
        })

        window.events.$on('statuses:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('statuses:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>