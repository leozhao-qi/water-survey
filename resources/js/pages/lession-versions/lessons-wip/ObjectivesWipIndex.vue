<template>
    <div class="w-full">
        <h1 class="text-2xl font-normal mb-4">
            Lesson objectives
        </h1> 

        <datatable 
            :data="lesson.objectives"
            :columns="columns"
            :per-page="10"
            :order-keys="['number']"
            :order-key-directions="['asc']"
            :has-text-filter="false"
            :has-event="true"
            event-text="Edit"
            event="objectives-wip:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'number', title: 'Objective', sortable: true },
                { field: 'name', title: 'Description', sortable: true },
                { field: 'type_format', title: 'Type', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            lesson: 'lessonsWIP/lesson'
        })
    },

    methods: {
        ...mapActions({
            setEdit: 'lessonsWIP/setEditObjective'
        })
    },

    async mounted () {
        window.events.$on('objectives-wip:edit', objective => {
            this.setEdit(objective)
        })
    }
}
</script>