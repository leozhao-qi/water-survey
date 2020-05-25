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
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
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
            form: 'userpackage/form',
            userPackage: 'userpackage/userPackage'
        })
    },

    watch: {
        complete () {
            this.$emit('userpackage:change', [
                {
                    type: 'complete',
                    value: this.complete
                },{
                    type: 'signed_off_by',
                    value: this.complete === true ? parseInt(this.authUser.id) : null
                },{
                    type: 'signed_off_at',
                    value: this.complete === true ? toMySQLDateFormat(Date.now()) : null
                }
            ])
        },

        form: {
            deep: true,

            handler () {
                this.complete = this.form.complete
            }
        }
    },

    methods: {
        ucfirst,

        fromMySQLDateFormat
    },
}
</script>