<template>
    <div class="flex flex-col items-center w-full py-16 mx-auto">
        <nav 
            class="flex justify-end w-full items-center"
            v-if="!creating && !updating"
        >
            <a 
                href=""
                @click.prevent="creating = true"
                class="btn btn-text"
            >Add objective</a>
        </nav>

        <objectives-create 
            v-if="creating"
        />

        <objectives-edit 
            v-if="updating"
        />

        <objectives-index 
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
        window.events.$on('objectives:edit', () => {
            this.updating = true
        })

        window.events.$on('objectives:edit-cancel', () => {
            this.updating = false
        })

        window.events.$on('objectives:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>