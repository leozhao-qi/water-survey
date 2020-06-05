<template>
    <form class="mt-2">
        <vue-editor 
            v-model="form.body"
            class="w-full"
        />

        <div class="flex items-center mt-2">
            <button 
                class="btn btn-blue btn-sm text-sm"
                @click.prevent="update"
            >
                Edit comment
            </button>

            <button 
                class="btn btn-text btn-sm text-sm ml-2"
                @click.prevent="cancel"
            >
                Cancel
            </button>
        </div>
    </form>
</template>

<script>
import { VueEditor, Quill } from 'vue2-editor'
import { mapActions } from 'vuex'

export default {
    components: {
        VueEditor
    },

    props: {
        comment: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            form: {
                body: this.comment.body
            }
        }
    },

    methods: {
        ...mapActions({
            updateComment: 'logbooks/updateComment'
        }),

        async update () {
            let response = await this.updateComment({
                commentId: this.comment.id,
                form: this.form
            })

            this.cancel()

            this.$toasted.success(response.data.message)
        },

        cancel () {
            this.form.body = ''

            window.events.$emit('comments:edit-cancel')
        }
    },
}
</script>