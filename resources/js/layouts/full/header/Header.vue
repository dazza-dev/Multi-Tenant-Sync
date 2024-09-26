<template>
  <v-app-bar elevation="10" :priority="priority" height="70" class="">
    <v-btn
      class="hidden-md-and-down"
      icon
      color="primary"
      variant="text"
      @click.stop="storeConfig.SET_MINI_SIDEBAR"
    >
      <Menu2Icon size="20" stroke-width="1.5" />
    </v-btn>
    <v-btn
      class="hidden-lg-and-up"
      icon
      variant="flat"
      @click.stop="storeConfig.SET_SIDEBAR_DRAWER"
      size="small"
    >
      <Menu2Icon size="20" stroke-width="1.5" />
    </v-btn>

    <v-spacer />

    <Language />
  </v-app-bar>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import { Menu2Icon } from "vue-tabler-icons";
import Language from "./Language.vue";

//
import { useConfigStore } from "@/stores/config";
const storeConfig = useConfigStore();

const showSearch = ref(false);
const priority = ref(0);

watch(priority, (newPriority) => {
  priority.value = newPriority;
});
</script>
