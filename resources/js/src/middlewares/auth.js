export default function auth (_to, _from, next) {
    const isAuthenticated = window.localStorage.getItem('id_token');
    if (isAuthenticated) {
    next();
    } else {
    next({ name: 'Login' });
    }
};