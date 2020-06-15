<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-4">
            Edit: Status - {{ status.name }}
        </h1>

        <form 
            @submit.prevent="update"
        >
            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.code }"
                    for="code"
                >
                    Code
                </label>

                <input 
                    type="text" 
                    v-model="form.code"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    :class="{ 'border-red-500': errors.code }"
                    id="code"
                >

                <p
                    v-if="errors.code"
                    v-text="errors.code[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.name_en }"
                    for="name_en"
                >
                    Name (English)
                </label>

                <input 
                    type="text" 
                    v-model="form.name_en"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="name_en"
                    :class="{ 'border-red-500': errors.name_en }"
                >

                <p
                    v-if="errors.name_en"
                    v-text="errors.name_en[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700 font-bold mb-2" 
                    :class="{ 'text-red-500': errors.name_fr }"
                    for="name_fr"
                >
                    Name (French)
                </label>

                <input 
                    type="text" 
                    v-model="form.name_fr"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-auto"
                    id="name_fr"
                    :class="{ 'border-red-500': errors.name_fr }"
                >

                <p
                    v-if="errors.name_fr"
                    v-text="errors.name_fr[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full mb-4"
            >
                <label 
                    class="block text-gray-700" 
                    :class="{ 'text-red-500': errors.completion }"
                    for="completion"
                >
                    <input 
                        type="checkbox" 
                        v-model="form.completion"
                        id="completion"
                        :class="{ 'border-red-500': errors.completion }"
                    >
                    This status counts towards lesson package completion
                </label>

                <p
                    v-if="errors.completion"
                    v-text="errors.completion[0]"
                    class="text-red-500 text-sm"
                ></p>
            </div>

            <div
                class="w-full"
            >
                <button 
                    class="btn btn-blue text-sm"
                >
                    Update status
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
import { mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            form: {
                name_en: '',
                name_fr: '',
                code: '',
                completion: false
            }
        }
    },

    computed: {
        ...mapGetters({
            status: 'statuses/status'
        })
    },

    methods: {
        cancel () {
            window.events.$emit('statuses:edit-cancel')

            this.form.name_en = ''
            this.form.name_fr = ''
            this.code = ''
            this.completion = false
        },

        async update () {
            let { data } = await axios.put(`api/statuses/${this.status.id}`, this.form)

            this.cancel()

            this.$toasted.success(data.data.message)
        },
    },

    async mounted () {
        this.form.name_en = this.status.name_en
        this.form.name_fr = this.status.name_fr
        this.form.code = this.status.code
        this.form.completion = this.status.completion
    }
}
</script>