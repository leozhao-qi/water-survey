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
            >Add recommendations</a>
        </nav>

        <recommendations-create 
            v-if="creating"
        />

        <recommendations-edit 
            v-if="updating"
        />

        <recommendations-index 
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
        window.events.$on('recommendations:edit', () => {
            this.updating = true
        })

        window.events.$on('recommendations:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('recommendations:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>