export const fetch = async ({ commit }) => {
    let { data: lessons } = await axios.get(`api/lessons`)

    commit('SET_LESSONS', lessons.data)

    return
}

export const setEdit = async ({ commit }, lesson) => {
    await commit('SET_LESSON', lesson)

    return
}