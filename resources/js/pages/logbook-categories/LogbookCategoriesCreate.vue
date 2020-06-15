<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            New logbook category
        </h1>

        <form 
            @submit.prevent="store"
        >
            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.name_en }"
                    for="name_en"
                >
                    Name (English)
                </label>

                <input 
                    type="text" 
                    v-model="form.name_en"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="name_en"
                    :class="{ 'border-red-500': errors.name_en }"
                >

                <p
                    v-if="errors.name_en"
                    v-text="errors.name_en[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.name_fr }"
                    for="name_fr"
                >
                    Name (French)
                </label>

                <input 
                    type="text" 
                    v-model="form.name_fr"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="name_fr"
                    :class="{ 'border-red-500': errors.name_fr }"
                >

                <p
                    v-if="errors.name_fr"
                    v-text="errors.name_fr[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700" 
                    :class="{ 'text-red-500': errors.supervisor_only }"
                    for="supervisor_only"
                >
                    <input 
                        type="checkbox" 
                        v-model="form.supervisor_only"
                        id="supervisor_only"
                        :class="{ 'border-red-500': errors.supervisor_only }"
                    >
                    This category should only be seen by supervisors.
                </label>

                <p
                    v-if="errors.supervisor_only"
                    v-text="errors.supervisor_only[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Add logbook category
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

export default {
    data() {
        return {
            form: {
                name_en: '',
                name_fr: '',
                supervisor_only: false
            }
        }
    },

    computed: {
        ...mapGetters({
            logbookCategories: 'logbookCategories/logbookCategories'
        })
    },

    methods: {
        cancel () {
            window.events.$emit('logbook-categories:create-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.code = ''
            this.completion = false
        },

        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/logbook-categories`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    }
}
</script>