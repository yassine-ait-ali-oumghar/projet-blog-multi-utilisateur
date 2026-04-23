import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = localStorage.getItem('token');
if(token) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

window.axios.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response && error.response.status === 401) {
       localStorage.removeItem('token');
       localStorage.removeItem('user');
       window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);
