<template>
    <div class="flex flex-col items-center w-full lg:w-9/12 py-16 mx-auto">
        <nav 
            class="flex justify-end w-full items-center"
            v-if="!creating && !updating"
        >
            <a 
                href=""
                @click.prevent="creating = true"
                class="btn btn-text"
            >Add lesson</a>
        </nav>

        <lessons-wip-create 
            v-if="creating"
        />

        <lessons-wip-edit 
            v-if="updating"
        />

        <lessons-wip-index 
            v-if="!creating && !updating"
        />

        <div class="fixed bottom-0 w-full flex bg-white p-4 shadow-inner">
            <button 
                class="btn btn-blue ml-auto"
                @click.prevent="modalActive = true"
            >
                Upgrade to new version
            </button>
        </div>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="store"
        >
            <template slot="header">
                Upgrade to new version
            </template>

            <template slot="body">
                <div class="my-4">
                    <p 
                        v-if="typeof lessonVersions !== 'undefined' && lessonVersions.length"
                        class="mb-4"
                    >
                        This will create a new lesson package version. The previous version was 
                        "{{ latestVersion }}". Do you want this new version to be "{{ latestVersion + 1 }}"? 
                        If not, please enter a different version below.
                    </p>

                    <form>
                        <div
                            class="w-full mb-4"
                        >
                            <label 
                                class="block text-gray-700 font-bold mb-2" 
                                :class="{ 'text-red-500': errors.version }"
                                for="version"
                            >
                                Alternate version number
                            </label>

                            <input 
                                type="text" 
                                v-model="form.version"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                                :class="{ 'border-red-500': errors.version }"
                                id="version"
                            >

                            <p
                                v-if="errors.version"
                                v-text="errors.version[0]"
                                class="text-red-500 text-sm"
                            ></p>
                        </div>

                        <p class="my-4">Please select a date that this new package version is valid on:</p>

                        <div 
                            class="mb-4"
                        >
                            <label 
                                class="block text-gray-700 font-bold" 
                                :class="{ 'text-red-500': errors.valid_on }"
                                for="valid_on"
                            >
                                New lesson package valid date
                            </label>

                            <datepicker
                                input-class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline my-2"
                                :class="{ 'border-red-500': errors.valid_on }"
                                v-model="form.valid_on"
                                format="MM/dd/yyyy"
                            ></datepicker>

                            <p
                                v-if="errors.valid_on"
                                v-text="errors.valid_on[0]"
                                class="text-red-500 text-sm mb-2"
                            ></p>
                        </div>
                    </form>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { orderBy } from 'lodash-es'
import Datepicker from 'vuejs-datepicker'
import toMySQLDateFormat from '../../helpers/toMySQLDateFormat'

export default {
    components: {
        Datepicker
    },

    data() {
        return {
            creating: false,
            updating: false,
            modalActive: false,
            form: {
                version: '',
                valid_on: ''
            }
        }
    },

    computed: {
        ...mapGetters({
            lessonVersions: 'lessonVersions/lessonVersions'
        }),

        latestVersion () {
            return (orderBy(this.lessonVersions, ['version'], ['desc']))[0].version
        }
    },

    methods: {
        ...mapActions({
            fetchLessonVersions: 'lessonVersions/fetch'
        }),

        orderBy,

        close () {
            this.modalActive = false

            this.form.version = ''
            this.form.valid_on = ''
        },

        async store () {
            if (!this.form.version) {
                this.form.version = this.latestVersion + 1
            }

            this.form.valid_on = toMySQLDateFormat(this.form.valid_on)

            let { data } = await axios.post(`${this.urlBase}/api/lesson-versions`, this.form)

            this.close()

            window.location.href = `${this.urlBase}/lessons`
        }
    },

    async mounted () {
        await this.fetchLessonVersions()

        window.events.$on('lessons-wip:edit', () => {
            this.updating = true
        })

        window.events.$on('lessons-wip:edit-cancel', level => {
            this.updating = false
        })

        window.events.$on('lessons-wip:create-cancel', () => {
            this.creating = false
        })
    }
}
</script>