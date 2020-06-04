import { filter } from 'lodash-es'

export const SET_LOGBOOKS = (state, logbooks) => state.logbooks = logbooks

export const SET_LOGBOOK = (state, logbook) => state.logbook = logbook

export const SET_LOGBOOK_FILES = (state, fileId) => {
    state.logbook.files = filter(state.logbook.files, file => {
        return file.id !== fileId
    })
}

export const SET_USERS = (state, users) => state.users = users