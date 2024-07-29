<template>
    <div class="w-full lg:w-9/12">
        <h1 class="text-3xl font-bold mb-4">
            Add users
        </h1>

        <div class="flex justify-center w-full mb-6 pb-6 border-b border-gray-200">
            <div>
                <label
                    for="roles"
                    class="block text-gray-700 font-bold mb-2"
                >
                    Register user as...
                </label>
                <div class="relative">
                    <select
                        id="roles"
                        v-model="role"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    >
                        <option value=""></option>

                        <option
                            :value="r.name"
                            v-for="r in roles"
                            :key="r.id"
                            v-text="ucfirst(r.name)"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <form
            @submit.prevent
        >
            <div
                class="w-full mb-4"
            >
                <label
                    class="block text-gray-700 font-bold mb-2"
                    :class="{ 'text-red-500': errors.full_name }"
                    for="name"
                >
                    Full name
                </label>

                <input
                    type="text"
                    v-model="form.full_name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    :class="{ 'border-red-500': errors.full_name }"
                    id="name"
                >

                <p
                    v-if="errors.full_name"
                    v-text="errors.full_name[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label
                    class="block text-gray-700 font-bold mb-2"
                    :class="{ 'text-red-500': errors.email }"
                    for="email"
                >
                    ec.gc.ca email
                </label>

                <input
                    type="email"
                    v-model="form.email"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="email"
                    :class="{ 'border-red-500': errors.email }"
                >

                <p
                    v-if="errors.email"
                    v-text="errors.email[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>
            <div
                class="w-full"
            >
                <button
                    @click.prevent="cancel"
                    class="btn btn-text text-red-500 mr-2"
                >Cancel</button>

                <button
                    @click.prevent="store"
                    class="btn btn-blue"
                >Create account</button>
            </div>
            <p class="block text-gray-700 mt-2" >A password will be sent to the email address above.</p>
        </form>
    </div>
</template>`

<script>
import { map, forEach, reject, isEmpty } from 'lodash-es'
import ucfirst from '../../helpers/ucfirst'

export default {
    data() {
        return {
            roles: [],
            role: '',
            form: {
                full_name: '',
                email: ''
            }
        }
    },

    methods: {
        ucfirst,

        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/users/create`, {
                role: this.role,
                user: this.form
            });

            this.reset();
            window.events.$emit('datatable:clear');

            if (data.data.type === 'failure') {
                this.$toasted.error(data.data.message);
            } else {
                this.$toasted.success(data.data.message);
            }
        },

        reset () {
            this.form = {
                full_name: '',
                email: ''
            }
            this.roles = []
            this.role = ''
        },

        cancel () {
            this.reset()
            window.events.$emit('datatable:cancel')
        }
    },

    async mounted () {
        let { data: roles } = await axios.get(`${this.urlBase}/api/roles`)

        this.form = {
            full_name: '',
            email: ''
        }
        this.roles = roles
    }
}
</script>
