<template>
    <div class="w-full py-16">
        <h1 class="text-3xl font-bold mb-4">
            New lesson packages version
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
            event="lessons-wip-edit:edit"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

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
            lessons: 'lessonsWIP/lessons'
        })    
    },

    methods: {
        ...mapActions({
            fetch: 'lessonsWIP/fetch'
        })    
    },

    async mounted () {
        await this.fetch()
    }
}
</script>