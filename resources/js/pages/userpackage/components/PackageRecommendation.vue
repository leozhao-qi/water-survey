<template>
    <div class="w-full mt-8">
        <h2 class="text-2xl mb-2">
            Recommendation
        </h2>

        
        <div class="relative w-full lg:w-2/3">
            <select 
                v-model="recommendation"
                class="shadow appearance-none border rounded w-full py-2 px-3 mt-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                :class="{ 'border-red-500': errors.recommendation }"
                @change="update"
                :disabled="isComplete || !hasRole(['administrator', 'manager', 'head_of_operations', 'supervisor'])"
            >
                <option value=""></option>

                <option
                    :value="r.id"
                    v-for="r in recommendations"
                    :key="r.id"
                    v-text="`Option ${r.code} - ${r.name}`"
                ></option>
            </select>

            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import isComplete from '../../../mixins/isComplete'

export default {
    mixins: [ isComplete ],

    data() {
        return {
            recommendation: null
        }
    },

    computed: {
        ...mapGetters({
            userPackage: 'userpackage/userPackage',
            recommendations: 'recommendations/recommendations'
        })
    },

    methods: {
        ...mapActions({
            fetchRecommendations: 'recommendations/fetch'
        }),

        update () {
            this.$emit('userpackage:change', [
                {
                    type: 'recommendation_id',
                    value: this.recommendation
                }
            ])
        }
    },

    watch: {
        userPackage: {
            deep: true,

            handler () {
                this.recommendation = this.userPackage.recommendation ? this.userPackage.recommendation.id : null
            }
        }
    },

    async mounted () {
        await this.fetchRecommendations()
    }
}
</script>