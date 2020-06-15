import pad from './pad'

export default function (date) {
    let dateParts, jsDateObj

    if (!date) {
        return ''
    }

    if (/\/.*\//.test(date)) {
        dateParts = date.split('/')

        jsDateObj = new Date(dateParts[2], dateParts[0] - 1, dateParts[1].substr(0,2))
    } else {
        dateParts = date.split('-')

        jsDateObj = new Date(dateParts[0], dateParts[1] - 1, dateParts[2].substr(0,2))
    }

    return pad(jsDateObj.getUTCMonth() + 1) + '/' + pad(jsDateObj.getUTCDate()) + '/' + jsDateObj.getUTCFullYear()
}