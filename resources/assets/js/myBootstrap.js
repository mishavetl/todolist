axios.interceptors.response.use(undefined, error => {
    const response = error.response;
    if (response.status === 401) {
        window.location.reload();
    }
    return Promise.reject(error);
});