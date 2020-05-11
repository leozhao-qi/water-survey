export const fetch = async ({ commit }) => {
    let { data: levels } = await axios.get(`/api/levels`)

    commit('SET_LEVELS', levels)

    return
}