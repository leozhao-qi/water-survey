<template>
    <div class="w-full" v-if="typeof logbook !== 'undefined'">
        <div class="mb-4 flex items-center">
            <a class="no-underline cursor-pointer" @click.prevent="back">
                <span class="text-lg">&larr;</span> Back to logbooks
            </a>

            <template v-if="!updating && (hasRole(['administrator']) || (logbook.user.id === parseInt(authUser.id)))">
                <button 
                    class="btn btn-text text-blue-500 ml-auto"
                    @click.prevent="updating = true"
                >
                    Update
                </button>

                <span class="mx-1">|</span>

                <button 
                    class="btn btn-text text-red-500 "
                    @click.prevent="modalActive = true"
                >
                    Delete
                </button>
            </template>
        </div>

        <template v-if="!updating">
            <h2 class="tracking-widest text-xs title-font font-medium uppercase text-gray-500 mb-1">
                {{ logbook.category.name }}
            </h2>

            <h1 class="text-3xl fornt-normal mb-2">
                {{ logbook.event_description }}
            </h1>

            <div class="mb-6">
                <p class="text-gray-500 text-sm">
                    <strong>Created by:</strong> 
                    {{ logbook.user.fullname }} ({{ ucfirst(logbook.user.role) }}) 
                    on {{ dayjs(logbook.created).format('MM/DD/YYYY') }}
                </p>

                <p class="text-gray-500 text-sm" v-if="logbook.updated">
                    <strong>Updated:</strong> 
                    at {{ dayjs(logbook.updated).format('MM/DD/YYYY') }}
                </p>
            </div>

            <div 
                class="content"
                v-html="formattedEntry"
            ></div>

            <template v-if="logbook.files.length">
                <h2 class="text-2xl mt-6 mb-2">
                    Files
                </h2>

                <logbook-files />
            </template>

            <comments />
        </template>

        <logbooks-edit 
            v-if="updating"
        />

        <modal 
            v-show="modalActive"
            @close="modalActive = false"
            @submit="destroy"
            ok-button-text="Yes"
            cancel-button-text="No"
        >
            <template slot="header">
                Delete logbook
            </template>

            <template slot="body">
                <div class="my-4">
                    Are you sure you want to delete this logbook?
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import ucfirst from '../../helpers/ucfirst'
import dayjs from 'dayjs'

export default {
    data() {
        return {
            modalActive: false,
            updating: false        }
    },

    computed: {
        ...mapGetters({
            logbook: 'logbooks/logbook'
        }),
        
        formattedEntry () {
            return this.logbook.details_of_event
                .replace(/<p><br><\/p>/g, '')
                .replace(/<p class="ql-align-justify"><br><\/p>/g, '')
                .replace(/<p class="ql-align-right"><br><\/p>/g, '')
                .replace(/<p class="ql-align-left"><br><\/p>/g, '')
        }
    },

    methods: {
        ...mapActions({
            destroyLogbook: 'logbooks/destroy'
        }),

        dayjs,

        ucfirst,

        back () {
            this.$store.commit('logbooks/SET_LOGBOOK', {}, {root: true})

            window.events.$emit('logbooks:hide')
        },

        async destroy () {
            this.modalActive = false

            let response = await this.destroyLogbook(this.logbook.id)

            this.back()

            this.$toasted.success(response.data.message)
        }
    },

    mounted () {
        window.events.$on('logbooks:edit-cancel', () => {
            this.updating = false
        })
    }
}
</script>