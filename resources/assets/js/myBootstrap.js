var lastActionElement = document.querySelector('#last_action');

axios.interceptors.request.use(function (config) {
    if (config.data == undefined) {
        config.data = {};
    }
    config.data['last_action'] =  lastActionElement.value;
    return config;
}, function (error) {
    document.querySelector('#problem-alert').style.display = 'block';
    return Promise.reject(error);
});

axios.interceptors.response.use(okay => {
    lastActionElement.value = okay.headers['last_action'];
    return okay;
}, error => {
    const response = error.response;
    if (response.status === 401) {
        window.location.reload();
    } else if (response.status === 409) {
        document.querySelector('#reload-alert').style.display = 'block';
        window.scrollTo(0, 0);
    }
    return Promise.reject(error);
});