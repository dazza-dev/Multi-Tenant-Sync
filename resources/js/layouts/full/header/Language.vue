<template>
  <v-menu :close-on-content-click="false" location="bottom">
    <template v-slot:activator="{ props }">
      <v-btn icon variant="text" color="primary" v-bind="props">
        <v-avatar size="22">
          <img
            v-if="$i18n.locale === 'en'"
            :src="flagEn"
            :alt="$i18n.locale"
            width="22"
            height="22"
            class="obj-cover"
          />
          <img
            v-if="$i18n.locale === 'es'"
            :src="flagEs"
            :alt="$i18n.locale"
            width="22"
            height="22"
            class="obj-cover"
          />
        </v-avatar>
      </v-btn>
    </template>
    <v-sheet rounded="md" width="200" elevation="10">
      <v-list class="theme-list">
        <v-list-item
          v-for="(item, index) in languageDD"
          :key="index"
          color="primary"
          :active="$i18n.locale == item.value"
          class="d-flex align-center"
          @click="setI18nLanguage(item.value)"
        >
          <template v-slot:prepend>
            <v-avatar size="22">
              <img
                :src="item.avatar"
                :alt="item.avatar"
                width="22"
                height="22"
                class="obj-cover"
              />
            </v-avatar>
          </template>
          <v-list-item-title class="text-subtitle-1 font-weight-regular">
            {{ item.title }}
            <span class="text-disabled text-subtitle-1 pl-2"
              >({{ item.subtext }})</span
            >
          </v-list-item-title>
        </v-list-item>
      </v-list>
    </v-sheet>
  </v-menu>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import { useI18n } from "vue-i18n";
import { setI18nLanguage } from "@/plugins/i18n";
import flagEn from "@/assets/images/flag/icon-flag-en.svg";
import flagEs from "@/assets/images/flag/icon-flag-es.svg";
import type { languageType } from "@/types/HeaderTypes";

const languageDD: languageType[] = [
  { title: "English", subtext: "US", value: "en", avatar: flagEn },
  { title: "Español", subtext: "ES", value: "es", avatar: flagEs },
];

const { locale } = useI18n({ useScope: "global" });
watch(locale, (newLocale) => {
  setI18nLanguage(newLocale);
});
</script>
