<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Levels
        </h1> 

        <datatable 
            :data="levels"
            :columns="columns"
            :per-page="10"
            :order-keys="['name']"
            :order-key-directions="['asc']"
            :has-text-filter="false"
            :has-event="true"
            event-text="Edit"
            event="levels:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            users: [],
            columns: [
                { field: 'name', title: 'Name', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            levels: 'levels/levels'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'levels/fetch',
            setEdit: 'levels/setEdit'
        })
    },

    async mounted () {
        await this.fetch()

        window.events.$on('levels:edit', level => {
            this.setEdit(level)
        })
    }
}
</script>