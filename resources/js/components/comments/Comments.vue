<template>
    <div>
        <div class="flex items-center mt-6 mb-2">
            <h2 class="text-2xl">
                Comments
            </h2>

            <button 
                class="btn btn-text text-sm text-blue-500"
                v-if="!adding"
                @click.prevent="adding = true"
            >
                Add comment
            </button>
        </div>

        <new-comment v-if="adding" />

        <comments-list
            v-if="logbook.comments.length"
        />

        <div
            v-if="logbook.comments.length === 0 && !adding"
            class="alert alert-blue w-full lg:w-1/2"
        >There are no comments for this logbook.</div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            adding: false
        }
    },

    computed: {
        ...mapGetters({
            logbook: 'logbooks/logbook'
        })
    },

    mounted () {
        window.events.$on('comments:cancel', () => {
            this.adding = false
        })
    }
}
</script>