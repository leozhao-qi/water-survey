<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Lessons
        </h1> 

        <datatable 
            :data="lessons"
            :columns="columns"
            :per-page="10"
            :order-keys="['number']"
            :order-key-directions="['asc']"
            :has-text-filter="true"
            :has-event="true"
            event-text="Edit"
            event="lessons:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'number', title: 'Number', sortable: true },
                { field: 'name', title: 'Name', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            lessons: 'lessons/lessons'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'lessons/fetch',
            setEdit: 'lessons/setEdit'
        })
    },

    async mounted () {
        await this.fetch()

        window.events.$on('lessons:edit', lesson => {
            this.setEdit(lesson)
        })
    }
}
</script>