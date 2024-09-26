<template>
  <div class="logo">
    <RouterLink to="/">
      <img
        v-if="!storeConfig.miniSidebar"
        src="@/assets/images/logo.png"
        alt="logo"
        class="d-block w-100"
      />
      <img
        v-else
        src="@/assets/images/logo-symbol.png"
        alt="logo"
        class="d-block height-40"
      />
    </RouterLink>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { RouterLink } from "vue-router";
import { useConfigStore } from "@/stores/config";

//
const storeConfig = useConfigStore();
</script>

<style lang="scss" scoped>
.height-40 {
  height: 40px;
}
</style>
