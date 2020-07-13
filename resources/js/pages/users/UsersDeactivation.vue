<template>
    <div class="w-full lg:w-9/12">   
        <h1 class="text-3xl font-bold mb-4">
            Activate users
        </h1> 

        <datatable 
            v-if="users.length"
            :data="users"
            :columns="columns"
            :per-page="10"
            :order-keys="['lastname', 'firstname']"
            :order-key-directions="['asc', 'asc']"
            :has-text-filter="true"
            :has-event="true"
            event-text="Activate"
            event="user:activate"
        >
            <button
                @click.prevent="cancel"
                class="btn btn-text text-red-500 mr-2"
            >Cancel</button>
        </datatable>

        <div
            v-else
            class="alert alert-blue"
        >
            There are currently no inactive users.
        </div>

        <modal 
            v-show="modalActive"
            @close="modalActive = false"
            @submit="update"
        >
            <template slot="header">
                Activate user
            </template>

            <template slot="body">
                <div class="my-4">
                    <label 
                        class="block text-gray-700 font-bold mb-2" 
                        :class="{ 'text-red-500': errors.reactivated_at }"
                        for="activateion-date"
                    >
                        Re-activation date
                    </label>

                    <datepicker
                        input-class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.reactivated_at }"
                        v-model="form.date"
                        format="MM/dd/yyyy"
                        id="activateion-date"
                    ></datepicker>

                    <p
                        v-if="errors.reactivated_at"
                        v-text="errors.reactivated_at[0]"
                        class="text-red-500 text-sm"
                    ></p>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
import toMySQLDateFormat from '../../helpers/toMySQLDateFormat'
import { isEmpty } from 'lodash-es'

export default {
    components: {
        Datepicker
    },

    data() {
        return {
            users: [],
            user: {},
            modalActive: false,
            columns: [
                { field: 'firstname', title: 'First name', sortable: true },
                { field: 'lastname', title: 'Last name', sortable: true },
                { field: 'email', title: 'Email', sortable: false },
            ],
            form: {
                date: ''
            }
        }
    },

    methods: {
        cancel () {
            window.events.$emit('datatable:cancel')
        },

        async update () {
            let reactivated_at = toMySQLDateFormat(this.form.date)
            
            let { data } = await axios.put(`${this.urlBase}/api/deactivations/${this.user.id}/activate`, { reactivated_at })

            if (isEmpty(this.errors)) {
                this.modalActive = false

                this.$toasted.success(data.data.message)

                this.form.date = ''

                window.events.$emit('user:activated')
            }
        }
    },

    async mounted () {
        let { data: users } = await axios.get(`${this.urlBase}/api/users/deactivations`)

        this.users = users

        window.events.$on('user:activate', user => {
            this.modalActive = true,

            this.user = user
        })
    }
}
</script>