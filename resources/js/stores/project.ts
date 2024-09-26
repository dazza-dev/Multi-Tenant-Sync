import { defineStore } from "pinia";
import axios from "@/utils/axios";

interface Project {
  id: number;
  name: string;
  logo: string;
}

export const useProjectStore = defineStore({
  id: "Project",
  state: () => ({
    loading: false,
    project: {},
    projects: [],
    totalItems: 0,
  }),
  getters: {},
  actions: {
    // Get Projects
    async getProjects() {
      this.loading = true;
      try {
        const response = await axios.get("projects");
        this.projects = response.data.data;

        return response;
      } catch (error) {
        console.error("Error fetching projects:", error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Get Project By ID
    async getProjectById(projectId: number) {
      this.loading = true;
      try {
        const response = await axios.get("projects/" + projectId);
        this.project = response.data.project;

        return response;
      } catch (error) {
        console.error("Error fetching the project:", error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Create Project
    async createProject(projectInfo: Project) {
      this.loading = true;
      try {
        const response = await axios.post("projects", projectInfo);
        return { success: true, projectInfo: response.data.project };
      } catch (error) {
        console.error("Error creating project:", error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Update Project
    async updateProject(projectId: number, projectInfo: Project) {
      this.loading = true;
      try {
        const response = await axios.put("projects/" + projectId, projectInfo);
        return { success: true, projectInfo: response.data.project };
      } catch (error) {
        console.error("Error updating project:", error);
        throw error;
      } finally {
        this.loading = false;
      }
    },

    // Delete Project
    async deleteProject(projectId: number) {
      this.loading = true;
      try {
        const response = await axios.delete("projects/" + projectId);
        return { success: true, projectId: projectId };
      } catch (error) {
        throw error;
      } finally {
        this.loading = false;
      }
    },
  },
});
