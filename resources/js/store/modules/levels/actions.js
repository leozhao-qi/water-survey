export const fetch = async ({ commit }) => {
    let { data: levels } = await axios.get(`${this.urlBase}/api/levels`)

    commit('SET_LEVELS', levels)

    return
}

export const setEdit = async ({ commit }, level) => {
    await commit('SET_LEVEL', level)

    return
}