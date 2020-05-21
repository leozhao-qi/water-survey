<template>
    <div class="w-full">
        <nav 
            class="flex justify-end w-full items-center"
        >
            <a 
                href=""
                @click.prevent="cancel"
                class="btn btn-text"
            >Back to work in progress</a>
        </nav>

        <h1 
            class="text-3xl font-bold mb-4"
            v-if="!creatingObjective && !updatingObjective"
        >
            {{ lesson.number }} - {{ lesson.name }}
        </h1>

        <div
            v-if="!editingLesson && !creatingObjective && !updatingObjective"
        >
            <p>
                <strong>Level:</strong> {{ lesson.level.name }}
            </p>

            <button 
                class="btn btn-text text-blue-500 text-sm mt-4"
                @click.prevent="editingLesson = true"
            >Edit lesson</button>
        </div>

        <template v-if="editingLesson && !creatingObjective && !updatingObjective">
            <form 
                @submit.prevent="update"
            >
                <div
                    class="w-full mb-4"
                >
                    <label 
                        for="level_id"
                        class="block text-gray-700 font-bold mb-2"
                    >
                        Level
                    </label>

                    <div class="relative">
                        <select 
                            id="level_id"
                            v-model="form.level_id"
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            :class="{ 'border-red-500': errors.level_id }"
                        >
                            <option value=""></option>

                            <option
                                :value="level.id"
                                v-for="level in levels"
                                :key="level.id"
                                v-text="level.name"
                            ></option>
                        </select>

                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>

                    <p
                        v-if="errors.level_id"
                        v-text="errors.level_id[0]"
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
                    class="w-full"
                >
                    <button 
                        class="btn btn-blue text-sm"
                    >
                        Update lesson
                    </button>

                    <button 
                        class="btn btn-text text-sm"
                        @click.prevent="editingLesson = false"
                    >
                        Cancel
                    </button>
                </div>
            </form>

            <hr class="block w-full mt-6 pt-6 border-t border-gray-200">

            <destroy-lesson-wip 
                v-if="hasRole(['administrator'])"
                @close="cancel"
            /> 
        </template>

        <hr 
            v-if="!editingLesson && !creatingObjective && !updatingObjective" 
            class="block w-full border-t border-gray-200 mt-4"
        >

        <div
            class="mt-4"
            v-if="!editingLesson"
        >
            <div class="flex flex-col items-center w-full mx-auto">
                <nav 
                    class="flex justify-end w-full items-center"
                    v-if="!creatingObjective && !updatingObjective"
                >
                    <a 
                        href=""
                        @click.prevent="creatingObjective = true"
                        class="btn btn-text text-sm"
                    >Add objective</a>
                </nav>

                <objectives-wip-create 
                    v-if="creatingObjective"
                />

                <objectives-wip-edit 
                    v-if="updatingObjective"
                />

                <objectives-wip-index 
                    v-if="!creatingObjective && !updatingObjective"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'

export default {
    data() {
        return {
            form: {
                name_en: '',
                name_fr: '',
                number: null,
                level_id: null
            },
            editingLesson: false,
            creatingObjective: false,
            updatingObjective: false
        }
    },

    computed: {
        ...mapGetters({
            lesson: 'lessonsWIP/lesson',
            lessons: 'lessonsWIP/lessons',
            levels: 'levels/levels'
        })
    },

    methods: {
        ...mapActions({
            fetchLevels: 'levels/fetch',
            fetch: 'lessonsWIP/fetch'
        }),

        ...mapMutations({
            setLesson: 'lessonsWIP/SET_LESSON'
        }),

        cancel () {
            window.events.$emit('lessons-wip:edit-cancel')

            this.editingLesson = false

            this.resetForm()
        },

        resetForm () {
            this.form.name_en = ''
            this.form.name_fr = ''
            this.form.number = null
            this.form.level_id = null
        },

        async update () {
            let { data } = await axios.put(`/api/lessons-wip/${this.lesson.id}`, this.form)

            await this.setLesson(data.data.lesson)

            this.editingLesson = false

            this.$toasted.success(data.data.message)
        },
    },

    async mounted () {
        await this.fetchLevels()

        this.form.name_en = this.lesson.name_en
        this.form.name_fr = this.lesson.name_fr
        this.form.number = this.lesson.number
        this.form.level_id = this.lesson.level_id

        window.events.$on('objectives-wip:edit', () => {
            this.updatingObjective = true
        })

        window.events.$on('objectives-wip:edit-cancel', () => {
            this.updatingObjective = false
        })

        window.events.$on('objectives-wip:create-cancel', () => {
            this.creatingObjective = false
        })
    }
}
</script>