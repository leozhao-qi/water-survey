<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Topics
        </h1> 

        <datatable 
            :data="topics"
            :columns="columns"
            :per-page="10"
            :order-keys="['number']"
            :order-key-directions="['asc']"
            :has-text-filter="false"
            :has-event="true"
            event-text="Edit"
            event="topics:edit"
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
            topics: 'topics/topics'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'topics/fetch',
            setEdit: 'topics/setEdit'
        })
    },

    async mounted () {
        await this.fetch()

        window.events.$on('topics:edit', topic => {
            this.setEdit(topic)
        })
    }
}
</script>