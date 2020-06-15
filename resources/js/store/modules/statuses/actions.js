export const fetch = async ({ commit }) => {
    let { data: statuses } = await axios.get(`${urlBase}/api/statuses`)

    commit('SET_STATUSES', statuses.data)

    return
}

export const setEdit = async ({ commit }, status) => {
    await commit('SET_STATUS', status)

    return
}