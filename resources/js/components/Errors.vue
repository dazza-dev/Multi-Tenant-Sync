<template>
  <v-messages color="red-darken-4" :messages="messages" :active="true" />
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps({
  errors: Array | Object,
});

const messages = computed(() => {
  const errorStrings = [];
  if (props.errors) {
    for (const prop in props.errors) {
      props.errors[prop].forEach((error) => {
        errorStrings.push(error.$message);
      });
    }
  }
  return errorStrings.join(", ");
});
</script>
