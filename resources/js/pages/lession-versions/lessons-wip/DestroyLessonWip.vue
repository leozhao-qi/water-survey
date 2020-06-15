<template>
    <div class="w-full">
        <button 
            class="btn btn-text text-red-500 text-sm"
            @click.prevent="modalActive = true"
        >
            Delete lesson
        </button>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="destroy"
        >
            <template slot="header">
                Delete lesson: {{ lesson.number }} - {{ lesson.name }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <p class="text-red-500">
                        Are you sure you want to do this? All information about this lesson will be permenantly deleted.
                        This includes all associated objectives. 
                        <strong>Only do this if you are absolutely sure this is what you want</strong>.
                    </p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            modalActive: false
        }
    },

    computed: {
        ...mapGetters({
            lesson: 'lessonsWIP/lesson'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'lessonsWIP/fetch'
        }),

        close () {
            this.modalActive = false
        },

        async destroy () {
            let { data } = await axios.delete(`api/lessons-wip/${this.lesson.id}`)

            await this.fetch()

            this.close()

            this.$emit('close')
        }
    },
}
</script>