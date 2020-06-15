<template>
    <div class="w-full mt-8" v-if="hasTheory || hasPractical">
        <h2 class="text-2xl mb-2">
            Status
        </h2>

        <div class="w-full lg:w-2/3 flex">
            <div class="w-1/2 mr-4" v-if="hasTheory">
                <label 
                    class="font-bold"
                    :class="{ 'text-red-500': errors.theory_status }"
                    for="theory_status"
                >
                    Theory
                </label>

                <div class="relative w-full mb-2">
                    <select 
                        id="theory_status"
                        v-model="theory_status"
                        class="shadow appearance-none border rounded w-full py-2 px-3 mt-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.theory_status }"
                        @change="updateTheory"
                        :disabled="isComplete || !hasRole(['administrator', 'manager', 'head_of_operations', 'supervisor'])"
                    >
                        <option
                            :value="status.code"
                            v-for="status in statuses"
                            :key="status.id"
                            v-text="status.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.theory_status"
                    v-text="errors.theory_status[0]"
                    class="text-red-500 text-sm mb-2"
                ></p>
            </div>

            <div class="w-1/2" v-if="hasPractical">
                <label 
                    class="font-bold"
                    for="practical_status"
                    :class="{ 'text-red-500': errors.practical_status }"
                >
                    Practical application
                </label>

                <div class="relative w-full mb-2">
                    <select 
                        id="practical_status"
                        v-model="practical_status"
                        class="shadow appearance-none border rounded w-full py-2 px-3 mt-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        :class="{ 'border-red-500': errors.practical_status }"
                        @change="updatePractical"
                        :disabled="isComplete || !hasRole(['administrator', 'manager', 'head_of_operations', 'supervisor'])"
                    >
                        <option
                            :value="status.code"
                            v-for="status in statuses"
                            :key="status.id"
                            v-text="status.name"
                        ></option>
                    </select>

                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>

                <p
                    v-if="errors.practical_status"
                    v-text="errors.practical_status[0]"
                    class="text-red-500 text-sm mb-2"
                ></p>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import isComplete from '../../../mixins/isComplete'

export default {
    mixins: [ isComplete ],
    
    data() {
        return {
            theory_status: 'incomplete',
            practical_status: 'incomplete',
            hasTheory: false,
            hasPractical: false,
            statuses: []
        }
    },

    computed: {
        ...mapGetters({
            userPackage: 'userpackage/userPackage'
        })
    },

    watch: {
        userPackage: {
            deep: true,

            handler () {
                this.theory_status = this.userPackage.theory_status
                this.practical_status = this.userPackage.practical_status
                this.hasTheory = this.userPackage.objective_types.indexOf('theory') >= 0
                this.hasPractical = this.userPackage.objective_types.indexOf('practical_application') >= 0
            }
        }
    },

    methods: {
        updateTheory () {
            this.$emit('userpackage:change', [
                {
                    type: 'theory_status',
                    value: this.theory_status
                }
            ])
        },

        updatePractical () {
            this.$emit('userpackage:change', [
                {
                    type: 'practical_status',
                    value: this.practical_status
                }
            ])
        }
    },

    async mounted () {
        let { data: statuses } = await axios.get('/api/statuses')

        this.statuses = statuses.data
    }
}
</script>