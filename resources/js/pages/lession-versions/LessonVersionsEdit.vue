<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Edit lesson version: {{ lessonVersion.version }}
        </h1>

        <form 
            @submit.prevent="update"
        >
            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.version }"
                    for="version"
                >
                    Version
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

            <div class="mb-4">
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.valid_on }"
                    for="valid_on"
                >
                    Valid on
                </label>

                <datepicker
                    input-class="shadow appearance-none border rounded w-full mt-4 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline my-2"
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

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Update lesson version
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
import { mapGetters } from 'vuex'
import toMySQLDateFormat from '../../helpers/toMySQLDateFormat'
import fromMySQLDateFormat from '../../helpers/fromMySQLDateFormat'
import Datepicker from 'vuejs-datepicker'

export default {
    components: {
        Datepicker
    },

    data() {
        return {
            form: {
                version: '',
                valid_on: ''
            }
        }
    },

    computed: {
        ...mapGetters({
            lessonVersion: 'lessonVersions/lessonVersion'
        })
    },

    methods: {
        cancel () {
            window.events.$emit('lesson-versions:edit-cancel')

            this.form.version = ''
            this.form.valid_on = ''
        },

        async update () {
            this.form.valid_on = toMySQLDateFormat(this.form.valid_on)

            let { data } = await axios.put(`api/lesson-versions/${this.lessonVersion.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    },

    async mounted () {
        this.form.version = this.lessonVersion.version
        this.form.valid_on = fromMySQLDateFormat(this.lessonVersion.valid_on)
    }
}
</script>