<template>
    <div class="w-full">
        <h2 class="text-2xl">Status</h2>

        <div class="flex items-center">
            <span>{{ user.active  ? 'Active' : 'Inactive' }}</span>

            <button
                class="btn btn-text text-sm ml-2"
                v-if="user.active && hasRole(['administrator', 'manager', 'head_of_operations'])"
                @click.prevent="modalCreateDeactivation = true"
            >Deactivate</button>
        </div>

        <dl
            v-if="currentlyDeactivated"
            class="w-full block mt-3"
        >
            <dt class="font-bold">Deactivated at:</dt>
            <dd class="pl-2">{{ dayjs(currentDeactivation.deactivated_at).format('MM/DD/YYYY') }}</dd>

            <dt class="font-bold">Deactivation rationale:</dt>
            <dd class="pl-2">{{ currentDeactivation.deactivation_rationale }}</dd>
        </dl>

        <template v-if="user.deactivations && user.deactivations.length && hasRole(['administrator', 'manager', 'head_of_operations'])">
            <button 
                class="btn btn-blue text-sm mt-3"
                @click.prevent="modalShowDeactivations = true"
            >
                Manage deactivations
            </button>
        </template>

        <modal 
            v-show="modalCreateDeactivation"
            @close="close"
            @submit="store"
        >
            <template slot="header">
                Deactivate {{ user.firstname }} {{ user.lastname }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <create-deactivation />
                </div>
            </template>
        </modal>

        <modal 
            v-show="modalShowDeactivations"
            @close="close"
            :has-ok-button="false"
            cancel-button-text="Close"
        >
            <template slot="header">
                Manage deactivations for {{ user.firstname }} {{ user.lastname }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <deactivations />
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import toMySQLDateFormat from '../../../../helpers/toMySQLDateFormat'
import { find, isEmpty } from 'lodash-es'
import dayjs from 'dayjs'

export default {
    data() {
        return {
            modalShowDeactivations: false,
            modalCreateDeactivation: false,
            form: {}
        }
    },

    computed: {
        ...mapGetters({
            user: 'user/user'
        }),

        currentlyDeactivated () {
            return !isEmpty(this.currentDeactivation)
        },

        currentDeactivation () {
            return find(
                this.user.deactivations, 
                deactivation => deactivation.reactivated_at === null
            )
        }
    },

    methods: {
        ...mapActions({
            fetch: 'user/fetch'
        }),

        dayjs,

        close () {
            this.modalShowDeactivations = false
            this.modalCreateDeactivation = false
            this.form = {}

            window.events.$emit('deactivations:close')
            window.events.$emit('deactivations:create-clear')
        },

        async store () {
            let submitData = {
                deactivated_at: toMySQLDateFormat(this.form.deactivated_at),
                deactivation_rationale: this.form.deactivation_rationale
            }

            let { data } = await axios.post(`/api/deactivations/${this.user.id}`, submitData)

            this.$toasted.success(data.data.message)

            await this.fetch(this.user.id)
            
            window.events.$emit('deactivations:create-clear')

            this.form = {}

            this.modalCreateDeactivation = false
        }
    },

    mounted () {
        window.events.$on('deactivations:create', form => {
            this.form = form
        })
    }
}
</script>