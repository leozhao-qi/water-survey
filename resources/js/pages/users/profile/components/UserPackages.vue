<template>
    <div class="w-full">
        <div class="flex align-center mb-4">
            <h2 class="text-2xl">
                Lesson packages
            </h2>

            <button 
                class="btn btn-text text-blue-500 no-underline"
                v-if="typeof user.packages !== 'undefined' && !user.packages.length && hasRole(['administrator'])"
                @click.prevent="modalActive = true"
            >
                Assign
            </button>
        </div>
        
        <template 
            v-if="typeof user.packages !== 'undefined' && user.packages.length"
        >
            <datatable 
                :data="user.packages"
                :columns="columns"
                :per-page="10"
                :order-keys="['package']"
                :order-key-directions="['asc']"
                :has-text-filter="true"
                :has-action="true"
                action-text="View"
                :action-link="`/users/${user.id}/packages`"
                action-id="id"
            />
        </template>

        <div 
            class="alert alert-blue"
            v-else
        >
            This user does not have any packages assigned.
        </div>

        <modal 
            v-show="modalActive"
            @close="close"
            @submit="store"
        >
            <template slot="header">
                Assign lesson packages to {{ user.firstname }} {{ user.lastname }}
            </template>

            <template slot="body">
                <div class="my-4">
                    <form>
                        <div
                            class="w-full mb-4"
                        >
                            <label 
                                for="version"
                                class="block text-gray-700 font-bold mb-2"
                            >
                                Version
                            </label>

                            <div class="relative">
                                <select 
                                    id="version"
                                    v-model="form.version"
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    :class="{ 'border-red-500': errors.version }"
                                >
                                    <option value=""></option>

                                    <option
                                        :value="lessonVersion.id"
                                        v-for="lessonVersion in lessonVersions"
                                        :key="lessonVersion.id"
                                        v-text="lessonVersion.version"
                                    ></option>
                                </select>

                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>

                            <p
                                v-if="errors.version"
                                v-text="errors.version[0]"
                                class="text-red-500 text-sm"
                            ></p>
                        </div>
                    </form>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from 'vuex'

export default {
    data() {
        return {
            columns: [
                { field: 'version', title: 'Version', sortable: true },
                { field: 'package', title: 'Lesson package', sortable: true }
            ],
            modalActive: false,
            form: {
                version: null
            }        
        }
    },

    computed: {
        ...mapGetters({
            user: 'user/user',
            lessonVersions: 'lessonVersions/lessonVersions'
        })
    },

    methods: {
        ...mapActions({
            fetchLessonVersions: 'lessonVersions/fetch'
        }),

        ...mapMutations({
            updateProfile: 'user/SET_USER'
        }),

        close () {
            this.modalActive = false
        },

        async store () {
            let { data } = await axios.post(`${this.urlBase}/api/users/${this.user.id}/packages`, this.form)

            this.updateProfile(data.data.user)

            this.close()

            this.$toasted.success(data.data.message)
        }
    },

    async mounted () {
        await this.fetchLessonVersions()
    }
}
</script>