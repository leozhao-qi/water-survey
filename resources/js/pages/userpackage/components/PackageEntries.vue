<template>
    <div class="w-full mt-8">
        <h2 class="text-2xl">
            Logbook entries
        </h2>

        <button 
            class="btn btn-text btn-sm text-sm mt-2"
            @click.prevent="open"
        >
            Show entries
        </button>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            userPackage: 'userpackage/userPackage'
        })
    },

    methods: {
        open () {
            window.open(`${urlBase}/logbooks?package=${this.userPackage.package}&user=${this.userPackage.user.fullname}`, "Logbook entries")
        }
    }
}
</script>