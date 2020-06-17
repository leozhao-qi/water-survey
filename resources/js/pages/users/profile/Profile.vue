<template>
    <div class="flex flex-col items-center w-full py-16">
        <h1 class="text-3xl mb-4 w-full flex items-center">
            <span>{{ user.firstname }} {{ user.lastname }}</span>

            <button 
                class="btn btn-blue text-sm btn-sm ml-4"
                v-if="user.role === 'apprentice' && typeof user.packages !== 'undefined' && user.packages.length"
                @click.prevent="showSot"
            >
                View schedule of training
            </button>
        </h1>

        <change-password />

        <role />

        <appoitment-date />

        <profile-deactivations />

        <reporting-structure />

        <template v-if="typeof user.packages !== 'undefined'">
            <hr class="block w-full mt-6 pt-6 border-t border-gray-200">

            <unassigned-user-packages v-if="hasRole(['administrator'])" />

            <user-packages />
        </template>
        
        <hr class="block w-full mt-6 pt-6 border-t border-gray-200">

        <destroy-user 
            v-if="hasRole(['administrator'])"
        />
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
        }),

        showSot () {
            window.location.href = `${this.urlBase}/users/${this.user.id}/reports/sot`
        }
    },

    mounted() {
        this.fetch(this.userId)
    },
}
</script>