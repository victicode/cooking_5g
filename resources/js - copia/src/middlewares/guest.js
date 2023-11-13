export default function guest ({ next, store }){
    store.getters.isAuthenticated ? next({ name: 'home' }) : next();
}