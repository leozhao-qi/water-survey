<template>
    <div class="w-full mt-8">
        <h2 class="text-2xl mb-2">
            Statement of competency and sign off
        </h2>

        <label for="complete" class="ml-2">
            <input 
                type="checkbox"
                id="complete"
                v-model="complete"
                :true-value="1"
                :false-value="0"
                @change="update"
                :disabled="!hasRole(['administrator', 'manager', 'head_of_operations'])"
            > 
            Statement of competency
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
            complete: false
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
            this.updateCompletion()
            
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