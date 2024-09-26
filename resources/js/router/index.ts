import { createRouter, createWebHistory } from "vue-router";

const router = createRouter({
  history: createWebHistory(import.meta.env.VITE_BASE_URL || "/"),
  routes: [
    {
      path: "/",
      name: "home",
      redirect: "/projects",
      component: () => import("@/layouts/full/FullLayout.vue"),
      children: [
        {
          name: "projects",
          path: "/projects",
          component: () => import("@/views/projects/Index.vue"),
        },
        {
          name: "project",
          path: "/project/:projectId",
          component: () => import("@/views/projects/Show.vue"),
        },
        {
          name: "job-details",
          path: "/jobs/details/:jobExecutionId?",
          component: () => import("@/views/jobs/Details.vue"),
        },
        {
          name: "execute-job",
          path: "/jobs/execute/:projectId?",
          component: () => import("@/views/jobs/Execute.vue"),
        },
      ],
    },
  ],
});

export default router;
