<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Logbook categories
        </h1> 

        <datatable 
            :data="logbookCategories"
            :columns="columns"
            :per-page="10"
            :order-keys="['name']"
            :order-key-directions="['asc']"
            :has-text-filter="false"
            :has-event="true"
            event-text="Edit"
            event="logbook-categories:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'name', title: 'Name', sortable: true },
                { field: 'supervisor_only_formatted', title: 'Can only be seen by supervisors', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            logbookCategories: 'logbookCategories/logbookCategories'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'logbookCategories/fetch',
            setEdit: 'logbookCategories/setEdit'
        })
    },

    async mounted () {
        await this.fetch()

        window.events.$on('logbook-categories:edit', logbookCategory => {
            this.setEdit(logbookCategory)
        })
    }
}
</script>