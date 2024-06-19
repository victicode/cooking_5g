
export default function isAdmin (_to, _from, next) {
    const isAuthenticated = window.localStorage.getItem('id_token');
    const isAdmin = window.localStorage.getItem('is_S_admin');
    if (isAuthenticated && isAdmin === 'true'){
        next();
    } else {
        next({ path: '/dashboard' });
    }
};