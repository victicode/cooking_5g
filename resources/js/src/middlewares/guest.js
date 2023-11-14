export default function guest ({ next, store }){
    store.getters.isAuthenticated ? next({ name: 'dashboard' }) : next();
}