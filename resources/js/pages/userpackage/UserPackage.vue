<template>
    <div class="flex flex-col items-center w-full lg:w-9/12 pt-16 pb-32 mx-auto">
        <package-info />

        <package-status @userpackage:change="updatePackage" />

        <package-objectives @userpackage:change="updatePackage" />

        <statement-of-competency @userpackage:change="updatePackage" />

        <div class="fixed bottom-0 w-full flex bg-white p-4 shadow-inner">
            <button 
                class="btn btn-blue ml-auto"
                @click.prevent="update({ userId, userpackageId })"
            >
                Update lesson package
            </button>
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

    methods: {
        ...mapActions({
            fetch: 'userpackage/fetch',
            updatePackage: 'userpackage/updatePackageObj',
            update: 'userpackage/update'
        }),
    },

    async mounted () {
        await this.fetch({
            userId: this.userId,
            userpackageId: this.userpackageId
        })

        window.events.$on('userpackage:updated', message => {
            this.$toasted.success(message)
        })
    }
}
</script>