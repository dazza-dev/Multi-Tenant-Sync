<template>
  <v-row>
    <v-col cols="12">
      <UiParentCard :title="$t('projects.tableTitle')">
        <v-row>
          <v-col cols="12" lg="4" md="6">
            <v-text-field
              density="compact"
              v-model="search"
              :label="$t('projects.searchProject')"
              prepend-inner-icon="mdi-magnify"
              variant="outlined"
              hide-details
            ></v-text-field>
          </v-col>
          <v-col cols="12" lg="8" md="6" class="text-right">
            <CreateProject
              :projectId="projectId"
              :dialogEdit="dialogEdit"
              @closeEditModal="closeEditModal"
              @refreshDatatable="refreshDatatable()"
            />
          </v-col>
        </v-row>

        <DataTable
          :headers="headers"
          :items="items"
          :loading="loading"
          :search="search"
          :totalItems="totalItems"
          @onLoadData="loadData"
          @editItem="editProject"
          @deleteItem="deleteProject"
          :deleteButtonText="$t('common.delete')"
          :deleteModalTitle="t('projects.deleteConfirmation')"
          :deleteModalConfirmButtonText="$t('common.confirmDelete')"
          :deleteModalCancelButtonText="$t('common.cancel')"
          :editButtonText="$t('common.edit')"
        >
          <template v-slot:item.name="{ item }">
            <RouterLink
              :to="{
                name: 'project',
                params: {
                  projectId: item.id,
                },
              }"
              class="d-flex align-center text-decoration-none text-body-1 font-weight-medium"
            >
              <v-avatar size="35" v-if="item.logo">
                <v-img :alt="item.name" :src="item.logo"></v-img>
              </v-avatar>
              <v-avatar
                size="35"
                :class="'ml-n2 avtar-border bg-' + randomColor()"
                v-else
              >
                {{ getInitials(item.name) }}
              </v-avatar>

              <div class="ml-4">
                <h6 class="text-h6">{{ item.name }}</h6>
              </div>
            </RouterLink>
          </template>
        </DataTable>
      </UiParentCard>
    </v-col>
  </v-row>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { useI18n } from "vue-i18n";
import { useI18nTranslation } from "@/utils/i18nUtils";
import CreateProject from "./Create.vue";
import { useProjectStore } from "@/stores/project";
import { notify, randomColor, getInitials } from "@/utils/common";
import UiParentCard from "@/components/UiParentCard.vue";
import DataTable from "@/components/DataTable.vue";

//
const { t } = useI18n();
const pageTitle = ref({ title: t("projects.pageTitle") });
document.title = pageTitle.value.title;
const headers = ref([]);

//
const store = useProjectStore();
const dialogEdit = ref(false);
const projectId = ref(-1);
const search = ref("");
const items = computed(() => store.projects);
const loading = computed(() => store.loading);
const totalItems = computed(() => store.totalItems);

//
const setTranslations = () => {
  // Page Title
  pageTitle.value = { title: t("projects.pageTitle") };

  // Table Headers
  headers.value = [
    {
      title: t("projects.tableHeaderName"),
      align: "start",
      key: "name",
    },
    {
      title: t("projects.tableHeaderEndPoint"),
      align: "start",
      key: "db_host",
    },
    {
      title: t("common.actions"),
      key: "actions",
      sortable: false,
    },
  ];
};

useI18nTranslation(setTranslations);

const refreshDatatable = () => {
  loadData({ page: 0, itemsPerPage: 10 });
};

const loadData = () => {
  store.getProjects();
};

function editProject(project: Array<any>) {
  projectId.value = project.id;
  dialogEdit.value = true;
}

function closeEditModal() {
  projectId.value = -1;
  dialogEdit.value = false;
}

function deleteProject(project: Array<any>) {
  store
    .deleteProject(project.value.id)
    .then((response) => {
      refreshDatatable();
      notify("success", t("projects.deletedProjectSuccessfully"));
    })
    .catch((error) => {
      notify(
        "error",
        t("projects.errorDeletingProject") + ": " + error.message
      );
    });
}
</script>
