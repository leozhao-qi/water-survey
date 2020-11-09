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
                    <div
                        class="w-full lg:w-1/2 mb-4"
                    >
                        <label 
                            for="report_type"
                            class="block text-gray-700 font-bold mb-2"
                        >
                            Select report type
                        </label>

                        <div class="relative">
                            <select 
                                id="report_type"
                                v-model="reportType"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            >
                                <option value=""></option>

                                <option value="eg_3_4">EG03 - EG04 Report</option>
                                <option value="eg_4_5">EG04 - EG05 Report</option>
                                <option value="custom">Custom Report</option>
                            </select>

                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>

                    <template v-if="reportType === 'custom'">
                        <datatable 
                            :data="user.packages"
                            :columns="packageColumns"
                            :per-page="10"
                            :order-keys="['package']"
                            :order-key-directions="['asc']"
                            :has-text-filter="true"
                            :checkable="true"
                        />
                    </template>
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
            selectedJoin: '',
            reportType: ''
        }
    },

    methods: {
        close () {
            this.modalRoT = false

            this.user = null

            this.selected = []

            this.reportType = ''

            this.idArr = []

            window.events.$emit('datatable:clear')
        },

        async download () {
            if (this.reportType === 'custom') {
                window.location.href = `${this.urlBase}/api/reports/rot/download?packages=${this.selectedJoin}&user=${this.user.id}`
            } else {
                window.location.href = `${this.urlBase}/api/reports/rot/download?type=${this.reportType}&user=${this.user.id}`
            }

            this.close()
        },

        selectAll () {
            window.events.$emit('datatable:select-all', this.idArr)
        }
    },

    async mounted () {
        let { data: users } = await axios.get(`${this.urlBase}/api/reports/rot`)

        this.users = users

        window.events.$on('rot:view', async user => {
            this.modalRoT = true

            this.user = user

            let { data: packages } = await axios.get(`${this.urlBase}/api/users/${this.user.id}/packages`)

            this.user.packages = packages.data

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