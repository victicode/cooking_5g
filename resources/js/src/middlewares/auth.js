export default function auth ({ next, store }){
    !store.getters.isAuthenticated ? next({ name: 'Login' }) : next();
}