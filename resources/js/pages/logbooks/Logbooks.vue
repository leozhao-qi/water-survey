<template>
    <div class="flex flex-col items-center w-full lg:w-9/12 py-16 mx-auto">
        <nav 
            class="flex justify-end w-full items-center"
            v-if="hasRole(['apprentice', 'supervisor']) && !creating && !showing"
            @click.prevent="creating = true"
        >
            <a 
                href=""
                class="btn btn-text"
            >Add logbook entry</a>
        </nav>

        <logbooks-create 
            v-if="creating"
        />

        <logbooks-show 
            v-if="showing"
        />

        <logbooks-index 
            v-if="!creating && !showing"
        />
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    data() {
        return {
            creating: false,
            showing: false
        }
    },

    methods: {
        ...mapActions({
            show: 'logbooks/show'
        })
    },

    mounted () {
        window.events.$on('logbooks:create-cancel', () => {
            this.creating = false
        })

        window.events.$on('logbooks:show', async logbookId => {
            await this.show(logbookId)

            this.showing = true
        })

        window.events.$on('logbooks:hide', () => {
            this.showing = false
        })
    }
}
</script>