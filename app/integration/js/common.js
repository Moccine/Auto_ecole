const _ = require('lodash');
const $ = require('jquery');

const trans = (key, params) => {
    if (jsTranslations[key]) {
        let text = jsTranslations[key];

        _.forOwn(params, (value, key) => {
            text = text.replace('%' + key + '%', value)
        });

        return text
    } else {
        if (console && jsVars.debug) {
            console.warn('Translation not found: ' + key)
        }

        return key
    }
};

const getRoute = (key, params) => {
    if (jsRoutes[key]) {
        let route = jsRoutes[key];

        _.forOwn(params, (value, key) => {
            route = route.replace('__' + key + '__', value)
        });

        return route
    } else {
        if (console && jsVars.debug) {
            console.warn('Route not found: ' + key)
        }

        return key
    }
};

const httpRequest = (url, method, data) => {
    return $.ajax({
        url: url,
        method: method,
        data: data,
    })
};

module.exports = {
    trans,
    getRoute,
    httpRequest,
};
