<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Edit: {{ level.name }}
        </h1>

        <form 
            @submit.prevent="update"
            v-if="typeof form.name !== 'undefined'"
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
                    :class="{ 'border-red-500': errors.name }"
                    id="name"
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
                    Update level
                </button>

                <button 
                    class="btn btn-text text-sm"
                    @click.prevent="cancel"
                >
                    Cancel
                </button>
            </div>
        </form>

        <hr class="block w-full mt-6 pt-6 border-t border-gray-200">

        <destroy-level 
            v-if="hasRole(['administrator'])"
            @close="cancel"
        />
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    data() {
        return {
            form: {
                name: ''
            }
        }
    },

    computed: {
        ...mapGetters({
            level: 'levels/level'
        })
    },

    methods: {
        cancel () {
            window.events.$emit('levels:edit-cancel')

            this.form.name = ''
        },

        async update () {
            let { data } = await axios.put(`${this.urlBase}/api/levels/${this.level.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    },

    mounted () {
        this.form.name = this.level.name
    }
}
</script>