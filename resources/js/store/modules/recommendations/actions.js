export const fetch = async ({ commit }) => {
    let { data: recommendations } = await axios.get(`api/recommendations`)

    commit('SET_RECOMMENDATIONS', recommendations.data)

    return
}

export const setEdit = async ({ commit }, recommendation) => {
    await commit('SET_RECOMMENDATION', recommendation)

    return
}