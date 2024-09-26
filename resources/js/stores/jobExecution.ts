import { defineStore } from "pinia";
import axios from "@/utils/axios";

interface JobExecution {
  id: number;
  name: string;
  details: string;
  params: [string];
}

export const useJobExecutionStore = defineStore({
  id: "JobExecution",
  state: () => ({
    loading: false,
    jobExecution: {},
    jobExecutions: [],
    jobExecutionsLogs: [],
    jobsAvailable: [],
    totalItems: 0,
  }),
  getters: {},
  actions: {
    // Get Jobs Available
    async getJobsAvailable() {
      this.loading = true;
      try {
        const response = await axios.get("jobs-available");
        this.jobsAvailable = response.data.jobsAvailable;

        return response;
      } catch (error) {
        console.error("Error fetching jobs available:", error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Get Job Executions
    async getJobExecutions({
      projectId,
      page,
      itemsPerPage,
      search,
      sortColumn,
      sortDirection,
    }: {
      projectId: number;
      page: number;
      itemsPerPage: number;
      search: string;
      sortColumn: string;
      sortDirection: string;
    }) {
      this.loading = true;
      try {
        const response = await axios.get("job-executions/" + projectId, {
          params: {
            page: page,
            per_page: itemsPerPage,
            search: search,
            sortColumn: sortColumn,
            sortDirection: sortDirection,
          },
        });
        this.jobExecutions = response.data.data;
        this.totalItems = response.data.total;

        return response;
      } catch (error) {
        console.error("Error fetching job executions:", error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Get Job Executions Logs
    async getJobExecutionsLogs({
      jobExecutionId,
      page,
      itemsPerPage,
      search,
      sortColumn,
      sortDirection,
    }: {
      jobExecutionId: number;
      page: number;
      itemsPerPage: number;
      search: string;
      sortColumn: string;
      sortDirection: string;
    }) {
      this.loading = true;
      try {
        const response = await axios.get(
          "job-executions/logs/" + jobExecutionId,
          {
            params: {
              page: page,
              per_page: itemsPerPage,
              search: search,
              sortColumn: sortColumn,
              sortDirection: sortDirection,
            },
          }
        );
        this.jobExecutionsLogs = response.data.data;
        this.totalItems = response.data.total;

        return response;
      } catch (error) {
        console.error("Error fetching job executions logs:", error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Get Job Execution By ID
    async getJobExecutionById(jobExecutionId: number) {
      this.loading = true;
      try {
        const response = await axios.get(
          "job-executions/show/" + jobExecutionId
        );
        this.jobExecution = response.data.jobExecution;

        return response;
      } catch (error) {
        console.error("Error fetching the job execution:", error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Execute Job
    async executeJob(
      projectId: number,
      jobId: string,
      name: string,
      params: string
    ) {
      this.loading = true;
      try {
        const response = await axios.post("execute-job/" + projectId, {
          job: jobId,
          name: name,
          params: params,
        });
        return { success: true, jobExecution: response.data.jobExecution };
      } catch (error) {
        console.error("Error executing job:", error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    reset() {
      this.jobExecution = {};
      this.jobExecutions = [];
      this.jobExecutionsLogs = [];
      this.jobsAvailable = [];
      this.totalItems = 0;
    },
  },
});
