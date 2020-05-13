<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Objectives
        </h1> 

        <datatable 
            :data="objectives"
            :columns="columns"
            :per-page="10"
            :order-keys="['lesson', 'number']"
            :order-key-directions="['asc', 'asc']"
            :has-text-filter="true"
            :has-event="true"
            event-text="Edit"
            event="objectives:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'lesson', title: 'Lesson', sortable: true },
                { field: 'number', title: 'Objective', sortable: true },
                { field: 'name', title: 'Description', sortable: true },
                { field: 'type_format', title: 'Type', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            objectives: 'objectives/objectives'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'objectives/fetch',
            setEdit: 'objectives/setEdit'
        })
    },

    async mounted () {
        await this.fetch()

        window.events.$on('objectives:edit', objective => {
            this.setEdit(objective)
        })
    }
}
</script>