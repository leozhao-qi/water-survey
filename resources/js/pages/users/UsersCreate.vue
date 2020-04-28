<template>
    <div class="w-full lg:w-9/12">   
        <h1 class="text-3xl font-bold mb-4">
            Add users
        </h1> 

        <datatable 
            :data="users"
            :columns="columns"
            :per-page="10"
            :order-keys="['lastname', 'firstname']"
            :order-key-directions="['asc', 'asc']"
            :has-text-filter="true"
            :checkable="true"
            :dropdown="roles"
            dropdown-title="Role"
		    dropdown-key="id"
		    dropdown-text-key="name"
        >
            <button
                @click.prevent="cancel"
                class="btn btn-text text-red-500 mr-2"
            >Cancel</button>

            <button 
                @click.prevent="store"
                class="btn btn-blue"
            >Submit</button>
        </datatable>
    </div>
</template>

<script>
import { map, forEach, reject, isEmpty } from 'lodash-es'

export default {
    data() {
        return {
            users: [],
            columns: [
                { field: 'firstname', title: 'First name', sortable: true },
                { field: 'lastname', title: 'Last name', sortable: true },
                { field: 'email', title: 'Email', sortable: false }
            ],
            selected: [],
            selectedRoles: [],
            roles: []
        }
    },

    methods: {
        async store () {
            let submitData = []

            let data = map(this.selected, async x => {
                let roleId = ''

                for await (const role of this.selectedRoles) {
                    if (x === role.itemId) {
                        roleId = role.dropdownItemId
                    }
                }

                if (!roleId) {
                    return Promise.resolve({})
                }

                return Promise.resolve({
                    userId: x,
                    roleId
                })
            })

            for await (const y of data) {
                submitData.push(y)
            }

            submitData = reject(submitData, isEmpty)

            if (submitData.length) {
                let response = await axios.post('/api/users/moodle', submitData)

                this.reset()

                window.events.$emit ('datatable:clear')

                this.$toasted.success(response.data.data.message)
            }
        },

        reset () {
            this.selected = []
            this.selectedRoles = []
            this.roles = []
        },

        cancel () {
            this.reset()

            window.events.$emit('datatable:cancel')
        }
    },

    async mounted () {
        let { data: users } = await axios.get('/api/users/moodle/create')
        let { data: roles } = await axios.get('/api/roles')

        this.users = users
        this.roles = roles

        window.events.$on('users:selected', users => {
            this.selected = users
        })

        window.events.$on('datatable:dropdown', items => {
            this.selectedRoles = items
        })
    }
}
</script>