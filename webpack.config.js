const path = require('path');

module.exports = {
    entry: './public/js/mvm.js',
    mode: 'production',
    output: {
        path: path.resolve(__dirname, 'public/js/dist/'),
        filename: 'mvm.js'
    },
    resolveLoader: {
        modules: [
            path.join(__dirname, 'public/yarn')
        ]
    },
    resolve: {
        modules: [
            path.join(__dirname, 'public/yarn')
        ]
    },
    devtool: 'source-map'
};

