<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Logbook entries
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

                <div class="mb-2 w-full lg:w-1/2 p-2">
                    <label 
                        for="package_filter"
                        class="block text-gray-700 font-bold mb-2"
                    >
                        Lesson packages
                    </label>

                    <div class="relative">
                        <select 
                            id="package_filter"
                            v-model="filter.package"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        >
                            <option value=""></option>

                            <option
                                :value="p.package"
                                v-for="p in packagesIndex"
                                :key="p.id"
                                v-text="p.package"
                            ></option>
                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <ul v-if="filteredLogbooks.length">
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

                            <span v-if="logbook.references">
                                <strong>Apprentice: </strong> 
                                {{ logbook.references.fullname }} | 
                            </span>

                            <span>
                                <strong>Created:</strong> {{ dayjs(logbook.created).format('MM/DD/YYYY') }}
                            </span>

                            <span v-if="logbook.updated">
                                | <strong>Updated:</strong> {{ dayjs(logbook.updated).format('MM/DD/YYYY') }}
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
                                <svg 
                                    viewBox="0 0 24 24"
                                    class="w-5 h-5" 
                                    style="fill: #718096;"
                                >
                                    <path d="M17,9H7V7H17M17,13H7V11H17M14,17H7V15H14M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z"/>
                                    <title>Lesson packages covered</title>
                                </svg>

                                <span class="mr-4 ml-1">
                                    {{ logbook.packages }}
                                </span>

                                <template v-if="logbook.files > 0">
                                    <svg 
                                        viewBox="0 0 24 24"
                                        class="w-5 h-5" 
                                        style="fill: #718096;"
                                    >
                                        <path d="M16.5,6V17.5A4,4 0 0,1 12.5,21.5A4,4 0 0,1 8.5,17.5V5A2.5,2.5 0 0,1 11,2.5A2.5,2.5 0 0,1 13.5,5V15.5A1,1 0 0,1 12.5,16.5A1,1 0 0,1 11.5,15.5V6H10V15.5A2.5,2.5 0 0,0 12.5,18A2.5,2.5 0 0,0 15,15.5V5A4,4 0 0,0 11,1A4,4 0 0,0 7,5V17.5A5.5,5.5 0 0,0 12.5,23A5.5,5.5 0 0,0 18,17.5V6H16.5Z"/>
                                        <title>Attachments</title>
                                    </svg>

                                    <span class="mr-4">
                                        {{ logbook.files }}
                                    </span>
                                </template>

                                <template v-if="logbook.comments > 0">
                                    <svg 
                                        viewBox="0 0 24 24" 
                                        class="w-5 h-5 mr-1" 
                                        style="fill: #718096;"
                                    >
                                        <path d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4A2,2 0 0,0 20,2M20,16H6L4,18V4H20"/>
                                        <title>Comments</title>
                                    </svg>

                                    <span>{{ logbook.comments }}</span>
                                </template>
                            </span>
                        </div>
                    </div>
                </li>
            </ul>

            <div class="alert alert-blue" v-else>
                There are no logbooks that match your search criteria
            </div>

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
                author: '',
                package: '',
                references: ''
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

            if (!this.filterActive) {
                this.filter.event_description = ''
                this.filter.category = ''
                this.filter.author = ''
                this.filter.package = ''
                this.filter.references = ''
            }
        },

        show (logbookId) {
            window.events.$emit('logbooks:show', logbookId)
        }
    },    

    computed: {
        ...mapGetters({
            logbooks: 'logbooks/logbooks',
            logbookCategories: 'logbookCategories/logbookCategories',
            users: 'logbooks/users',
            packagesIndex: 'logbooks/packagesIndex'
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
                    if (item.references && item.references.fullname.indexOf(this.filter.author) >= 0) {
                        return true
                    }

                    return item.user.fullname.toLowerCase().indexOf(this.filter.author.toLowerCase()) >= 0
                })
            }

            if (this.filter.package) {
                let number = this.filter.package.split('-')[0].trim()

                filtered = filter(filtered, item => {
                    return item.packages.indexOf(number) >= 0
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

        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.get('package') !== '') {
            this.filter.package = urlParams.get('package')
        }

        if (urlParams.get('user') !== '') {
            this.filter.author = urlParams.get('user')
            this.filter.references = urlParams.get('user')
        }
    }
}
</script>