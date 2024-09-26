<template>
  <v-row>
    <v-col cols="12">
      <UiParentCard :title="project.name">
        <v-row>
          <v-col cols="12" lg="4" md="6">
            <v-text-field
              density="compact"
              v-model="search"
              :label="$t('jobs.searchJob')"
              prepend-inner-icon="mdi-magnify"
              variant="outlined"
              hide-details
            ></v-text-field>
          </v-col>

          <v-col cols="12" lg="8" md="6" class="text-right">
            <v-btn
              color="primary"
              :to="{
                name: 'execute-job',
                params: { projectId: $route.params.projectId },
              }"
              flat
              class="ml-auto"
            >
              <v-icon class="mr-2">mdi-database-sync</v-icon>
              {{ $t("jobs.executeJob") }}
            </v-btn>
          </v-col>
        </v-row>

        <v-data-table-server
          :headers="headers"
          :items="items"
          :loading="loading"
          :items-length="totalItems"
          v-model:items-per-page="itemsPerPage"
          @update:options="loadData"
          :search="search"
          @search="updateSearch"
          v-model:sort-by="sortBy"
          item-value="name"
          class="border rounded-md mt-5"
        >
          <template v-slot:item.name="{ item }">
            <RouterLink
              :to="{
                name: 'job-details',
                params: {
                  jobExecutionId: item.id,
                },
              }"
              class="text-primary text-decoration-none text-body-1 font-weight-medium"
            >
              {{ item.name }}
            </RouterLink>
          </template>
          <template v-slot:item.pending_jobs="{ item }">
            <v-chip
              rounded="lg"
              color="warning"
              class="font-weight-bold"
              size="small"
              >{{ item.pending_jobs }}</v-chip
            >
          </template>
          <template v-slot:item.failed_jobs="{ item }">
            <v-chip
              rounded="lg"
              color="error"
              class="font-weight-bold"
              size="small"
              >{{ item.failed_jobs }}</v-chip
            >
          </template>
          <template v-slot:item.status="{ item }">
            <v-chip
              rounded="md"
              class="font-weight-bold"
              :color="getStatusColor(item.status)"
              size="small"
              label
            >
              {{ $t("jobs.statuses." + item.status) }}
            </v-chip>

            <v-tooltip
              activator="parent"
              location="top"
              v-if="item.status === 'failed'"
            >
              <template v-slot:activator="{ props }">
                <v-icon>mdi-help-circle</v-icon>
              </template>
              <span>{{ item.response }}</span>
            </v-tooltip>
          </template>
          <template v-slot:item.created_at="{ item }">
            {{ new Date(item.created_at).toLocaleString() }}
          </template>
        </v-data-table-server>
      </UiParentCard>
    </v-col>
  </v-row>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { useI18n } from "vue-i18n";
import { useI18nTranslation } from "@/utils/i18nUtils";
import { useRoute } from "vue-router";
import { useProjectStore } from "@/stores/project";
import { useJobExecutionStore } from "@/stores/jobExecution";
import { getStatusColor } from "@/utils/common";
import UiParentCard from "@/components/UiParentCard.vue";

const { t } = useI18n();
const headers = ref([]);

//
const route = useRoute();
const store = useProjectStore();
const storeJobs = useJobExecutionStore();
const project = computed(() => store.project);
const projectId = ref(null);

const setTranslations = () => {
  // Table Headers
  headers.value = [
    {
      title: t("jobs.tableHeaderName"),
      align: "start",
      key: "name",
    },
    {
      title: t("jobs.tableHeaderTotal"),
      align: "start",
      key: "total_jobs",
    },
    {
      title: t("jobs.tableHeaderPending"),
      align: "start",
      key: "pending_jobs",
    },
    {
      title: t("jobs.tableHeaderFailed"),
      align: "start",
      key: "failed_jobs",
    },
    {
      title: t("jobs.tableHeaderStatus"),
      align: "start",
      key: "status",
    },
    {
      title: t("jobs.tableHeaderDate"),
      key: "created_at",
    },
  ];
};

useI18nTranslation(setTranslations);

watch(
  () => route.params.projectId,
  (newProjectId, oldProjectId) => {
    projectId.value = parseInt(newProjectId);
    if (!isNaN(projectId.value)) {
      store.getProjectById(projectId.value).then((response) => {
        document.title = t("projects.pageTitle") + " - " + project.value.name;
      });
    }
  },
  { immediate: true }
);

//
const search = ref("");
const sortBy = ref([]);
const sortColumn = ref("created_at");
const sortDirection = ref("desc");
const items = computed(() => storeJobs.jobExecutions);
const loading = computed(() => storeJobs.loading);
const totalItems = computed(() => storeJobs.totalItems);
const itemsPerPage = ref(100);
const currentPage = ref(0);

const loadData = ({
  page,
  itemsPerPage,
}: {
  page: number;
  itemsPerPage: number;
}) => {
  currentPage.value = page;
  storeJobs.getJobExecutions({
    projectId: projectId.value,
    page: page,
    itemsPerPage: itemsPerPage,
    search: search.value,
    sortColumn: sortColumn.value,
    sortDirection: sortDirection.value,
  });
};

const updateSearch = () => {
  loadData({
    page: 0,
    itemsPerPage: itemsPerPage.value,
  });
};

watch(itemsPerPage, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    loadData({
      page: 0,
      itemsPerPage: newValue,
    });
  }
});

watch(sortBy, (newValue, oldValue) => {
  if (newValue[0]) {
    sortColumn.value = newValue[0].key;
    sortDirection.value = newValue[0].order;
  }
});
</script>
