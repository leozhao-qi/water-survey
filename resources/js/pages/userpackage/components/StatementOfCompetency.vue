<template>
    <div class="w-full mt-8">
        <h2 class="text-2xl mb-2">
            Final Sign off
        </h2>

        <label for="complete-null" class="block mb-4 ml-2">
            <input 
                type="radio"
                id="complete-null"
                v-model="complete"
                :value="null"
                @change="update"
                :disabled="!hasRole(['administrator', 'manager', 'head_of_operations'])"
            > 
            Package not complete
        </label> 

        <label for="complete-a" class="block mb-4 ml-2">
            <input 
                type="radio"
                id="complete-a"
                v-model="complete"
                value="A"
                @change="update"
                :disabled="!hasRole(['administrator', 'manager', 'head_of_operations'])"
            > 
            <strong>Statement of Competency</strong> - I agree with the recommendation and that the details have been reviewed, the required objectives have been met and that the Lesson Package is complete as specified by management.
        </label> 

        <label for="complete-b" class="block mb-4 ml-2">
            <input 
                type="radio"
                id="complete-b"
                v-model="complete"
                value="B"
                @change="update"
                :disabled="!hasRole(['administrator', 'manager', 'head_of_operations'])"
            > 
            <strong>Exemption</strong> - I agree with the recommendation and not all objectives were expected to be completed for this lesson package. This does not exempt the candidate from the Lesson Package objectives should Management decide it is required at any time.
        </label> 

        <p 
            class="mt-2 ml-2 text-sm"
            v-if="userPackage.signed_off_by"
        >
            <strong>Signed off by:</strong> 
            {{ userPackage.signed_off_by.firstname }} {{ userPackage.signed_off_by.lastname }} 
            ({{ ucfirst(userPackage.signed_off_by.role) }}) on {{ fromMySQLDateFormat(userPackage.signed_off_at) }}
        </p>

        <div 
            class="alert alert-red mt-4"
            v-if="errors.complete"
            v-text="errors.complete[0]"
        >

        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import ucfirst from '../../../helpers/ucfirst'
import toMySQLDateFormat from '../../../helpers/toMySQLDateFormat'
import fromMySQLDateFormat from '../../../helpers/fromMySQLDateFormat'

export default {
    data() {
        return {
            complete: null
        }
    },

    computed: {
        ...mapGetters({
            userPackage: 'userpackage/userPackage'
        })
    },

    watch: {
        userPackage: {
            deep: true,

            handler () {
                this.complete = this.userPackage.complete
            }
        }
    },

    methods: {
        ...mapActions({
            updateCompletion: 'userpackage/updateCompletion'
        }),

        ucfirst,

        fromMySQLDateFormat,

        update () {
            this.updateCompletion(this.complete)
            
            this.$emit('userpackage:change', [
                {
                    type: 'complete',
                    value: this.complete
                }
            ])
        }
    }
}
</script>