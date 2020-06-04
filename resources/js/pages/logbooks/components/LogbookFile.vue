<template>
    <div>
        <span class="flex items-center" v-if="typeof file !== 'undefined'">    
            <svg 
                viewBox="0 0 24 24" 
                class="w-5 h-5 mr-1" 
                :style="`fill: rgb(${fillColor});`"
                v-if="typeof this.file.actual_filename !== 'undefined'"
            >
                <path :d="`${icon}`"/>
            </svg>

            <template v-if="!editingFilename">
                <a 
                    :href="`${urlBase}/storage/entries?user=${logbook.user.id}&file=${file.coded_filename}`"
                >
                    <span>{{ file.actual_filename }}</span>
                </a>

                <svg 
                    viewBox="0 0 24 24" 
                    class="w-5 h-5 ml-4 cursor-pointer" 
                    :style="`fill: ${editFillColor};`"
                    @click.prevent="editingFilename = true"
                    v-if="!showConfirm"
                >
                    <path d="M20.71,7.04C21.1,6.65 21.1,6 20.71,5.63L18.37,3.29C18,2.9 17.35,2.9 16.96,3.29L15.12,5.12L18.87,8.87M3,17.25V21H6.75L17.81,9.93L14.06,6.18L3,17.25Z"/>
                    <title>Edit filename</title>
                </svg>

                <svg 
                    viewBox="0 0 24 24" 
                    class="w-5 h-5 ml-2 cursor-pointer" 
                    :style="`fill: ${deleteFillColor};`"
                    @click.prevent="destroyConfirm"
                    v-if="!showConfirm"
                >
                    <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                    <title>Delete file</title>
                </svg>
            </template>

            <template v-else>
                <div>
                    <span class="flex items-center">
                        <input 
                            type="text" 
                            v-model="form.actual_filename"
                            class="shadow appearance-none border rounded w-64 py-1 px-1 text-gray-700 leading-tight focus:outline-none focus:shadow-outline text-sm"
                            :id="`actual_filename-${file.actual_filename}`"
                            :class="{ 'border-red-500': errors.actual_filename }"
                        >

                        <svg 
                            viewBox="0 0 24 24" 
                            class="w-5 h-5 ml-3 cursor-pointer" 
                            :style="`fill: ${checkFillColor};`"
                            @click.prevent="save"
                        >
                            <path d="M21,7L9,19L3.5,13.5L4.91,12.09L9,16.17L19.59,5.59L21,7Z"/>
                            <title>Save filename change</title>
                        </svg>

                        <svg 
                            viewBox="0 0 24 24"
                            class="w-5 h-5 ml-3 cursor-pointer" 
                            :style="`fill: ${closeFillColor};`"
                            @click.prevent="cancel"
                        >
                            <path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                            <title>Cancel</title>
                        </svg>
                    </span>

                    <p
                        v-if="errors.actual_filename"
                        v-text="errors.actual_filename[0]"
                        class="text-red-500 text-sm"
                    ></p>
                </div>
            </template>
        </span>

        <div 
            class="bg-yellow-300 border border-yellow-500 text-yellow-700 rounded p-2 my-2"
            v-if="showConfirm"
        >
            <p>Are you sure you want to delete this file?</p>   

            <div class="flex mt-2">
                <button 
                    class="btn btn-red btn-sm text-xs"
                    @click.prevent="destroyFile(file.id)"
                >
                    Delete
                </button>

                <button 
                    class="btn btn-text btn-sm text-xs ml-2"
                    @click.prevent="showConfirm = false"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
    props: {
        fileData: {
            type: Object,
            required: true
        }
    },

    computed: {
        ...mapGetters({
            logbook: 'logbooks/logbook'
        }),

        fillColor () {
            if (typeof this.file.actual_filename !== 'undefined') {
                let fileExtension = this.file.actual_filename.split('.')[1].toLowerCase()

                switch (fileExtension) {
                    case 'pdf':
                        return '180, 8, 8'
                    case 'doc':
                    case 'docx':
                        return '0, 24, 143'
                    case 'xls':
                    case 'xlsx':
                        return '0, 114, 51'
                    case 'ppt':
                    case 'pptx':
                        return '221, 89, 0'
                    case 'jpeg':
                    case 'jpg':
                    case 'bmp':
                    case 'tiff':
                    case 'png':
                    case 'gif':
                        return '55, 134, 209'
                    default:
                        return '187, 187, 187'
                }
            }
        },

        icon () {
            if (typeof this.file.actual_filename !== 'undefined') {
                let fileExtension = this.file.actual_filename.split('.')[1].toLowerCase()
            
                switch (fileExtension) {
                    case 'pdf':
                        return 'M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6A2,2 0 0,1 4,20V4A2,2 0 0,1 6,2M10.1,11.4C10.08,11.44 9.81,13.16 8,16.09C8,16.09 4.5,17.91 5.33,19.27C6,20.35 7.65,19.23 9.07,16.59C9.07,16.59 10.89,15.95 13.31,15.77C13.31,15.77 17.17,17.5 17.7,15.66C18.22,13.8 14.64,14.22 14,14.41C14,14.41 12,13.06 11.5,11.2C11.5,11.2 12.64,7.25 10.89,7.3C9.14,7.35 9.8,10.43 10.1,11.4M10.91,12.44C10.94,12.45 11.38,13.65 12.8,14.9C12.8,14.9 10.47,15.36 9.41,15.8C9.41,15.8 10.41,14.07 10.91,12.44M14.84,15.16C15.42,15 17.17,15.31 17.1,15.64C17.04,15.97 14.84,15.16 14.84,15.16M7.77,17C7.24,18.24 6.33,19 6.1,19C5.87,19 6.8,17.4 7.77,17M10.91,10.07C10.91,10 10.55,7.87 10.91,7.92C11.45,8 10.91,10 10.91,10.07Z'
                    case 'doc':
                    case 'docx':
                        return 'M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M15.2,20H13.8L12,13.2L10.2,20H8.8L6.6,11H8.1L9.5,17.8L11.3,11H12.6L14.4,17.8L15.8,11H17.3L15.2,20M13,9V3.5L18.5,9H13Z'
                    case 'xls':
                    case 'xlsx':
                        return 'M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M15.8,20H14L12,16.6L10,20H8.2L11.1,15.5L8.2,11H10L12,14.4L14,11H15.8L12.9,15.5L15.8,20M13,9V3.5L18.5,9H13Z'
                    case 'ppt':
                    case 'pptx':
                        return 'M12.6,12.3H10.6V15.5H12.7C13.3,15.5 13.6,15.3 13.9,15C14.2,14.7 14.3,14.4 14.3,13.9C14.3,13.4 14.2,13.1 13.9,12.8C13.6,12.5 13.2,12.3 12.6,12.3M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M15.2,16C14.6,16.5 14.1,16.7 12.8,16.7H10.6V20H9V11H12.8C14.1,11 14.7,11.3 15.2,11.8C15.8,12.4 16,13 16,13.9C16,14.8 15.8,15.5 15.2,16M13,9V3.5L18.5,9H13Z'
                    case 'jpeg':
                    case 'jpg':
                    case 'bmp':
                    case 'png':
                        return 'M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M6,20H15L18,20V12L14,16L12,14L6,20M8,9A2,2 0 0,0 6,11A2,2 0 0,0 8,13A2,2 0 0,0 10,11A2,2 0 0,0 8,9Z'
                    default:
                        return 'M13,9V3.5L18.5,9M6,2C4.89,2 4,2.89 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2H6Z'
                }
            }
        }
    },

    data() {
        return {
            deleteFillColor: '#F56565',
            editFillColor: '#4299E1',
            checkFillColor: '#48BB78',
            editingFilename: false,
            closeFillColor: '#F56565',
            form: {
                actual_filename: ''
            },
            file: {},
            showConfirm: false
        }
    },

    methods: {
        ...mapActions({
            destroyFile: 'logbooks/destroyFile'
        }),

        async save () {
            let extension = this.file.actual_filename.split('.')[1]

            this.form.actual_filename = `${this.form.actual_filename}.${extension}`

            let { data } = await axios.put(`/api/files/${this.file.id}`, this.form)

            this.file = data.data.file

            this.cancel()

            this.$toasted.success(data.data.message)
        },

        cancel () {
            this.editingFilename = false

            this.form.actual_filename = this.filenameOnly(this.file.actual_filename)
        },

        filenameOnly (file) {
            return this.file.actual_filename.split('.')[0]
        },

        destroyConfirm () {
            this.showConfirm = true
        }
    },

    mounted () {
        this.file = this.fileData

        this.form.actual_filename = this.filenameOnly(this.file.actual_filename)

        window.events.$on('logbooks:file-delete', data => {
            this.showConfirm = false
        })
    }
}
</script>