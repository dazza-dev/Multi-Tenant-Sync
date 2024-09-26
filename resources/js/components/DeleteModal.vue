<template>
  <v-dialog v-model="dialog" max-width="500px">
    <v-card>
      <v-card-title class="text-h5 text-center py-6">
        {{ title }}
      </v-card-title>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" variant="flat" dark @click="$emit('close')">{{
          cancelButtonText
        }}</v-btn>
        <v-btn color="success" variant="flat" dark @click="$emit('confirm')">{{
          confirmButtonText
        }}</v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps({
  show: Boolean,
  title: String,
  confirmButtonText: String,
  cancelButtonText: String,
});

const dialog = computed(() => props.show);

//
const emit = defineEmits(["close", "confirm"]);
</script>
