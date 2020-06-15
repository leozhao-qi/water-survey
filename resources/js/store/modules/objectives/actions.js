export const fetch = async ({ commit }) => {
    let { data: objectives } = await axios.get(`${urlBase}/api/objectives`)

    commit('SET_OBJECTIVES', objectives.data)

    return
}

export const setEdit = async ({ commit }, objective) => {
    await commit('SET_OBJECTIVE', objective)

    return
}