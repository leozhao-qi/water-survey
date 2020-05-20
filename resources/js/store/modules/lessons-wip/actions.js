export const fetch = async ({ commit }) => {
    let { data: lessons } = await axios.get(`/api/lesson-versions/create-version`)

    commit('SET_LESSONS', lessons.data)

    return
}

export const setEdit = async ({ commit }, lesson) => {
    await commit('SET_LESSON', lesson)

    return
}