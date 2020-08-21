<template>
    <div class="w-full">
        <nav 
            class="flex justify-end w-full items-center"
            @click.prevent="cancel"
        >
            <a 
                href=""
                class="btn btn-text"
            >Cancel</a>
        </nav>

        <h1 class="text-3xl font-bold mb-4">
            New logbook entry
        </h1> 

        <form @submit.prevent="store">
            <div
                class="w-full mb-4"
            >
                <label 
                    for="lesson_version_id"
                    class="block text-gray-700 font-bold mb-2"
                    :class="{ 'text-red-500': errors.logbook_category_id }"
                >
                    Category
                </label>

                <div class="relative w-full lg:w-1/2">
                    <select 
                        id="logbook_category_id"
                        v-model="form.logbook_category_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.logbook_category_id }"
                    >
                        <option value=""></option>

                        <option
                            :value="logbookCategory.id"
                            v-for="logbookCategory in logbookCategories"
                            :key="logbookCategory.id"
                            v-text="logbookCategory.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.logbook_category_id"
                    v-text="errors.logbook_category_id[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div 
                class="mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold" 
                    :class="{ 'text-red-500': errors.created }"
                    for="created"
                >
                    Date
                </label>

                <datepicker
                    input-class="shadow appearance-none border rounded w-full lg:w-1/2 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline my-2"
                    :class="{ 'border-red-500': errors.created }"
                    v-model="form.created"
                    format="MM/dd/yyyy"
                ></datepicker>

                <p
                    v-if="errors.created"
                    v-text="errors.created[0]"
                    class="text-red-500 text-sm mb-2"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="flex items-center text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.event_description }"
                    for="event_description"
                >
                    Event description 
                    
                    <span 
                        class="rounded-full h-5 w-5 ml-2 text-xs flex items-center justify-center bg-yellow-500 cursor-pointer"
                        v-tooltip="{
                            content: 'Provide a brief description of the event indicating if a Theory Training Event or a Field trip (Example, Field trip to Complete Annual Levels)',
                            placement: 'bottom-center',
                            classes: ['info', 'text-sm'],
                            targetClasses: ['it-has-a-tooltip'],
                            offset: 10,
                            delay: {
                                show: 500,
                                hide: 300,
                            }
                        }"
                    >?</span>
                </label>

                <input 
                    type="text" 
                    v-model="form.event_description"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="event_description"
                    :class="{ 'border-red-500': errors.event_description }"
                >

                <p
                    v-if="errors.event_description"
                    v-text="errors.event_description[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                v-if="apprentices.length"
                class="w-full mb-4"
            >
                <label 
                    for="references"
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.references }"
                >
                    Apprentice
                </label>

                <div class="relative w-full lg:w-1/2">
                    <select 
                        id="references"
                        v-model="form.references"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.references }"
                    >
                        <option value=""></option>

                        <option
                            :value="apprentice.id"
                            v-for="apprentice in apprentices"
                            :key="apprentice.id"
                            v-text="`${apprentice.fullname}`"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <div class="w-full mb-4">
                <label 
                    for="packages"
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.packages }"
                >
                    Lesson packages covered
                </label>

                <div class="relative w-full lg:w-1/2">
                    <select 
                        id="logbook_category_id"
                        v-model="selectedPackage"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.packages }"
                    >
                        <option value=""></option>

                        <option
                            :value="p.id"
                            v-for="p in orderBy(availablePackages, ['formatNumber'], ['asc'])"
                            :key="p.id"
                            v-text="`${p.formatNumber} v${p.versionNumber} - ${p.lessonName}`"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.packages"
                    v-text="errors.packages[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div class="w-full mb-4" v-if="selectedPackages.length">
                <p class="mb-2">
                    <strong class="text-gray-700">Selected packages</strong>
                </p>

                <selected-package-items 
                    :packages="selectedPackages"
                />
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="flex items-center text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.details_of_event }"
                    for="details_of_event"
                >
                    Details of event

                    <span 
                        class="rounded-full h-5 w-5 ml-2 text-xs flex items-center justify-center bg-yellow-500 cursor-pointer"
                        v-tooltip="{
                            content: 'Provide more specific details , include the Who if anyone assisted or was providing training.  Then an explanation of the event and what was accomplished or practiced',
                            placement: 'bottom-center',
                            classes: ['info', 'text-sm'],
                            targetClasses: ['it-has-a-tooltip'],
                            offset: 10,
                            delay: {
                                show: 500,
                                hide: 300,
                            }
                        }"
                    >?</span>
                </label>

                <vue-editor 
                    v-model="form.details_of_event"
                ></vue-editor>

                <p
                    v-if="errors.details_of_event"
                    v-text="errors.details_of_event[0]"
                    class="text-red-500 text-sm mt-2"
                ></p>
            </div>

            <div class="my-4">
                <template v-if="addFiles">
                    <file-upload />

                    <button 
                        class="btn btn-text text-blue-500" 
                        @click.prevent="addFiles = false"
                    >
                        Cancel
                    </button>

                    <hr class="block w-full mt-6 pt-6 border-t border-gray-200">
                </template>

                <template v-else>
                    <button 
                        class="btn btn-text text-blue-500" 
                        @click.prevent="addFiles = true"
                    >
                        + Add files
                    </button>
                </template>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Create entry
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import Datepicker from 'vuejs-datepicker'
import toMySQLDateFormat from '../../helpers/toMySQLDateFormat'
import { VueEditor, Quill } from 'vue2-editor'
import { filter, includes, orderBy } from 'lodash-es'

export default {
    components: {
        Datepicker,
        VueEditor
    },

    data() {
        return {
            form: {
                logbook_category_id: null,
                created: '',
                event_description: '',
                details_of_event: '',
                files: [],
                packages: [],
                references: null
            },
            addFiles: false,
            selectedPackage: null      
        }
    },

    computed: {
        ...mapGetters({
            logbookCategories: 'logbookCategories/logbookCategories',
            packages: 'logbooks/packages',
            apprentices: 'logbooks/apprentices'
        }),
        
        availablePackages () {
            return filter(this.packages, p => {
                return !includes(this.form.packages, p.id)
            })
        },

        selectedPackages () {
            return filter(this.packages, p => {
                return includes(this.form.packages, p.id)
            })
        }
    },

    watch: {
        selectedPackage () {
            if (this.selectedPackage) {
                this.form.packages.push(this.selectedPackage)
            }
        },

        'form.references' () {
            this.fetchLessonPackages(this.form.references)
        }
    },

    methods: {
        ...mapActions({
            fetchLogbookCategories: 'logbookCategories/fetch',
            fetchLessonPackages: 'logbooks/fetchLessonPackages',
            storeEntry: 'logbooks/store'
        }),
        
        filter,

        orderBy,

        cancel () {
            window.events.$emit('logbooks:create-cancel')

            this.form.logbook_category_id = null
            this.form.created = ''
            this.form.event_description = ''
            this.form.details_of_event = ''
            this.form.files = []
            this.form.packages = []
            this.form.references = null
        },

        async store () {
            this.form.created = toMySQLDateFormat(this.form.created)

            let response = await this.storeEntry(this.form)

            this.cancel()

            this.$toasted.success(response.data.message)
        }
    },

    async mounted () {
        await this.fetchLogbookCategories()

        if (this.authUser.role === 'apprentice') {
            await this.fetchLessonPackages(this.authUser.id)
        }

        window.events.$on('upload:finished', fileObject => this.form.files.push(fileObject))

        window.events.$on('logbook:remove-package', packageId => {
            this.form.packages = filter(this.form.packages, p => {
                return p !== packageId
            })
        })
    }
}
</script>