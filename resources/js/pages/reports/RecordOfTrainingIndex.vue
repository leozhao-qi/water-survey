<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Apprentices
        </h1> 

        <datatable 
            v-if="typeof users !== 'undefined' && users.length"
            :data="users"
            :columns="columns"
            :per-page="10"
            :order-keys="['lastname', 'firstname']"
            :order-key-directions="['asc', 'asc']"
            :has-text-filter="true"
            :has-action="true"
            action-text="View SoT"
            :action-link="`${urlBase}/reports/sot`"
            action-id="id"
            :has-event="true"
            event-text="View RoT"
            event="rot:view"
        />

        <modal 
            v-show="modalRoT"
            @close="close"
            @submit="download"
        >
            <template 
                slot="header"
                v-if="user !== null"
            >
                Record of Traning for {{ user.firstname }} {{ user.lastname }}
            </template>

            <template 
                slot="body"
                v-if="user !== null"
            >
                <div class="my-4">
                   <datatable 
                        :data="user.packages"
                        :columns="packageColumns"
                        :per-page="10"
                        :order-keys="['package']"
                        :order-key-directions="['asc']"
                        :has-text-filter="true"
                        :checkable="true"
                    >
                        <button
                            @click.prevent="selectAll"
                            class="btn btn-text text-red-500 mr-2"
                        >Select all</button>
                   </datatable>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { map, join } from 'lodash-es'

export default {
    data() {
        return {
            columns: [
                { field: 'firstname', title: 'First name', sortable: true },
                { field: 'lastname', title: 'Last name', sortable: true }
            ],
            packageColumns: [
                { field: 'package', title: 'Package', sortable: true }
            ],
            users: [],
            modalRoT: false,
            user: null,
            selected: [],
            idArr: [],
            selectedJoin: ''
        }
    },

    methods: {
        close () {
            this.modalRoT = false

            this.user = null

            this.selected = []

            this.idArr = []

            window.events.$emit('datatable:clear')
        },

        async download () {
            window.location.href = `${this.urlBase}/api/reports/rot/download?packages=${this.selectedJoin}&user=${this.user.id}`

            this.close()
        },

        selectAll () {
            window.events.$emit('datatable:select-all', this.idArr)
        }
    },

    async mounted () {
        let { data: users } = await axios.get(`${this.urlBase}/api/reports/rot`)

        this.users = users.data

        window.events.$on('rot:view', user => {
            this.modalRoT = true

            this.user = user

            this.idArr = map(this.user.packages, p => {
                return p.id
            })
        })

        window.events.$on('users:selected', packages => {
            this.selected = packages

            this.selectedJoin = join(this.selected, ',')
        })
    }
}
</script>