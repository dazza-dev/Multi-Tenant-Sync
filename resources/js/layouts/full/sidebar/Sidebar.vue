<template>
  <v-navigation-drawer
    left
    v-model="storeConfig.sidebarDrawer"
    elevation="0"
    rail-width="75"
    app
    class="leftSidebar"
    :rail="storeConfig.miniSidebar"
    expand-on-hover
    width="270"
  >
    <!---Logo part -->
    <div class="pa-5">
      <Logo />
    </div>

    <!-- ---------------------------------------------- -->
    <!---Navigation -->
    <!-- ---------------------------------------------- -->
    <perfect-scrollbar class="scrollnavbar">
      <v-list class="pa-6">
        <!---Menu Loop -->
        <template v-for="(item, i) in items">
          <!---Item Sub Header -->
          <NavGroup :item="item" v-if="item.header" :key="item.title" />
          <!---If Has Child -->
          <NavCollapse
            class="leftPadding"
            :item="item"
            :level="0"
            v-else-if="item.children"
          />
          <!---Single Item-->
          <NavItem :item="item" v-else class="leftPadding" />
          <!---End Single Item-->
        </template>
      </v-list>
    </perfect-scrollbar>
  </v-navigation-drawer>
</template>

<script setup lang="ts">
import { shallowRef, computed } from "vue";
import sidebarItems from "./sidebarItems";
import { useConfigStore } from "@/stores/config";
import { useProjectStore } from "@/stores/project";

//
import NavGroup from "./NavGroup.vue";
import NavItem from "./NavItem.vue";
import NavCollapse from "./NavCollapse.vue";
import Logo from "../Logo.vue";
import { CategoryIcon } from "vue-tabler-icons";

//
const storeConfig = useConfigStore();
const sidebarMenu = shallowRef(sidebarItems);

const projectStore = useProjectStore();
projectStore.getProjects();

//
const items = computed(() => {
  let items = projectStore.projects.map((project) => {
    return {
      title: project.name,
      icon: CategoryIcon,
      to: "/project/" + project.id,
    };
  });

  return sidebarItems.concat(items);
});
</script>
