<template>
    <form class="mt-2">
        <vue-editor 
            v-model="form.body"
            class="w-full"
            :editorToolbar="customToolbar"
        />

        <div class="flex items-center mt-2">
            <button 
                class="btn btn-blue btn-sm text-sm"
                @click.prevent="update"
            >
                Save changes
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
            },
            customToolbar: [
                [{header: [1, 2, 3, 4, 5, 6, false] }],
                ["bold", "italic", "underline"],
                [{script: 'sub'}, {script: 'super'}],
                [{align: []}],
                [{ list: "ordered" }, { list: "bullet" }],
                [{indent: '-1'}, {indent: '+1'}],
                ["link", "image"]
            ]
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