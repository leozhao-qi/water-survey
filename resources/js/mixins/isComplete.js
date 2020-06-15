import { mapGetters } from 'vuex'

export default {
	computed: {
		...mapGetters({
            isComplete: 'userpackage/isComplete'
		})
	}
}