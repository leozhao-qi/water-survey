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
import objectives from './modules/objectives'
import userpackage from './modules/package'

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
		userpackage
	} 
})