export default function guest (_to, _from, next) {
    const isAuthenticated = window.localStorage.getItem('id_token');
    if (isAuthenticated) {
    next({ path: '/dashboard' });
    } else {
    next();
    }
}