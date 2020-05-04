<template>
    <div class="w-full">
        <h2 class="text-2xl">
            Role
        </h2>

        <template v-if="!changing">
            <div class="flex w-full my-2 items-center">
                <span
                    class="mr-2"
                    v-if="user.role"
                >
                    {{ ucfirst(user.role) }}
                </span>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="changing = true"
                >
                    Change role
                </button>
            </div>
        </template>

        <template v-if="changing">
            <div class="relative w-full lg:w-1/3 mb-2">
                <select 
                    id="roles"
                    v-model="role"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    :class="{ 'border-red-500': errors.role }"
                >
                    <option value=""></option>

                    <option
                        :value="r.name"
                        v-for="r in roles"
                        :key="r.id"
                        v-text="`${ucfirst(r.name)}`"
                    ></option>
                </select>

                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
            
            <p
                v-if="errors.roles"
                v-text="errors.roles[0]"
                class="text-red-500 text-sm mb-2"
            ></p>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                    @click.prevent="update"
                >
                    Change role
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
import { mapGetters, mapActions } from 'vuex'
import ucfirst from '../../../../helpers/ucfirst'

export default {
    data() {
        return {
            changing: false,
            roles: [],
            role: ''
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
                this.role = this.user.role
            }
        }
    },

    methods: {
        ...mapActions({
            fetch: 'user/fetch'
        }),

        ucfirst,

        async update () {
            let { data } = await axios.put(`/api/users/${this.user.id}/role`, {
                role: this.role
            })

            await this.fetch(this.user.id)

            this.cancel()

            this.$toasted.success(data.data.message)
        },

        cancel () {
            this.changing = false

            this.role = ''
        }
    },

    async mounted () {
        let { data: roles } = await axios.get('/api/roles')

        this.roles = roles
    },
}
</script>