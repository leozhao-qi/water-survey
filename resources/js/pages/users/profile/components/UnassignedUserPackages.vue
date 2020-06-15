<template>
    <div
        class="w-full"
        v-if="typeof user.unassignedLessons !== 'undefined' && user.unassignedLessons !== null && user.unassignedLessons.length"
    >
        <h2 class="text-2xl mb-4">
            Unassigned lesson packages
        </h2>

        <p class="mb-4">
            There are new lesson packages available that have not been assigned to {{ user.firstname }} {{ user.lastname }}.
        </p>

        <form
            @submit.prevent="update"
        >
            <div
                class="w-full mb-4"
            >
                <label 
                    for="lesson_id"
                    class="block text-gray-700 font-bold mb-2"
                    :class="{ 'text-red-500': errors.lesson_id }"
                >
                    Unassigned packages
                </label>

                <div class="relative w-full lg:w-1/2">
                    <select 
                        id="lesson_id"
                        v-model="form.lesson_id"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.lesson_id }"
                        v-if="typeof user.unassignedLessons !== 'undefined' && user.unassignedLessons.length"
                    >
                        <option value=""></option>

                        <option
                            :value="lesson.id"
                            v-for="lesson in orderBy(user.unassignedLessons, ['number'], ['asc'])"
                            :key="lesson.id"
                            v-text="`${lesson.number} - ${lesson.name}`"
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
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Assign lesson
                </button>
            </div>
        </form>

        <hr class="block w-full mt-6 pt-6 border-t border-gray-200">
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { orderBy } from 'lodash-es'

export default {
    data() {
        return {
            form: {
                lesson_id: ''
            }        
        }
    },

    computed: {
        ...mapGetters({
            user: 'user/user'
        })
    },

    methods: {
        ...mapActions({
            fetch: 'user/fetch'
        }),

        orderBy,

        async update () {
            let { data } = await axios.put(`api/users/${this.user.id}/packages`, this.form)

            await this.fetch(this.user.id)

            this.$toasted.success(data.data.message)
        }
    },
}
</script>