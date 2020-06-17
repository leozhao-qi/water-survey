export const fetch = async ({ commit }) => {
    let { data: topics } = await axios.get(`${urlBase}/api/topics`)

    commit('SET_TOPICS', topics.data)

    return
}

export const setEdit = async ({ commit }, topic) => {
    await commit('SET_TOPIC', topic)

    return
}