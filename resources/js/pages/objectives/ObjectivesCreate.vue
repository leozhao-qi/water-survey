<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            New objective
        </h1>

        <form 
            @submit.prevent="store"
        >
            <div
                class="w-full mb-4"
            >
                <label 
                    for="lesson_id"
                    class="block text-gray-700 font-bold mb-2"
                >
                    Lesson
                </label>

                <div class="relative">
                    <select 
                        id="lesson_id"
                        v-model="form.lesson_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.lesson_id }"
                    >
                        <option value=""></option>

                        <option
                            :value="lesson.id"
                            v-for="lesson in orderBy(lessons, ['number'], ['asc'])"
                            :key="lesson.id"
                            v-text="`${lesson.number} v${lesson.version} - ${lesson.name}`"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.lesson_id"
                    v-text="errors.lesson_id[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.number }"
                    for="number"
                >
                    Number
                </label>

                <input 
                    type="text" 
                    v-model="form.number"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    :class="{ 'border-red-500': errors.number }"
                    id="number"
                >

                <p
                    v-if="errors.number"
                    v-text="errors.number[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.name_en }"
                    for="name_en"
                >
                    Description (English)
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
                    Description (French)
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
                    for="type"
                    class="block text-gray-700 font-bold mb-2"
                >
                    Objective type
                </label>

                <div class="relative">
                    <select 
                        id="type"
                        v-model="form.type"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.type }"
                    >
                        <option value=""></option>

                        <option
                            :value="type.value"
                            v-for="type in typeValues"
                            :key="type.value"
                            v-text="type.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.type"
                    v-text="errors.type[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Create objective
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
import { orderBy } from 'lodash-es'

export default {
    data() {
        return {
            form: {
                name_en: '',
                name_fr: '',
                number: null,
                lesson_id: null,
                type: ''
            },
            typeValues: [
                { name: 'Theory', value: 'theory' },
                { name: 'Practical application', value: 'practical_application' }
            ]
        }
    },

    computed: {
        ...mapGetters({
            lessons: 'lessons/lessons'
        })
    },

    methods: {
        ...mapActions({
            fetchLessons: 'lessons/fetch'
        }),

        orderBy,

        cancel () {
            window.events.$emit('objectives:create-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.form.number = null
            this.form.lesson_id = null
            this.form.type = ''
        },

        async store () {
            let { data } = await axios.post(`/api/objectives`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    },

    async mounted () {
        await this.fetchLessons()
    }
}
</script>