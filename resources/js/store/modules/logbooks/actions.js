export const store = async ({ commit, dispatch }, form) => {
    let { data } = await axios.post(`/api/logbooks`, form)

    await dispatch('fetch')

    return data
}

export const show = async ({ commit }, logbookId) => {
    let { data: logbook } = await axios.get(`/api/logbooks/${logbookId}`)

    commit('SET_LOGBOOK', logbook.data)

    return
}

export const destroy = async ({ dispatch, state }) => {
    let { data } = await axios.delete(`/api/logbooks/${state.logbook.id}`)

    await dispatch('fetch')

    return data
}

export const destroyFile = async ({ commit, state }, fileId) => {
    let { data } = await axios.delete(`/api/files/${fileId}`)

    await commit('SET_LOGBOOK_FILES', fileId)

    window.events.$emit('logbooks:file-delete', data)

    return
}

export const update = async ({ dispatch, state }, form) => {
    let { data } = await axios.put(`/api/logbooks/${state.logbook.id}`, form)

    await dispatch('show', state.logbook.id)

    return data
}

export const fetch = async ({ commit, rootState }) => {
    let { data: logbooks } = await axios.get(`/api/logbooks`)

    commit('SET_LOGBOOKS', logbooks.data)

    commit('logbookCategories/SET_LOGBOOK_CATEGORIES', logbooks.meta.logbookCategories, {root: true})

    commit('SET_USERS', logbooks.meta.users)

    return
}