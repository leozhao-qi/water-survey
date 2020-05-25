export const fetch = async ({ commit }, { userId, userpackageId }) => {
    let { data: userPackage } = await axios.get(`/api/users/${userId}/packages/${userpackageId}`)

    commit('SET_USERPACKAGE', userPackage.data)

    commit('SET_FORM', {
        complete: userPackage.data.complete
    })

    return
}

export const update = async ({ commit, state }, { userId, userpackageId }) => {
    const form = {
        signed_off_by: state.form.complete ? state.form.signed_off_by : null,
        signed_off_at: state.form.complete ? state.form.signed_off_at : null,
        complete: state.form.complete ? 1 : 0
    }

    console.log(form)
    
    let { data: userPackage } = await axios.put(`/api/users/${userId}/packages/${userpackageId}`, form)

    commit('SET_USERPACKAGE', userPackage.data.package)

    commit('SET_FORM', {
        complete: userPackage.data.package.complete
    })

    window.events.$emit('userpackage:updated', userPackage.data.message)

    return
}

export const updatePackageObj = async ({ commit }, arr) => {
    commit('UPDATE_USERPACKAGE', arr)
}