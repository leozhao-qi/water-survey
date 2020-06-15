<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Add level
        </h1>

        <form 
            @submit.prevent="store"
        >
            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.name }"
                    for="name"
                >
                    Name
                </label>

                <input 
                    type="text" 
                    v-model="form.name"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="name"
                    :class="{ 'border-red-500': errors.name }"
                >

                <p
                    v-if="errors.name"
                    v-text="errors.name[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Add level
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    Cancel
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import { mapActions } from 'vuex'

export default {
    data() {
        return {
            form: {
                name: ''
            }
        }
    },

    methods: {
        ...mapActions({
            fetch: 'levels/fetch'
        }),

        cancel () {
            window.events.$emit('levels:create-cancel')

            this.form.name = ''
        },

        async store () {
            let { data } = await axios.post(`/api/levels`, this.form)

            await this.fetch()

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    }
}
</script>