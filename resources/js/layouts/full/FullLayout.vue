<template>
  <v-locale-provider>
    <v-app
      :theme="storeConfig.theme"
      :class="[
        storeConfig.theme,
        storeConfig.miniSidebar ? 'mini-sidebar' : '',
        'verticalLayout',
      ]"
    >
      <Sidebar />
      <Header />

      <v-main>
        <v-container fluid class="page-wrapper pb-sm-15 pb-10">
          <div class="maxWidth">
            <RouterView />
          </div>
        </v-container>
      </v-main>
    </v-app>
  </v-locale-provider>
</template>

<script setup lang="ts">
import { RouterView } from "vue-router";
import Sidebar from "./sidebar/Sidebar.vue";
import Header from "./header/Header.vue";
import { useConfigStore } from "@/stores/config";

const storeConfig = useConfigStore();
</script>
