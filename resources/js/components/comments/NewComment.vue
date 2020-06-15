<template>
    <div>
        <vue-editor 
            v-model="form.body"
            class="w-full"
        />

        <p
            v-if="errors.body"
            v-text="errors.body[0]"
            class="text-red-500 text-sm mt-2"
        ></p>

        <div class="flex items-center mt-4">
            <button 
                class="btn btn-blue btn-sm text-sm"
                @click.prevent="store"
            >
                Add comment
            </button>

            <button 
                class="btn btn-text btn-sm text-sm ml-2"
                @click.prevent="cancel"
            >
                Cancel
            </button>
        </div>

        <hr class="block w-full mt-6 pt-6 border-t border-gray-200">
    </div>
</template>

<script>
import { VueEditor, Quill } from 'vue2-editor'
import { mapActions } from 'vuex'

export default {
    components: {
        VueEditor
    },

    data() {
        return {
            form: {
                body: ''
            }
        }
    },

    methods: {
        ...mapActions({
            storeComment: 'logbooks/storeComment'
        }),

        cancel () {
            this.form.body = ''

            window.events.$emit('comments:cancel')
        },

        async store () {
            let response = await this.storeComment(this.form)

            this.cancel()

            this.$toasted.success(response.data.message)
        }
    }
}
</script>