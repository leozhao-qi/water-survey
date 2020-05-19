<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Lesson versions
        </h1> 

        <datatable 
            :data="lessonVersions"
            :columns="columns"
            :per-page="10"
            :order-keys="['version']"
            :order-key-directions="['asc']"
            :has-text-filter="false"
            :has-event="true"
            event-text="Edit"
            event="lesson-versions:edit"
        />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'version', title: 'Version', sortable: true },
                { field: 'valid_on', title: 'Valid on', sortable: true }
            ]
        }
    },

    computed: {
        ...mapGetters({
            lessonVersions: 'lessonVersions/lessonVersions'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'lessonVersions/fetch',
            setEdit: 'lessonVersions/setEdit'
        })
    },

    async mounted () {
        await this.fetch()

        window.events.$on('lesson-versions:edit', lessonVersion => {
            this.setEdit(lessonVersion)
        })
    }
}
</script>