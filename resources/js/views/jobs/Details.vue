<template>
  <v-row v-if="jobExecution.id">
    <v-col cols="12">
      <UiParentCard :title="jobExecution.name">
        <v-list density="compact">
          <v-list-item
            color="primary"
            :subtitle="$t('jobs.batch')"
            :title="jobExecution.batch_id"
          />
          <v-list-item
            color="primary"
            :subtitle="$t('jobs.name')"
            :title="jobExecution.name"
          />
          <v-list-item
            color="primary"
            :subtitle="$t('jobs.job')"
            :title="jobExecution.job"
          />
          <v-list-item
            color="primary"
            :subtitle="$t('jobs.date')"
            :title="new Date(jobExecution.created_at).toLocaleString()"
          />
        </v-list>

        <v-chip
          rounded="md"
          class="font-weight-bold mb-5"
          :color="getStatusColor(jobExecution.status)"
          size="small"
          label
        >
          {{ $t("jobs.statuses." + jobExecution.status) }}
        </v-chip>

        <v-alert v-if="jobExecution.response" color="error">{{
          jobExecution.response
        }}</v-alert>

        <div v-if="jobExecution.params" class="py-5">
          <HighCode :codeValue="jobExecution.params" width="100%" />
        </div>

        <v-row class="mt-5">
          <v-col cols="12" lg="4" md="4">
            <v-text-field
              density="compact"
              v-model="search"
              :label="$t('jobs.searchJob')"
              prepend-inner-icon="mdi-magnify"
              variant="outlined"
              hide-details
              clearable
            ></v-text-field>
          </v-col>
          <v-col cols="12" lg="4" md="4">
            <v-select
              density="compact"
              v-model="status"
              :items="statuses"
              :label="$t('jobs.filterByStatus')"
              variant="outlined"
              hide-details
              clearable
            ></v-select>
          </v-col>
          <v-col cols="12" lg="4" md="4" class="text-right">
            <v-btn color="success" @click="exportToExcel">
              <v-icon left>mdi-file-excel</v-icon>
              {{ $t("common.export") }}
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
          <template v-slot:item.success="{ item }">
            <v-chip
              v-if="item.success"
              rounded="md"
              class="font-weight-bold"
              color="success"
              size="small"
              label
              >{{ $t("jobs.statuses.completed") }}</v-chip
            >
            <v-chip
              v-else
              rounded="md"
              class="font-weight-bold"
              color="error"
              size="small"
              label
              >{{ $t("jobs.statuses.failed") }}</v-chip
            >
          </template>
          <template v-slot:item.response="{ item }">
            <v-dialog max-width="500">
              <template v-slot:activator="{ props: activatorProps }">
                <v-btn
                  v-bind="activatorProps"
                  color="surface-variant"
                  text="View Response"
                  variant="flat"
                ></v-btn>
              </template>

              <template v-slot:default="{ isActive }">
                <v-card title="Response">
                  <div class="px-5 py-5">
                    <HighCode :codeValue="item.response" width="100%" />
                  </div>
                  <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text="Close" @click="isActive.value = false"></v-btn>
                  </v-card-actions>
                </v-card>
              </template>
            </v-dialog>
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
import { ref, computed, watch, onUnmounted } from "vue";
import { useRoute } from "vue-router";
import { useI18n } from "vue-i18n";
import { useI18nTranslation } from "@/utils/i18nUtils";
import { useJobExecutionStore } from "@/stores/jobExecution";
import UiParentCard from "@/components/UiParentCard.vue";
import { getStatusColor } from "@/utils/common";
import { HighCode } from "vue-highlight-code";

//
const { t } = useI18n();
const headers = ref([]);

//
const route = useRoute();
const storeJobs = useJobExecutionStore();
const jobExecutionId = ref(null);
const jobExecution = computed(() => storeJobs.jobExecution);

const setTranslations = () => {
  // Table Headers
  headers.value = [
    {
      title: t("jobs.tableHeaderTenant"),
      align: "start",
      key: "tenant_name",
    },
    {
      title: t("jobs.tableHeaderStatus"),
      align: "start",
      key: "success",
    },
    {
      title: t("jobs.tableHeaderResponse"),
      align: "start",
      key: "response",
    },
    {
      title: t("jobs.tableHeaderDate"),
      key: "created_at",
    },
  ];
};

useI18nTranslation(setTranslations);

watch(
  () => route.params.jobExecutionId,
  (newJobExecutionId, oldJobExecutionId) => {
    jobExecutionId.value = parseInt(newJobExecutionId);
    if (!isNaN(jobExecutionId.value)) {
      storeJobs.getJobExecutionById(jobExecutionId.value).then((response) => {
        document.title = jobExecution.value.name;
      });
    }
  },
  { immediate: true }
);

//
const search = ref("");
const status = ref(null);
const statuses = ref([
  { title: t("jobs.statuses.all"), value: null },
  { title: t("jobs.statuses.completed"), value: 1 },
  { title: t("jobs.statuses.failed"), value: 0 },
]);
const sortBy = ref([]);
const sortColumn = ref("created_at");
const sortDirection = ref("desc");
const items = computed(() => storeJobs.jobExecutionsLogs);
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
  storeJobs.getJobExecutionsLogs({
    jobExecutionId: jobExecutionId.value,
    page: page,
    itemsPerPage: itemsPerPage,
    search: search.value,
    sortColumn: sortColumn.value,
    sortDirection: sortDirection.value,
    status: status.value,
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

watch(status, (newValue, oldValue) => {
  if (newValue !== oldValue) {
    loadData({
      page: 0,
      itemsPerPage: itemsPerPage.value,
    });
  }
});

const exportToExcel = () => {
  storeJobs.exportToExcel(jobExecutionId.value, {
    search: search.value,
    status: status.value,
  });
};

onUnmounted(() => {
  storeJobs.reset();
});
</script>
