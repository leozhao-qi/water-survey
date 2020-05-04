<template>
    <div class="flex flex-col items-center w-full py-16">
        <h1 class="text-3xl mb-4 w-full">
            {{ user.firstname }} {{ user.lastname }}
        </h1>

        <change-password />

        <role />

        <appoitment-date />

        <profile-deactivations />

        <reporting-structure />
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    props: {
        userId: {
            type: Number,
            required: true
        }
    },

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

    mounted() {
        this.fetch(this.userId)
    },
}
</script>