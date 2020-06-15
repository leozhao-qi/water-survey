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
                        <strong>Only do this if you are absolutely sure this is what you want</strong>.
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex'

export default {
    data() {
        return {
            modalActive: false
        }
    },

    computed: {
        ...mapGetters({
            objective: 'lessonsWIP/objective'
        })
    },

    methods: {
        ...mapMutations({
            setLesson: 'lessonsWIP/SET_LESSON'
        }),

        close () {
            this.modalActive = false
        },

        async destroy () {
            let { data } = await axios.delete(`${this.urlBase}/api/objectives-wip/${this.objective.id}`)

            await this.setLesson(data.data.lesson)

            this.close()

            this.$emit('close')
        }
    },
}
</script>