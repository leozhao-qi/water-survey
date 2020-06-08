import { filter, assign, find } from 'lodash-es'

export const SET_LOGBOOKS = (state, logbooks) => state.logbooks = logbooks

export const SET_LOGBOOK = (state, logbook) => state.logbook = logbook

export const SET_LOGBOOK_FILES = (state, fileId) => {
    state.logbook.files = filter(state.logbook.files, file => {
        return file.id !== fileId
    })
}

export const SET_LOGBOOK_COMMENTS = (state, comments) => state.logbook.comments = comments

export const SET_LOGBOOK_COMMENT = (state, comment) => assign(find(state.logbook.comments, { id: comment.id }), comment)

export const REMOVE_LOGBOOK_COMMENT = (state, comment) => state.logbook.comments = filter(state.logbook.comments, c => c.id !== comment.id)

export const SET_USERS = (state, users) => state.users = users

export const SET_LOGBOOK_PACKAGES = (state, packages) => state.packages = packages

export const SET_APPRENTICES = (state, apprentices) => state.apprentices = apprentices

export const SET_PACKAGES_INDEX = (state, packages) => state.packagesIndex = packages