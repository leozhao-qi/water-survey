<template>
    <li
        class="text-sm mb-1"
    >
        <div class="flex items-center">
            {{ pckg.lesson }}

            <button 
                class="btn btn-text btn-sm no-underline text-blue-500 ml-2 text-xs"
                href="#"
                @click.prevent="showObjectives = !showObjectives"
            >[{{ showObjectives ? 'Hide' : 'Show' }} objectives]</button>

            <button 
                class="btn btn-text btn-sm no-underline text-red-500 text-xs"
                @click.prevent="remove"
            >
                [Remove]
            </button>
        </div>

        <div class="w-full alert mt-1 ml-1" v-if="showObjectives">
            <div
                v-for="type in Object.keys(objectives)"
                :key="type"
            >
                <h3 
                    v-if="type !== 'not_defined'"
                    class="font-bold mt-2"
                >
                    {{ ucfirst(type) }}
                </h3>

                <ul>
                    <li
                        v-for="objective in objectives[type]"
                        :key="objective.id"
                    >
                        
                        {{ objective.number }} - {{ objective.name }}
                    </li>
                </ul>
            </div>
        </div>
    </li>
</template>

<script>
import ucfirst from '../../../helpers/ucfirst'

export default {
    props: {
        pckg: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            objectives: [],
            showObjectives: false
        }
    },

    methods: {
        ucfirst,

        remove () {
            window.events.$emit('logbook:remove-package', this.pckg.id)
        }
    },

    async mounted () {
        let { data: objectives } = await axios.get(`api/packages/${this.pckg.id}/objectives`)

        this.objectives = objectives
    }
}
</script>