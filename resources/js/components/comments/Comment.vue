<template>
    <li class="mb-4">
        <article>
            <div class="text-sm">
                <strong>{{ comment.user.firstname }} {{ comment.user.lastname }}</strong> 

                <span class="text-gray-700">
                    ({{ ucfirst(comment.user.role) }}) 

                    Added: {{ comment.created_at }}

                    <span v-if="comment.edited"> | Edited {{ comment.edited }}</span>
                </span>
            </div>

            <div 
                class="content mt-1"
                v-html="formattedEntry"
                v-if="!editing"
            ></div>

            <comment-edit 
                v-if="editing"
                :comment="comment"
            />

            <div
                class="mt-1 flex items-center"
                v-if="!showConfirm && !editing && (hasRole(['administrator']) || (comment.user.id === parseInt(authUser.id)))"
            >
                <button 
                    class="btn btn-text btn-sm text-sm text-blue-500"
                    @click.prevent="editing = true"
                >
                    Edit
                </button>

                <button 
                    class="btn btn-text btn-sm text-sm text-red-500 ml-2"
                    @click.prevent="destroyConfirm"
                >
                    Delete
                </button>
            </div>

            <div 
                class="bg-yellow-300 border border-yellow-500 text-yellow-700 rounded p-2 my-2"
                v-if="showConfirm && !editing"
            >
                <p>Are you sure you want to delete this comment?</p>   

                <div class="flex mt-2">
                    <button 
                        class="btn btn-red btn-sm text-xs"
                        @click.prevent="destroyComment(comment.id)"
                    >
                        Delete
                    </button>

                    <button 
                        class="btn btn-text btn-sm text-xs ml-2"
                        @click.prevent="showConfirm = false"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </article>
    </li>
</template>

<script>
import ucfirst from '../../helpers/ucfirst'
import { mapActions } from 'vuex'

export default {
    props: {
        comment: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            editing: false,
            showConfirm: false
        }
    },

    computed: {
        formattedEntry () {
            return this.comment.body
                .replace(/<p><br><\/p>/g, '')
                .replace(/<p class="ql-align-justify"><br><\/p>/g, '')
                .replace(/<p class="ql-align-right"><br><\/p>/g, '')
                .replace(/<p class="ql-align-left"><br><\/p>/g, '')
        }
    },

    methods: {
        ...mapActions({
            destroyComment: 'logbooks/destroyComment'
        }),

        ucfirst,

        destroyConfirm () {
            this.showConfirm = true
        }
    },

    mounted () {
        window.events.$on('comments:edit-cancel', () => {
            this.editing = false
        })

        window.events.$emit('comments:deleted', message => {
            this.$toasted.success(message)
        })
    }
}
</script>