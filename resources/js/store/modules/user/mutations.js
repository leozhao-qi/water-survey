import { filter } from 'lodash-es'

export const SET_USER = (state, user) => state.user = user

export const UPDATE_DEACTIVATIONS = (state, deactivation) => {
    state.user.deactivations = filter(state.user.deactivations, d => {
        return d.id !== deactivation.id
    })
}