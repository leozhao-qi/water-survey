<template>
    <div class="flex flex-col items-center w-full py-16">
        <nav 
            class="flex justify-end w-full lg:w-9/12 items-center"
            v-if="!creating && !activating"
        >
            <a 
                href=""
                @click.prevent="creating = true"
                class="btn btn-text"
            >Add users</a>

            <span>|</span>

            <a 
                href=""
                @click.prevent="activating = true"
                class="btn btn-text"
            >Re-activate users</a>
        </nav>

        <users-create 
            v-if="creating"
        />

        <users-deactivation 
            v-if="activating"
        />

        <users-index 
            v-if="!creating && !activating"
        />
    </div>
</template>

<script>
export default {
    data() {
        return {
            creating: false,
            activating: false
        }
    },

    mounted () {
        window.events.$on('datatable:cancel', () => {
            this.creating = false
            this.activating = false
        })

        window.events.$on('user:activated', () => {
            this.activating = false
        })
        
        window.events.$on('datatable:clear', () => {
            this.creating = false
        })
    }
}
</script>