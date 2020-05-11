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
            :has-action="true"
            action-text="Edit"
            action-link="/levels"
            action-id="id"
            v-if="typeof levels !== 'undefined'"
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
            fetch: 'levels/fetch'
        })
    },

    async mounted () {
        await this.fetch()
    }
}
</script>