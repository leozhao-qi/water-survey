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
            Edit logbook entry
        </h1> 

        <form @submit.prevent="update">
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
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.event_description }"
                    for="event_description"
                >
                    Event description
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
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.details_of_event }"
                    for="details_of_event"
                >
                    Details of event
                </label>

                <vue-editor 
                    v-model="form.details_of_event"
                    :editorToolbar="customToolbar"
                ></vue-editor>

                <p
                    v-if="errors.details_of_event"
                    v-text="errors.details_of_event[0]"
                    class="text-red-500 text-sm mt-2"
                ></p>
            </div>

            <div 
                class="my-4"
            >
                <div class="flex items-center mb-2">
                    <h2 class="text-2xl">
                        Files
                    </h2>

                    <button 
                        class="btn btn-text text-sm text-blue-500"
                        v-if="!addFiles"
                        @click.prevent="addFiles = true"
                    >
                        Add files
                    </button>
                </div>

                <logbook-files 
                    v-if="logbook.files.length"
                />

                <hr class="block w-full mt-6 pt-6 border-t border-gray-200" v-if="!addFiles">
            </div>

            <div class="my-4" v-if="addFiles">
                <file-upload />

                <button 
                    class="btn btn-text text-blue-500" 
                    @click.prevent="addFiles = false"
                >
                    Cancel
                </button>

                <hr class="block w-full mt-6 pt-6 border-t border-gray-200">
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Save changes
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
import fromMySQLDateFormat from '../../helpers/fromMySQLDateFormat'
import toMySQLDateFormat from '../../helpers/toMySQLDateFormat'
import Datepicker from 'vuejs-datepicker'
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
            selectedPackage: null,
            customToolbar: [
                [{header: [1, 2, 3, 4, 5, 6, false] }],
                ["bold", "italic", "underline"],
                [{script: 'sub'}, {script: 'super'}],
                [{align: []}],
                [{ list: "ordered" }, { list: "bullet" }],
                [{indent: '-1'}, {indent: '+1'}],
                ["link", "image"]
            ]        
        }
    },

    computed: {
        ...mapGetters({
            logbook: 'logbooks/logbook',
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
            editEntry: 'logbooks/update'
        }),

        orderBy,

        cancel () {
            window.events.$emit('logbooks:edit-cancel')

            this.form.logbook_category_id = null
            this.form.created = ''
            this.form.event_description = ''
            this.form.details_of_event = ''
            this.form.files = []
            this.form.packages = []
        },

        async update () {
            this.form.created = toMySQLDateFormat(this.form.created)

            let response = await this.editEntry(this.form)

            this.cancel()

            this.$toasted.success(response.data.message)
        }
    },

    async mounted () {
        await this.fetchLogbookCategories()

        if (this.authUser.role === 'apprentice') {
            await this.fetchLessonPackages(this.authUser.id)
        }

        this.form.logbook_category_id = this.logbook.logbook_category_id
        this.form.created = fromMySQLDateFormat(this.logbook.created)
        this.form.event_description = this.logbook.event_description
        this.form.details_of_event = this.logbook.details_of_event
        this.form.packages = this.logbook.packages
        this.form.references = this.logbook.references ? this.logbook.references.id : null

        window.events.$on('upload:finished', fileObject => this.form.files.push(fileObject))

        window.events.$on('logbook:remove-package', packageId => {
            this.form.packages = filter(this.form.packages, p => {
                return p !== packageId
            })
        })
    }
}
</script>