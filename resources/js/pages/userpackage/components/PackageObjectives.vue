<template>
    <div class="w-full mt-8">
        <h2 class="text-2xl mb-2">
            Objectives
        </h2>

        <template v-if="typeof userPackage.objectives !== 'undefined'">
            <div
                v-for="type in Object.keys(userPackage.objectives)"
                :key="type"
            >
                <h3 
                    class="font-bold mt-2"
                >
                    {{ ucfirst(type) }}
                </h3>

                <ul>
                    <li
                        v-for="objective in userPackage.objectives[type]"
                        :key="objective.id"
                    >
                        <label 
                            :for="`objective-${objective.id}`" 
                            class="ml-2"
                        >
                            <input 
                                type="checkbox"
                                :id="`objective-${objective.id}`"
                                :value="objective.id"
                                v-model="completedObjectives"
                                @change="update"
                                :disabled="isComplete || !hasRole(['administrator', 'manager', 'head_of_operations', 'supervisor'])"
                            > 
                            {{ objective.number }} - {{ objective.name }}
                        </label>
                    </li>
                </ul>
            </div>
        </template>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import isComplete from '../../../mixins/isComplete'
import ucfirst from '../../../helpers/ucfirst'

export default {
    mixins: [ isComplete ],

    data() {
        return {
            completedObjectives: []
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
                this.completedObjectives = this.userPackage.completedObjectives
            }
        }
    },

    methods: {
        ucfirst,

        update () {
            this.$emit('userpackage:change', [
                {
                    type: 'objectives',
                    value: this.completedObjectives
                }
            ])
        }
    }
}
</script>