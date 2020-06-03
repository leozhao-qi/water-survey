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
            New logbook
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
                ></vue-editor>

                <p
                    v-if="errors.details_of_event"
                    v-text="errors.details_of_event[0]"
                    class="text-red-500 text-sm mt-2"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Create logbook
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
                details_of_event: ''
            }        
        }
    },

    computed: {
        ...mapGetters({
            logbookCategories: 'logbookCategories/logbookCategories'
        })    
    },

    methods: {
        ...mapActions({
            fetchLogbookCategories: 'logbookCategories/fetch',
            storeEntry: 'logbooks/store'
        }),

        cancel () {
            window.events.$emit('logbooks:create-cancel')

            this.form.logbook_category_id = null
            this.form.created = ''
            this.form.event_description = ''
            this.form.details_of_event = ''
        },

        async store () {
            this.form.created = toMySQLDateFormat(this.form.created)

            let response = await this.storeEntry(this.form)

            this.cancel()

            this.$toasted.success(response.data.message)
        }
    },

    mounted () {
        this.fetchLogbookCategories()
    }
}
</script>