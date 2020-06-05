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

export const fetchComments = async ({ commit, state }) => {
    let { data: comments } = await axios.get(`/api/logbooks/${state.logbook.id}/comments`)

    commit('SET_LOGBOOK_COMMENTS', comments.data)

    return
}

export const storeComment = async ({ state, dispatch }, form) => {
    let { data } = await axios.post(`/api/logbooks/${state.logbook.id}/comments`, form)

    await dispatch('fetchComments')

    return data
}

export const updateComment = async ({ state, commit }, { commentId, form }) => {
    let { data } = await axios.put(`/api/logbooks/${state.logbook.id}/comments/${commentId}`, form)

    commit('SET_LOGBOOK_COMMENT', data.data.comment)

    return data
}

export const destroyComment = async ({ state, commit }, commentId) => {
    let { data } = await axios.delete(`/api/logbooks/${state.logbook.id}/comments/${commentId}`)

    commit('REMOVE_LOGBOOK_COMMENT', data.data.comment)

    window.events.$emit('comments:deleted', data.data.message)

    return
}