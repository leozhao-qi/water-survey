export const fetch = async ({ commit }, { userId, userpackageId }) => {
    let { data: userPackage } = await axios.get(`${urlBase}/api/users/${userId}/packages/${userpackageId}`)

    commit('SET_USERPACKAGE', userPackage.data)

    commit('SET_COMPLETE', userPackage.data.complete)

    return
}

export const update = async ({ commit, state }, { userId, userpackageId }) => {
    let { data: userPackage } = await axios.put(
        `${urlBase}/api/users/${userId}/packages/${userpackageId}`, state.form
    )

    commit('SET_USERPACKAGE', userPackage.data.package)

    commit('SET_COMPLETE', userPackage.data.package.complete)

    window.events.$emit('userpackage:updated', userPackage.data.message)

    return
}

export const updatePackageObj = ({ commit }, arr) => {
    commit('UPDATE_USERPACKAGE', arr)
}

export const updateCompletion  = ({ commit, state }) => {
    commit('SET_COMPLETE', !state.isComplete)
}