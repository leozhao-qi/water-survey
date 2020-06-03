<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Logbooks
        </h1> 

        <template v-if="typeof logbooks !== 'undefined' && logbooks.length">
            <div class="mb-6">
                <button 
                    class="btn btn-text text-blue-500 no-underline text-lg"
                    @click.prevent="toggleFilters"
                >
                    <span class="mr-1">{{ filterSymbol }}</span><span>Filters</span>
                </button>
            </div>

            <div
                class="mb-6 flex flex-wrap"
                v-if="filterActive"
            >
                <div class="mb-2 w-full lg:w-1/2 p-2">
                    <label 
                        for="event_description_filter"
                        class="block text-gray-700 font-bold mb-2"
                    >
                        Event description
                    </label>

                    <input 
                        type="text" 
                        v-model="filter.event_description"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                        id="event_description_filter"
                    >
                </div>

                <div class="mb-2 w-full lg:w-1/2 p-2">
                    <label 
                        for="category_filter"
                        class="block text-gray-700 font-bold mb-2"
                    >
                        Category
                    </label>

                    <div class="relative">
                        <select 
                            id="category_filter"
                            v-model="filter.category"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                            <option value=""></option>

                            <option
                                :value="logbookCategory.name"
                                v-for="logbookCategory in logbookCategories"
                                :key="logbookCategory.id"
                                v-text="logbookCategory.name"
                            ></option>
                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>

                <div class="mb-2 w-full lg:w-1/2 p-2">
                    <label 
                        for="author_filter"
                        class="block text-gray-700 font-bold mb-2"
                    >
                        Authors
                    </label>

                    <div class="relative">
                        <select 
                            id="author_filter"
                            v-model="filter.author"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                            <option value=""></option>

                            <option
                                :value="`${user.firstname} ${user.lastname}`"
                                v-for="user in users"
                                :key="user.id"
                                v-text="`${user.firstname} ${user.lastname}`"
                            ></option>
                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <ul>
                <li
                    v-for="logbook in filteredLogbooks"
                    :key="logbook.id"
                    class="mb-6"
                >
                    <div class="border-2 border-gray-200 rounded-lg p-6">
                        <h2 class="tracking-widest text-xs title-font font-medium uppercase text-gray-500 mb-1">
                            {{ logbook.category.name }}
                        </h2>

                        <h1 class="title-font text-lg font-medium text-gray-900 mb-1">
                            {{ logbook.event_description }}
                        </h1>

                        <p class="text-sm text-gray-500 mb-3">
                            {{ logbook.user.firstname }} {{ logbook.user.lastname }} | 

                            <span>
                                <strong>Created:</strong> {{ dayjs(logbook.created).format('MM/DD/YYYY') }}
                            </span>
                        </p>

                        <p class="leading-relaxed my-3">
                            {{ logbook.details_of_event_truncated }}
                        </p>

                        <div class="flex items-center flex-wrap ">
                            <a 
                                class="text-blue-500 inline-flex items-center md:mb-2 lg:mb-0 cursor-pointer"
                                @click.prevent="show(logbook.id)"
                            >
                                Read more
                                <svg class="w-4 h-4 ml-2" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5l7 7-7 7"></path>
                                </svg>
                            </a>

                            <span class="text-gray-600 inline-flex items-center leading-none text-sm ml-auto">
                                <svg class="w-4 h-4 mr-1" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path>
                                </svg>6
                            </span>
                        </div>
                    </div>
                </li>
            </ul>

            <paginate
                :page-count="pages"
                :click-handler="paginate"
                :container-class="'paginate'"
            />
        </template>
        
        <div v-else class="alert alert-blue">
            There are no logbooks at this time
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import Paginate from 'vuejs-paginate'
import { filter } from 'lodash-es'
import dayjs from 'dayjs'
// import { orderBy, filter, forEach, find, map } from 'lodash-es'

export default {
    components: {
        Paginate
    },

    data() {
        return {
            currentPage: 1,
            perPage: 10,
            pages: 1,
            filterActive: false,
            filter: {
                event_description: '',
                category: '',
                author: ''
            }
        }
    },

    methods: {
        ...mapActions({
            fetch: 'logbooks/fetch'
        }),

        dayjs,

        paginate (page) {
            this.currentPage = page
        },

        toggleFilters () {
            this.filterActive = !this.filterActive

            this.filter.event_description = ''
            this.filter.category = ''
            this.filter.author = ''
        },

        show (logbookId) {
            window.events.$emit('logbooks:show', logbookId)
        }
    },    

    computed: {
        ...mapGetters({
            logbooks: 'logbooks/logbooks',
            logbookCategories: 'logbookCategories/logbookCategories',
            users: 'logbooks/users'
        }),

        filteredLogbooks () {  
            let filtered = this.logbooks

            if (this.filter.event_description) {
                filtered = filter(filtered, item => {
                    return item.event_description.toLowerCase().indexOf(this.filter.event_description.toLowerCase()) >= 0
                })
            }

            if (this.filter.category) {
                filtered = filter(filtered, item => {
                    return item.category.name.toLowerCase().indexOf(this.filter.category.toLowerCase()) >= 0
                })
            }

            if (this.filter.author) {
                filtered = filter(filtered, item => {
                    return item.user.fullname.toLowerCase().indexOf(this.filter.author.toLowerCase()) >= 0
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
        },

        filterSymbol () {
            return this.filterActive ? '-' : '+'
        }
    },

    async mounted () {
        await this.fetch()
    }
}
</script>