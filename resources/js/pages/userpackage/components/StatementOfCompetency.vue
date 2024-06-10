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
            <strong>Statement of Competency</strong> - I agree with the recommendation and that the details have been
            reviewed, the required objectives have been completed as approved by Manager (Recommended as Option A or D)
        </label>

        <div
            v-if="needsSignOffConfirm"
            class="alert alert-red mb-3"
        >
            {{ signOffConfirmText }}
        </div>

        <label for="complete-b" class="block mb-4 ml-2">
            <input
                type="radio"
                id="complete-b"
                v-model="complete"
                value="B"
                @change="update"
                :disabled="!hasRole(['administrator', 'manager', 'head_of_operations'])"
            >
            <strong>Exemption</strong> - Fully Exempt – No objectives are expected to be completed as pre approved by Manager.  This does not exempt the candidate from the Lesson Package objectives should Management decide it is required at any time. ( Recommended as Option C)
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
            complete: null,
            needsSignOffConfirm: false,
            recommendationId: null,
            signOffConfirmText: ''
        }
    },

    computed: {
        ...mapGetters({
            userPackage: 'userpackage/userPackage',
            form: 'userpackage/form'
        })
    },

    watch: {
        userPackage: {
            deep: true,

            handler () {
                this.recommendationId = this.form.recommendation_id

                this.complete = this.userPackage.complete

                if (this.userPackage.recommendation) {
                    this.recommendationId = this.userPackage.recommendation.id

                    this.needsSignOffConfirm = this.userPackage.recommendation.code === 'D' && this.complete === 'A'

                    this.signOffConfirmText = 'This lesson package has been signed off as partially completed.  If the "Status" changes please update the Evaluation Details and Recommendation Details.'
                }
            }
        },

        complete () {
            if (this.userPackage.recommendation && this.userPackage.recommendation.code === 'D' && this.complete === 'A' && !this.form.recommendation_id) {
                this.signOffConfirmText = 'This lesson package has been signed off as partially completed.  If the "Status" changes please update the Evaluation Details and Recommendation Details.'
                this.needsSignOffConfirm = true
            } else if (this.recommendationId === 4 && this.complete === 'A') {
                this.signOffConfirmText = 'You are about to sign off on a partially complete lesson package.  If you wish to continue select "Update lesson package"'
                this.needsSignOffConfirm = true
            } else {
                this.signOffConfirmText = ''
                this.needsSignOffConfirm = false
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
    },

    mounted () {
        this.recommendationId = this.form.recommendation_id

        window.events.$on('recommendation:change', recommendation => {
            this.recommendationId = recommendation

            if (this.recommendationId === 4 && this.complete === 'A') {
                this.needsSignOffConfirm = true

                this.signOffConfirmText = 'You are about to sign off on a partially complete lesson package.  If you wish to continue select "Update lesson package"'
            }
        })
    }
}
</script>
