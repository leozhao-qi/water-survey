<template>
    <div class="w-full">
        <h2 class="text-2xl mb-4">
            Lesson packages
        </h2>

        <template v-if="typeof user.packages !== 'undefined'">
            <datatable 
                :data="user.packages"
                :columns="columns"
                :per-page="10"
                :order-keys="['package']"
                :order-key-directions="['asc']"
                :has-text-filter="true"
                :has-action="true"
                action-text="View"
                :action-link="`/users/${user.id}/packages`"
                action-id="id"
            />
        </template>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'package', title: 'Lesson package', sortable: true }
            ]        
        }
    },

    computed: {
        ...mapGetters({
            user: 'user/user'
        })
    },
}
</script>