<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            New lesson packages version
        </h1>

        <h2 class="text-2xl font-normal mb-4">
            Work in progress
        </h2>

        <datatable 
            :data="lessons"
            :columns="columns"
            :per-page="10"
            :order-keys="['formatNumber']"
            :order-key-directions="['asc']"
            :has-text-filter="true"
            :has-event="true"
            event-text="View"
            event="lessons-wip:edit"
        />
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'formatNumber', title: 'Number', sortable: true },
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
            fetch: 'lessonsWIP/fetch',
            setEdit: 'lessonsWIP/setEdit'
        })    
    },

    async mounted () {
        await this.fetch()

        window.events.$on('lessons-wip:edit', lesson => {
            this.setEdit(lesson)
        })
    }
}
</script>