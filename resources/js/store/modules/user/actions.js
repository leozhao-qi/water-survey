export const fetch = async ({ commit }, userId) => {
    let { data: user } = await axios.get(`/api/users/${userId}`)

    commit('SET_USER', user.data)

    return
}

export const updateDeactivations = async ({ commit }, deactiavtion) => {
    let { data: user } = await axios.get(`/api/users/${userId}`)

    await commit('SET_USER', user.data)

    commit('UPDATE_DEACTIVATIONS', deactiavtion)
}