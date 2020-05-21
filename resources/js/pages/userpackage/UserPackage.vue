<template>
    <div class="flex flex-col items-center w-full lg:w-9/12 py-16 mx-auto">
        <div class="w-full">
            <h1 class="text-3xl font-bold mb-2">
                {{ userPackage.package }}
            </h1>

            <p>
                <strong>Level: </strong>{{ userPackage.level }}
            </p>

            <p>
                <strong>Version: </strong>{{ userPackage.version }}
            </p>

            <p v-if="typeof userPackage.user !== 'undefined'">
                <strong>Apprentice:</strong> 
                <a :href="`${urlBase}/users/${userPackage.user_id}`">
                    {{ userPackage.user.firstname }} {{ userPackage.user.lastname }}
                </a>
            </p>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
    props: {
        userId: {
            type: Number,
            required: true
        },
        userpackageId: {
            type: Number,
            required: true
        }
    },

    computed: {
        ...mapGetters({
            userPackage: 'userpackage/userPackage'
        })    
    },

    methods: {
        ...mapActions({
            fetch: 'userpackage/fetch'
        })
    },

    async mounted () {
        await this.fetch({
            userId: this.userId,
            userpackageId: this.userpackageId
        })
    }
}
</script>