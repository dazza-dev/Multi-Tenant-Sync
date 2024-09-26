<template>
  <v-row justify="center" align="center" class="fill-height">
    <v-col cols="6">
      <UiParentCard :title="$t('jobs.executeJob')">
        <v-form ref="form" v-model="valid" lazy-validation>
          <v-select
            v-if="!$route.params.projectId"
            v-model="editedItem.projectId"
            :label="$t('jobs.selectProject')"
            :items="projects"
            variant="solo"
            item-value="id"
            item-title="name"
            clearable
            @blur="v$.projectId.$touch"
            @input="v$.projectId.$touch"
            :error-messages="v$.projectId.$errors.map((e: any) => e.$message)"
          >
            <template v-slot:item="{ props, item }">
              <v-list-item
                v-bind="props"
                :subtitle="item.raw.db_host"
              ></v-list-item> </template
          ></v-select>

          <v-select
            v-model="editedItem.jobId"
            :label="$t('jobs.selectJob')"
            :items="jobsAvailable"
            variant="solo"
            clearable
            @blur="v$.jobId.$touch"
            @input="v$.jobId.$touch"
            :error-messages="v$.jobId.$errors.map((e: any) => e.$message)"
          >
            <template v-slot:item="{ props, item }">
              <v-list-item
                v-bind="props"
                :subtitle="item.raw.description"
              ></v-list-item> </template
          ></v-select>

          <v-text-field
            v-if="editedItem.jobId"
            variant="solo"
            hide-details
            v-model="editedItem.name"
            :label="$t('jobs.jobName')"
            class="mb-5"
          ></v-text-field>

          <v-textarea
            v-if="selectedJob.allow_params"
            v-model="editedItem.params"
            :label="$t('jobs.jobParams')"
            variant="solo"
            clearable
          ></v-textarea>
        </v-form>

        <v-btn
          color="primary"
          flat
          class="mt-5"
          @click="executeJob()"
          :disabled="loading"
        >
          <v-icon class="mr-2">mdi-database-sync</v-icon>
          {{ $t("jobs.executeJob") }}
        </v-btn>
      </UiParentCard>
    </v-col>
  </v-row>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { useI18n } from "vue-i18n";
import { notify } from "@/utils/common";
import { useVuelidate } from "@vuelidate/core";
import { email, required } from "@vuelidate/validators";
import { useRoute, useRouter } from "vue-router";
import { useProjectStore } from "@/stores/project";
import { useJobExecutionStore } from "@/stores/jobExecution";
import UiParentCard from "@/components/UiParentCard.vue";

// Translation function
const { t } = useI18n();
const pageTitle = ref({ title: t("jobs.executeJob") });
document.title = pageTitle.value.title;

//
const route = useRoute();
const router = useRouter();
const storeProjects = useProjectStore();
const storeJobs = useJobExecutionStore();

storeJobs.getJobsAvailable();
storeProjects.getProjects();

//
const valid = ref(true);
const loading = computed(() => storeJobs.loading);
const jobsAvailable = computed(() => storeJobs.jobsAvailable);
const projects = computed(() => storeProjects.projects);
const selectedJob = computed(
  () =>
    jobsAvailable.value.find((job) => job.value === editedItem.value.jobId) ??
    {}
);

const editedItem = ref({
  jobId: null,
  projectId: null,
  name: null,
  params: null,
});

const defaultItem = ref({
  jobId: null,
  projectId: null,
  name: null,
  params: null,
});

const v$ = useVuelidate(
  {
    projectId: { required },
    jobId: { required },
  },
  editedItem
);

const executeJob = () => {
  if (v$.value.$invalid) {
    notify("error", t("common.fieldsValidationError"));
    return;
  }

  storeJobs
    .executeJob(
      editedItem.value.projectId,
      editedItem.value.jobId,
      editedItem.value.name,
      editedItem.value.params
    )
    .then((response) => {
      clear();
      notify("success", t("jobs.jobExecutedSuccessfully"));

      router.push({
        name: "job-details",
        params: {
          jobExecutionId: response.jobExecution.id,
        },
      });
    })
    .catch((error) => {
      notify("error", t("jobs.errorExecutingJob") + ": " + error.message);
    });
};

function clear() {
  v$.value.$reset();
  setTimeout(() => {
    editedItem.value = Object.assign({}, defaultItem.value);
  }, 300);
}

watch(
  () => route.params.projectId,
  (newProjectId, oldProjectId) => {
    editedItem.value.projectId = parseInt(newProjectId);
    if (isNaN(editedItem.value.projectId)) {
      editedItem.value.projectId = null;
    }
  },
  { immediate: true }
);
</script>
