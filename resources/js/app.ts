import "@/scss/style.scss";
import "@mdi/font/css/materialdesignicons.css";
import "vue3-toastify/dist/index.css";
import "animate.css";

import { createApp } from "vue";
import { createPinia } from "pinia";

import App from "./App.vue";
import router from "./router";

// Perfect Scrollbar
import { PerfectScrollbarPlugin } from "vue3-perfect-scrollbar";

// Tabler Icons
import VueTablerIcons from "vue-tabler-icons";

// Toasty
import Vue3Toasity, { type ToastContainerOptions } from "vue3-toastify";
const toastOptions: ToastContainerOptions = {
  position: "top-center",
  autoClose: 5000,
  hideProgressBar: false,
  closeOnClick: true,
  pauseOnHover: true,
  theme: "colored",
};

// Vuetify
import vuetify from "./plugins/vuetify";
import i18n from "./plugins/i18n";

// Create App
const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(i18n());
app.use(vuetify);
app.use(VueTablerIcons);
app.use(PerfectScrollbarPlugin);
app.use(Vue3Toasity, toastOptions);

app.mount("#app");
