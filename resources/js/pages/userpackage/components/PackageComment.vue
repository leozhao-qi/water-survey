<template>
    <div class="w-full mt-4">
        <div class="ml-2">
            <div class="flex items-center">
                <label 
                    for="comments"
                    class="font-bold"
                >
                    Comments
                </label>

                <button 
                    class="btn btn-text text-sm ml-2"
                    v-if="!editing"
                    @click.prevent="editing = true"
                >
                    Edit
                </button>
            </div>

            <div class="mt-2" v-if="editing">
                <vue-editor 
                    v-model="comment"
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

            <article 
				v-if="!editing"
				v-html="formattedComment"
                class="content"
			></article>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import { VueEditor, Quill } from 'vue2-editor'

export default {
    components: {
        VueEditor
    },

    data() {
        return {
            editing: false,
            comment: ''
        }
    },

    computed: {
        ...mapGetters({
            userPackage: 'userpackage/userPackage'
        }),

        formattedComment () {
            return this.comment.replace(/<p><br><\/p>/g, '')
        }
    },

    watch: {
        userPackage: {
            deep: true,

            handler () {
                this.comment = this.userPackage.comment ? this.userPackage.comment : ''
            }
        },

        comment () {
            this.$emit('userpackage:change', [
                {
                    type: 'comment',
                    value: this.comment
                }
            ])
        }
    },

    methods: {
        ...mapActions({
            fetch: 'userpackage/fetch'
        }),

        cancel () {
            this.editing = false

            this.fetch({
                userId: this.userPackage.user_id,
                userpackageId: this.userPackage.id
            })
        }
    },

    mounted () {
        window.events.$on('userpackage:updated', message => {
            this.editing = false
        })
    }
}
</script>