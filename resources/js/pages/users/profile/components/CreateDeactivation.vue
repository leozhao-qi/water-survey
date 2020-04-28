<template>
    <div>
        <div class="my-4">
            <label 
                class="block text-gray-700 font-bold mb-2" 
                :class="{ 'text-red-500': errors.deactivated_at }"
                for="deactivation-date"
            >
                Deactivation date
            </label>

            <datepicker
                input-class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                :class="{ 'border-red-500': errors.deactivated_at }"
                v-model="form.deactivated_at"
                format="MM/dd/yyyy"
                id="activateion-date"
            ></datepicker>

            <p
                v-if="errors.deactivated_at"
                v-text="errors.deactivated_at[0]"
                class="text-red-500 text-sm"
            ></p>
        </div>

        <div class="my-4">
            <label 
                class="block text-gray-700 font-bold mb-2" 
                :class="{ 'text-red-500': errors.deactivation_rationale }"
                for="activateion-date"
            >
                Deactivation rationale
            </label>

            <textarea
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                :class="{ 'border-red-500': errors.deactivation_rationale }"
                v-model="form.deactivation_rationale"
                rows="4"
            ></textarea>

            <p
                v-if="errors.deactivation_rationale"
                v-text="errors.deactivation_rationale[0]"
                class="text-red-500 text-sm"
            ></p>
        </div>
    </div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'

export default {
    components: {
        Datepicker
    },

    data() {
        return {
            form: {
                deactivated_at: '',
                deactivation_rationale: ''
            }
        }
    },

    watch: {
        form: {
            deep: true,
            handler () {
                window.events.$emit('deactivations:create', this.form)
            }
        }
    },

    mounted () {
        window.events.$on('deactivations:create-clear', () => {
            this.form.deactivated_at= '',
            this.form.deactivation_rationale = ''
        })
    }
}
</script>