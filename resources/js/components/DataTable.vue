<template>
  <v-data-table-server
    :headers="headers"
    :items="items"
    :loading="loading"
    :items-length="totalItems"
    v-model:items-per-page="localItemsPerPage"
    :search="search"
    @search="updateSearch"
    v-model:sort-by="sortBy"
    :item-value="itemValue"
    :class="tableClass"
  >
    <template
      v-for="slot in slots"
      :key="slot.key"
      v-slot:[`item.${slot.key}`]="{ item }"
    >
      <slot :name="`item.${slot.key}`" :item="item">{{ item[slot.key] }}</slot>
    </template>
    <template v-slot:item.actions="{ item }">
      <slot name="before-actions" :item="item"></slot>
      <v-tooltip :text="editButtonText">
        <template v-slot:activator="{ props }">
          <v-btn icon flat @click="editItem(item)" v-bind="props">
            <PencilIcon stroke-width="1.5" size="20" class="text-primary" />
          </v-btn>
        </template>
      </v-tooltip>
      <v-tooltip :text="deleteButtonText">
        <template v-slot:activator="{ props }">
          <v-btn icon flat @click="openDeleteModal(item)" v-bind="props">
            <TrashIcon stroke-width="1.5" size="20" class="text-error" />
          </v-btn>
        </template>
      </v-tooltip>
      <slot name="after-actions" :item="item"></slot>
    </template>
    <template v-slot:tfoot>
      <tfoot>
        <slot name="item.tfoot"></slot>
      </tfoot>
    </template>
  </v-data-table-server>

  <DeleteModal
    :show="dialogDelete"
    :title="deleteModalTitle"
    :confirmButtonText="deleteModalConfirmButtonText"
    :cancelButtonText="deleteModalCancelButtonText"
    @close="closeDeleteModal"
    @confirm="deleteItem"
  />
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from "vue";
import DeleteModal from "./DeleteModal.vue";

// Props
const props = defineProps({
  tableClass: {
    type: String,
    default: "border rounded-md mt-5",
  },
  headers: Array | Object,
  items: Array | Object,
  loading: {
    type: Boolean,
    default: false,
  },
  itemValue: {
    type: String,
    default: "id",
  },
  totalItems: {
    type: Number,
    default: 0,
  },
  itemsPerPage: {
    type: Number,
    default: 10,
  },
  search: String,

  // Delete Modal
  deleteButtonText: String,
  deleteModalTitle: String,
  deleteModalConfirmButtonText: String,
  deleteModalCancelButtonText: String,
  editButtonText: String,
});

// Emits
const emit = defineEmits(["onLoadData", "editItem", "deleteItem"]);

// Refs
const localItemsPerPage = ref(props.itemsPerPage);
const sortBy = ref([]);
const currentPage = ref(0);
const selectedItem = ref(null);
const dialogDelete = ref(false);

// Slots
const slots = computed(() => {
  return props.headers.filter((header) => header.key !== "actions");
});

// Load Data on Mounted
onMounted(() => {
  loadData({
    page: currentPage.value,
    itemsPerPage: localItemsPerPage.value,
  });
});

// Open Delete Modal
const openDeleteModal = (item) => {
  selectedItem.value = item;
  dialogDelete.value = true;
};

// Close Delete Modal
const closeDeleteModal = () => {
  selectedItem.value = null;
  dialogDelete.value = false;
};

// Edit Item Event
const editItem = (item) => {
  emit("editItem", item);
};

// Delete Item Event
const deleteItem = () => {
  emit("deleteItem", selectedItem);
  dialogDelete.value = false;
};

// Payload Event
const loadData = ({
  page,
  itemsPerPage,
}: {
  page: number;
  itemsPerPage: number;
}) => {
  currentPage.value = page;

  // Sort Params
  const sortParams = sortBy.value.map((sort) => ({
    column: sort.key,
    direction: sort.order,
  }));

  emit("onLoadData", {
    page: page,
    itemsPerPage: itemsPerPage,
    search: props.search,
    sortBy: sortParams,
  });
};

// Update Search
const updateSearch = () => {
  loadData({
    page: 0,
    itemsPerPage: localItemsPerPage.value,
  });
};

// Watch search
watch(
  () => props.search,
  (newValue, oldValue) => {
    if (newValue !== oldValue) {
      loadData({
        page: 0,
        itemsPerPage: localItemsPerPage.value,
      });
    }
  }
);

// Watch itemsPerPage
watch(localItemsPerPage, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    loadData({
      page: 0,
      itemsPerPage: newValue,
    });
  }
});

// Watch sortBy
watch(sortBy, (newValue, oldValue) => {
  loadData({
    page: 0,
    itemsPerPage: localItemsPerPage.value,
  });
});
</script>
