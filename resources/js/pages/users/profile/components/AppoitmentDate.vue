<template>
    <div class="w-full">
        <h2 class="text-2xl">
            Appointment date
        </h2>

        <template v-if="!user.appointment_date && !changing">
            <div class="flex w-full my-2 items-center">
                <span
                    class="mr-2"
                >
                    No appointment date set.
                </span>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="changing = true"
                    v-if="hasRole(['administrator'])"
                >
                    {{ !user.appointment_date ? 'Add' : 'Change date' }}
                </button>
            </div>
        </template>

        <template v-if="user.appointment_date && !changing">
            <div class="flex w-full my-2 items-center">
                <span
                    class="mr-2"
                >
                    {{ fromMySQLDateFormat(user.appointment_date) }}
                </span>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="changing = true"
                    v-if="hasRole(['administrator'])"
                >
                    {{ !user.appointment_date ? 'Save date' : 'Change date' }}
                </button>
            </div>
        </template>

        <template v-if="changing">
            <datepicker
                input-class="shadow appearance-none border rounded w-full lg:w-1/2 mt-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline my-2"
                :class="{ 'border-red-500': errors.appointment_date }"
                v-model="appointment_date"
                format="MM/dd/yyyy"
            ></datepicker>

            <p
                v-if="errors.appointment_date"
                v-text="errors.appointment_date[0]"
                class="text-red-500 text-sm mb-2"
            ></p>

            <div
                class="w-full mb-4"
            >
                <button 
                    class="btn btn-blue text-sm"
                    @click.prevent="update"
                >
                    {{ !user.appointment_date ? 'Save date' : 'Change date' }}
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    Cancel
                </button>
            </div>
        </template>
    </div>
</template>

<script>
import fromMySQLDateFormat from '../../../../helpers/fromMySQLDateFormat'
import toMySQLDateFormat from '../../../../helpers/toMySQLDateFormat'
import Datepicker from 'vuejs-datepicker'
import { mapActions, mapGetters } from 'vuex'

export default {
    components: {
        Datepicker
    },

    data() {
        return {
            changing: false,
            appointment_date: ''
        }
    },

    computed: {
        ...mapGetters({
            user: 'user/user'
        })
    },    

    watch: {
        user: {
            deep: true,

            handler () {
                this.appointment_date = this.user.appointment_date
            }
        }
    },

    methods: {
        ...mapActions({
            fetch: 'user/fetch'
        }),

        fromMySQLDateFormat: fromMySQLDateFormat,

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/users/${this.user.id}/appointment`, {
                appointment_date: this.appointment_date ? toMySQLDateFormat(this.appointment_date) : null
            })

            await this.fetch(this.user.id)

            this.cancel()

            this.$toasted.success(data.data.message)
        },

        cancel () {
            this.changing = false

            this.appointment_date = ''
        }
    },
}
</script>