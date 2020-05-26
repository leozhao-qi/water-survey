export const fetch = async ({ commit }, { userId, userpackageId }) => {
    let { data: userPackage } = await axios.get(`/api/users/${userId}/packages/${userpackageId}`)

    commit('SET_USERPACKAGE', userPackage.data)

    return
}

export const update = async ({ commit, state }, { userId, userpackageId }) => {
    let { data: userPackage } = await axios.put(
        `/api/users/${userId}/packages/${userpackageId}`, state.form
    )

    commit('SET_USERPACKAGE', userPackage.data.package)

    window.events.$emit('userpackage:updated', userPackage.data.message)

    return
}

export const updatePackageObj = async ({ commit }, arr) => {
    commit('UPDATE_USERPACKAGE', arr)
}