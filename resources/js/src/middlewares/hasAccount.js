export default function hasAccount ({ next, store }){
    window.localStorage.getItem("has_account") == 'false' ? next({ path: '/create-account' }) : next();
}