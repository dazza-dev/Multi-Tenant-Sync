import { defineStore } from "pinia";

export const useConfigStore = defineStore({
  id: "Config",
  state: () => ({
    theme: "BLUE_THEME",
    miniSidebar: false,
    sidebarDrawer: true,
  }),
  getters: {},
  actions: {
    SET_SIDEBAR_DRAWER() {
      this.sidebarDrawer = !this.sidebarDrawer;
    },
    SET_MINI_SIDEBAR() {
      this.miniSidebar = !this.miniSidebar;
    },
    SET_THEME(theme: string) {
      this.theme = theme;
    },
  },
});
