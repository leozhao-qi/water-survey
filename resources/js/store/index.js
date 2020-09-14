import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

import state from './state'
import * as mutations from './mutations'
import * as actions from './actions'
import * as getters from './getters'

import user from './modules/user'
import levels from './modules/levels'
import lessons from './modules/lessons'
import lessonsWIP from './modules/lessons-wip'
import lessonVersions from './modules/lesson-versions'
import objectives from './modules/objectives'
import userpackage from './modules/package'
import recommendations from './modules/recommendations'
import statuses from './modules/statuses'
import logbookCategories from './modules/logbook-categories'
import logbooks from './modules/logbooks'
import topics from './modules/topics'
import issues from './modules/issues'

export default new Vuex.Store({
	state, 
	mutations, 
	actions,
	getters,
	modules: {
		user,
		levels,
		lessons,
		objectives,
		userpackage,
		lessonVersions,
		lessonsWIP,
		recommendations,
		statuses,
		logbookCategories,
		logbooks,
		topics,
		issues
	} 
})