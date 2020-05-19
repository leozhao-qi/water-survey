export const fetch = async ({ commit }) => {
    let { data: lessonVersions } = await axios.get(`/api/lesson-versions`)

    commit('SET_LESSON_VERSIONS', lessonVersions.data)

    return
}

export const setEdit = async ({ commit }, lessonVersion) => {
    await commit('SET_LESSON_VERSIONS', lessonVersion)

    return
}