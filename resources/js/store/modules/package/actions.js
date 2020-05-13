export const fetch = async ({ commit }, { userId, userpackageId }) => {
    let { data: userPackage } = await axios.get(`/api/users/${userId}/packages/${userpackageId}`)

    commit('SET_USERPACKAGE', userPackage.data)

    return
}