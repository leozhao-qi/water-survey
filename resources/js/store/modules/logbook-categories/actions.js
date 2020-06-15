export const fetch = async ({ commit }) => {
    let { data: logbookCategories } = await axios.get(`${urlBase}/api/logbook-categories`)

    commit('SET_LOGBOOK_CATEGORIES', logbookCategories.data)

    return
}

export const setEdit = async ({ commit }, logbookCategory) => {
    await commit('SET_LOGBOOK_CATEGORY', logbookCategory)

    return
}