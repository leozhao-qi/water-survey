<template>
    <div class="w-full">
        <button 
            class="btn btn-text text-red-500 text-sm"
            @click.prevent="modalActive = true"
        >
            Delete objective
        </button>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroy"
        >
            <template slot="header">
                Delete Lesson: {{ objective.lesson }} - Objective {{ objective.number }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        Are you sure you want to do this? All information about this objective will be permenantly deleted.
                        This objective is also associated with a lesson package. Any users who have completed this objective 
                        wil have it removed from their records permanently.
                        <strong>Only do this if you are absolutely sure this is what you want</strong>.
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            modalActive: false
        }
    },

    computed: {
        ...mapGetters({
            objective: 'objectives/objective'
        })
    },

    methods: {
        close () {
            this.modalActive = false
        },

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/objectives/${this.objective.id}`)

            this.close()

            this.$emit('close')
        }
    },
}
</script>