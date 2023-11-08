<template>
    <div>
        <Loader v-if="loaderEnabled" v-bind:logo="loaderLogo"></Loader>
        <div class="row">
            <div class="mx-auto col-lg-12 col-md-12 col-sm-12 text-center">
                <img src="https://www.wallpapertip.com/wmimgs/76-760528_os-x-mavericks-scenery-wallpapers-hd-wallpapers-3800.jpg" alt="" class="img-responsive" height="175" />
            </div>
            <div class="mx-auto col-lg-10 col-md-10 col-sm-12">
                <transition name="fade-in-up">
                    <div class="card card-custom gutter-b example example-compact">
                        <div class="card-header">
                            <div class="card-toolbar w-100">
                                <ul class="nav nav-light-primary nav-pills w-100 justify-content-center d-table" style="table-layout: fixed;">
                                    <li class="nav-item d-table-cell float-none">
                                        <router-link to="/create-account/personal">
                                            <h6 href="#" target="_self" class="nav-link justify-content-center font-weight-bold" :class="{ 'active': $route.name === 'create-account-personal' }">Personal</h6>
                                        </router-link>
                                    </li>
                                    <li class="nav-item d-table-cell float-none">
                                        <router-link to="/create-account/company">
                                            <h6 href="#" target="_self" class="nav-link justify-content-center font-weight-bold" :class="{ 'active': this.$route.name === 'create-account-company' }">Empresa</h6>
                                        </router-link>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="card-body">
                            <router-view />
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import HtmlClass from "@/core/services/htmlclass.service";
import Loader from "@/view/content/Loader.vue";
import {
    ADD_BODY_CLASSNAME,
    REMOVE_BODY_CLASSNAME
} from "@/core/services/store/htmlclass.module.js";

export default {
    name: "Layout",
    components: {
        Loader
    },
    beforeMount() {
        // show page loading
        this.$store.dispatch(ADD_BODY_CLASSNAME, "page-loading");

        // initialize html element classes
        HtmlClass.init(this.layoutConfig());
    },
    mounted() {
        // Simulate the delay page loading
        setTimeout(() => {
            this.$store.dispatch(REMOVE_BODY_CLASSNAME, "page-loading");
        }, 2000);
    },
    methods: {},
    computed: {
        ...mapGetters([
            "layoutConfig"
        ]),
        /**
         * Check if the page loader is enabled
         * @returns {boolean}
         */
        loaderEnabled() {
            return !/false/.test(this.layoutConfig("loader.type"));
        },
        /**
         * Page loader logo image using require() function
         * @returns {string}
         */
        loaderLogo() {
            return process.env.BASE_URL + this.layoutConfig("loader.logo");
        },
    }
};
</script>