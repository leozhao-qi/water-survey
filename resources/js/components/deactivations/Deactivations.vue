<template>
    <div>
        <ul v-if="user.deactivations && user.deactivations.length > 0">
            <li
                v-for="deactivation in user.deactivations"
                :key="deactivation.id"
                class="py-4 border-gray-300 border-b"
            >
                <deactivation 
                    :deactivation="deactivation"
                />
            </li>
        </ul>

        <div 
            class="alert alert-blue"
            v-else
        >
            There are no deactivations for {{ user.firstname }} {{ user.lastname }}
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            user: 'user/user'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'user/fetch'
        })
    },

    mounted () {
        window.events.$on('deactivations:deleted', () => {
            this.fetch(this.user.id)
        })
    }
}
</script>

<style scoped>
    li:first-child {
        border-top: 1px solid #EDF2F7;
    }
</style>