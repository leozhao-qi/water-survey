<template>
    <div class="w-full mt-8">
        <h2 class="text-2xl mb-2">
            Recommendation

            <span 
                class="mr-2 text-sm"
                v-if="userPackage.recommended_by"
            >
                <strong>Recommended by:</strong>  
                {{ userPackage.recommended_by.firstname }} {{ userPackage.recommended_by.lastname }} 
                ({{ ucfirst(userPackage.recommended_by.role) }}) on {{ fromMySQLDateFormat(userPackage.recommended_on) }}
            </span>
        </h2>

        
        <div class="relative w-full">
            <select 
                v-model="recommendation"
                class="shadow appearance-none border rounded w-full py-2 px-3 mt-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm"
                :class="{ 'border-red-500': errors.recommendation }"
                @change="updateRecommendation"
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

        <div 
            class="mt-4" 
            v-if="showComment && !isComplete && hasRole(['administrator', 'manager', 'head_of_operations', 'supervisor'])"
        >
            <vue-editor 
                v-model="recommendation_comment"
            ></vue-editor>

            <div
                class="w-full mt-4"
            >
                <button 
                    class="btn btn-blue text-sm"
                    @click.prevent="$emit('save')"
                >
                    Save
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    Cancel
                </button>
            </div>
        </div>

        <div class="flex items-center mt-4">
            <strong class="mr-2">Comments</strong>
            
            <button 
                class="btn btn-text btn-sm text-sm"
                @click.prevent="showComment = true" 
                v-if="!showComment && !isComplete && hasRole(['administrator', 'manager', 'head_of_operations', 'supervisor'])"
            >
                {{ recommendation_comment ? 'Edit' : 'Add' }}
            </button>
        </div>

        <p 
            class="mt-2 text-sm"
            v-if="userPackage.recommendation_comment_by"
        >
            <strong>Recommendation comment by:</strong> 
            {{ userPackage.recommendation_comment_by.firstname }} {{ userPackage.recommendation_comment_by.lastname }} 
            ({{ ucfirst(userPackage.recommendation_comment_by.role) }}) on {{ fromMySQLDateFormat(userPackage.recommendation_comment_at) }}
        </p>

        <article 
            v-if="!showComment"
            v-html="formattedRecommendationComment"
            class="content mt-2"
        ></article>
        
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import isComplete from '../../../mixins/isComplete'
import { VueEditor, Quill } from 'vue2-editor'
import fromMySQLDateFormat from '../../../helpers/fromMySQLDateFormat'
import ucfirst from '../../../helpers/ucfirst'

export default {
    components: {
        VueEditor
    },

    mixins: [ isComplete ],

    data() {
        return {
            recommendation: null,
            recommendation_comment: '',
            counter: 0,
            showComment: false
        }
    },

    computed: {
        ...mapGetters({
            userPackage: 'userpackage/userPackage',
            recommendations: 'recommendations/recommendations'
        }),

        formattedRecommendationComment () {
            return this.recommendation_comment
                .replace(/<p><br><\/p>/g, '')
                .replace(/<p class="ql-align-justify"><br><\/p>/g, '')
                .replace(/<p class="ql-align-right"><br><\/p>/g, '')
                .replace(/<p class="ql-align-left"><br><\/p>/g, '')
        }
    },

    methods: {
        ...mapActions({
            fetchRecommendations: 'recommendations/fetch',
            fetch: 'userpackage/fetch'
        }),

        fromMySQLDateFormat,

        ucfirst,

        updateRecommendation () {
            this.$emit('userpackage:change', [
                {
                    type: 'recommendation_id',
                    value: this.recommendation
                }
            ])

            window.events.$emit('recommendation:change', this.recommendation)
        },

        cancel () {
            this.showComment = false

            this.fetch({
                userId: this.userPackage.user_id,
                userpackageId: this.userPackage.id
            })
        }
    },

    watch: {
        userPackage: {
            deep: true,

            handler () {
                this.recommendation = this.userPackage.recommendation ? this.userPackage.recommendation.id : null
                this.recommendation_comment = this.userPackage.recommendation_comment ? this.userPackage.recommendation_comment : ''
            }
        },

        recommendation_comment () {
            if (this.counter !== 0 || this.recommendation_comment) {
                this.$emit('userpackage:change', [
                    {
                        type: 'recommendation_comment',
                        value: this.recommendation_comment
                    }
                ])
            }

            this.counter += 1
        }
    },

    async mounted () {
        await this.fetchRecommendations()

        window.events.$on('userpackage:updated', message => {
            this.showComment = false
        })
    }
}
</script>