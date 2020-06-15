<template>
    <div>
        <dl>
            <dt 
                class="font-bold"
                :class="{ 'text-red-500': errors.deactivated_at }"
            >
                Deactivated at:
            </dt>
            <dd class="pl-2">
                <template v-if="!editing">
                    {{ fromMySQLDateFormat(form.deactivated_at) }}
                </template>
                <template v-else>
                    <datepicker
                        input-class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.deactivated_at }"
                        v-model="form.deactivated_at"
                        format="MM/dd/yyyy"
                    ></datepicker>

                    <p
                        v-if="errors.deactivated_at"
                        v-text="errors.deactivated_at[0]"
                        class="text-red-500 text-sm"
                    ></p>
                </template>
            </dd>

            <dt 
                class="font-bold mt-1"
                :class="{ 'text-red-500': errors.reactivated_at }"
            >
                Reactivated at:
            </dt>
            <dd class="pl-2">
                <template v-if="!editing">
                    {{ fromMySQLDateFormat(form.reactivated_at) !== '' ? fromMySQLDateFormat(form.reactivated_at) : `Click 'Edit deactivation' to enter a reactivation date.` }}
                </template>
                <template v-else>
                    <datepicker
                        input-class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.reactivated_at }"
                        v-model="form.reactivated_at"
                        format="MM/dd/yyyy"
                    ></datepicker>

                    <p
                        v-if="errors.reactivated_at"
                        v-text="errors.reactivated_at[0]"
                        class="text-red-500 text-sm"
                    ></p>
                </template>
            </dd>

            <dt 
                class="font-bold mt-1"
                :class="{ 'text-red-500': errors.deactivation_rationale }"
            >
                Rationale:
            </dt>
            <dd class="pl-2">
                <template v-if="!editing">
                    {{ form.deactivation_rationale }}
                </template>
                <template v-else>
                    <textarea
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.deactivation_rationale }"
                        v-model="form.deactivation_rationale"
                        rows="4"
                    ></textarea>

                    <p
                        v-if="errors.deactivation_rationale"
                        v-text="errors.deactivation_rationale[0]"
                        class="text-red-500 text-sm"
                    ></p>
                </template>
            </dd>
        </dl>

        <div class="flex mt-4">
            <template v-if="!editing">
                <button 
                    class="text-sm btn btn-text text-red-500"
                    v-if="!confirm"
                    @click.prevent="confirm = true"
                >Delete deactivation</button>

                <template v-else>
                    <div class="p-2 rounded bg-gray-200">
                        <p>Are you sure you want to delete this deactivation?</p>

                        <button 
                            class="text-sm btn btn-text text-red-500 mr-4"
                            v-if="confirm"
                            @click.prevent="confirm = false"
                        >No</button>

                        <button 
                            class="text-sm btn btn-text text-green-500"
                            v-if="confirm"
                            @click.prevent="destroy"
                        >Yes</button>
                    </div>
                </template>

                <button 
                    class="text-sm btn btn-text ml-auto"
                    @click.prevent="editing = true"
                >Edit deactivation</button>
            </template>

            <template v-else>
                <button 
                    class="text-sm btn btn-text text-sm ml-auto"
                    @click.prevent="editing = false"
                >Cancel</button>

                <button 
                    class="text-sm btn btn-blue text-sm ml-2"
                    @click.prevent="update"
                >Update</button>
            </template>
        </div>
    </div>
</template>

<script>
import fromMySQLDateFormat from '../../helpers/fromMySQLDateFormat'
import toMySQLDateFormat from '../../helpers/toMySQLDateFormat'
import Datepicker from 'vuejs-datepicker'
import { mapGetters, mapActions } from 'vuex'

export default {
    components: {
        Datepicker
    },

    props: {
        deactivation: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            editing: false,
            confirm: false,
            form: {
                deactivated_at: fromMySQLDateFormat(this.deactivation.deactivated_at),
                reactivated_at: fromMySQLDateFormat(this.deactivation.reactivated_at),
                deactivation_rationale: this.deactivation.deactivation_rationale
            }
        }
    },

    computed: {
        ...mapGetters({
            user: 'user/user'
        })
    },

    methods: {
        ...mapActions({
            updateDeactivations: 'user/updateDeactivations',
            fetch: 'user/fetch'
        }),

        fromMySQLDateFormat: fromMySQLDateFormat,
        
        formatDate (date) {
            console.log(date)
            return fromMySQLDateFormat(date)
        },

        async update () {
            let submitData = {
                deactivated_at: toMySQLDateFormat(this.form.deactivated_at),
                deactivation_rationale: this.form.deactivation_rationale
            }

            if (this.form.reactivated_at) {
                submitData.reactivated_at = toMySQLDateFormat(this.form.reactivated_at) ?
                    toMySQLDateFormat(this.form.reactivated_at) : null
            }

            let { data } = await axios.put(`/api/deactivations/${this.deactivation.id}`, submitData)

            this.form = {
                deactivated_at: fromMySQLDateFormat(data.data.deactivation.deactivated_at),
                reactivated_at: fromMySQLDateFormat(data.data.deactivation.reactivated_at),
                deactivation_rationale: data.data.deactivation.deactivation_rationale
            }

            this.$toasted.success(data.data.message)

            await this.fetch(this.user.id)

            this.editing = false
        },

        async destroy () {
            this.confirm = false

            let { data } = await axios.delete(`/api/deactivations/${this.deactivation.id}`)

            window.events.$emit('deactivations:deleted')

            this.$toasted.success(data.data.message)
        }
    },

    mounted () {
        window.events.$on('deactivations:close', () => {
            this.editing = false
            this.confirm = false
        })
    }
}
</script>