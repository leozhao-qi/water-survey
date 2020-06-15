<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Statuses
        </h1> 

        <datatable 
            :data="statuses"
            :columns="columns"
            :per-page="10"
            :order-keys="['code']"
            :order-key-directions="['asc']"
            :has-text-filter="false"
            :has-event="true"
            event-text="Edit"
            event="statuses:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'code', title: 'Code', sortable: true },
                { field: 'name', title: 'Name', sortable: true },
                { field: 'completion_formatted', title: 'Completion', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            statuses: 'statuses/statuses'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'statuses/fetch',
            setEdit: 'statuses/setEdit'
        })
    },

    async mounted () {
        await this.fetch()

        window.events.$on('statuses:edit', status => {
            this.setEdit(status)
        })
    }
}
</script>