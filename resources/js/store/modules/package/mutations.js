import { forEach } from "lodash-es"

export const SET_USERPACKAGE = (state, userPackage) => state.userPackage = userPackage

export const SET_FORM = (state, formData) => state.form = formData

export const UPDATE_USERPACKAGE = (state, arr) => {
    forEach(arr, item => state.form[item.type] = item.value)
}

export const SET_COMPLETE = (state, complete) => {
    if (complete === 'A' || complete === 'B') {
        state.isComplete = true
    } else {
        state.isComplete = false
    }
}