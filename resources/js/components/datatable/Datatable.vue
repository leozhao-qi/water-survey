<template>
    <div class="w-full">
        <div class="flex w-full mb-4">
            <input 
                type="text" 
                v-model="textFilter"
                class="block w-1/2 mr-auto p-1 border border-grey-200 rounded"
                placeholder="Search..."
            >

            <slot></slot>
        </div>

        <table class="w-full">
            <thead>
                <tr class="border-b-2 border-gray-300">
                    <th 
                        v-if="checkable"
                        class="p-1"
                    ></th>

                    <th
                        v-for="column in columns"
                        :key="column.field"
                        v-text="column.title"
                        class="text-left p-1"
                    ></th>

                    <th 
                        v-if="dropdown.length"
                        class="text-left p-1"
                        v-text="dropdownTitle"
                    ></th>

                    <th 
                        v-if="hasAction || hasEvent"
                        class="p-1"
                    ></th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="item in filteredData"
                    :key="item.id"
                    class="border-b border-gray-300"
                >
                    <td v-if="checkable" class="p-1">
                        <input 
                            type="checkbox" 
                            :value="item.id"
                            v-model="selected"
                        >
                    </td>
                    
                    <td
                        v-for="column in columns"
                        :key="column.field"
                        v-text="item[column.field]"
                        class="p-1"
                    ></td>

                    <td v-if="dropdown.length" class="p-1">
                        <select 
                            @change="pushDropdown"
                            v-model="selectedDropdownVModel"
                            class="block w-full p-1 border border-grey-200 hover:border-grey-300 rounded"
                        >
                            <option :value="item.id"></option>

                            <option
                                v-for="dropdownItem in dropdown"
                                :key="dropdownItem[dropdownKey]"
                                :value="`${item.id}:${dropdownItem[dropdownKey]}`"
                                v-text="dropdownItem[dropdownTextKey]"
                            ></option>
                        </select>
                    </td>

                    <td v-if="hasAction || hasEvent" class="p-1">
                        <template v-if="hasAction">
                            <a 
                                :href="`${actionLink}/${item[actionId]}`"
                                class="btn btn-text text-sm"
                            >
                                {{ actionText }}
                            </a>
                        </template>
                        
                        <template v-if="hasEvent">
                            <button 
                                @click.prevent="emitEvent(event, item)"
                                class="btn btn-text text-sm text-blue-500"
                            >
                                {{ eventText }}
                            </button>
                        </template>
                    </td>
                </tr>
            </tbody>
        </table>

        <paginate
            :page-count="pages"
            :click-handler="paginate"
            :container-class="'paginate'"
        />
    </div>
</template>

<script>
import paginate from 'vuejs-paginate'
import { orderBy, filter, forEach, find, map } from 'lodash-es'

export default {
    components: {
        paginate
    },

    props: {
        data: {
            type: Array,
            required: true
        },
        columns: {
            type: Array,
            required: true
        },
        perPage: {
            type: Number,
            required: false,
            default: 10
        },
        orderKeys: {
            type: Array,
            required: false,
            default: () => []
        },
        orderKeyDirections: {
            type: Array,
            required: false,
            default: () => []
        },
        hasTextFilter: {
            type: Boolean,
            required: false,
            default: false
        },
        checkable: {
            type: Boolean,
            required: false,
            default: false
        },
        dropdown: {
            type: Array,
            required: false,
            default: () => []
        },
        dropdownTitle: {
            type: String,
            required: false,
            default: ''
        },
        dropdownKey: {
            type: String,
            required: false,
            default: ''
        },
        dropdownTextKey: {
            type: String,
            required: false,
            default: ''
        },
        selectedValues: {
            type: Array,
            required: false,
            default: () => []
        },
        hasAction: {
            type: Boolean,
            required: false,
            default: false
        },
        actionText: {
            type: String,
            required: false,
            default: ''
        },
        actionLink: {
            type: String,
            required: false,
            default: '#'
        },
        actionId: {
            type: String,
            required: false,
            default: 'null'
        },
        event: {
            type: String,
            required: false,
            default: ''
        },
        eventText: {
            type: String,
            required: false,
            default: ''
        },
        hasEvent: {
            type: Boolean,
            required: false,
            default: false
        }
    },

    data() {
        return {
            currentPage: 1,
            textFilter: '',
            pages: 1,
            selected: [],
            selectedDropdown: [],
            selectedDropdownVModel: []
        }
    },

    computed: {
        filteredData () {  
            let filtered = this.data

            if (this.orderKeys.length) {
                filtered = orderBy(
                    filtered, this.orderKeys, this.orderKeyDirections
                )
            }

            if (this.textFilter) {
                filtered = filter(filtered, item => {
                    let isIndexOf = false

                    forEach(this.columns, column => {
                        if (item[column.field].toLowerCase().indexOf(this.textFilter.toLowerCase()) >= 0) {
                            isIndexOf = true
                        }
                    })

                    return isIndexOf
                })
            }

            this.pages = Math.ceil(filtered.length / this.perPage)

            return filter(filtered, (item, index) => {
                return index >= this.startIndex && index < this.endIndex
            })
        },

        startIndex () {
            return (this.currentPage - 1) * this.perPage
        },

        endIndex () {
            return this.startIndex + this.perPage
        }
    },

    watch: {
        selected () {
            window.events.$emit('users:selected', this.selected)
        }
    },

    methods: {
        paginate (page) {
            this.currentPage = page
        },

        async pushDropdown (e) {
            let arr = e.target.value.split(':')

            if (arr.length === 1) {
                this.selectedDropdown = await filter(this.selectedDropdown, 
                    x => x.itemId !== parseInt(arr[0])
                )

                return
            }

            await this.selectedDropdown.push({
                itemId: parseInt(arr[0]),
                dropdownItemId: parseInt(arr[1])
            })

            window.events.$emit('datatable:dropdown', this.selectedDropdown)
        },

        reset () {
            this.selected = []
            this.selectedDropdown = []
            this.currentPage = 1
            this.textFilter = ''
            this.pages = 1
        },

        emitEvent(event, item) {
            window.events.$emit(event, item)
        }
    },

    mounted () {
        if (this.selectedValues.length) {
            this.selected = this.selectedValues
        }

        window.events.$on('datatable:clear', () => {
            this.reset()
        })

        window.events.$on('datatable:cancel', () => {
            this.reset()
        })
    }
}
</script>