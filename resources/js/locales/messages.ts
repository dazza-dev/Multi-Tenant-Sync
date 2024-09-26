import { en as enVuetify, es as esVuetify } from "vuetify/locale";

// English
import enCommon from "./en/common.json";
import enSidebar from "./en/sidebar.json";
import enProjects from "./en/projects.json";
import enJobs from "./en/jobs.json";

// Spanish
import esCommon from "./es/common.json";
import esSidebar from "./es/sidebar.json";
import esProjects from "./es/projects.json";
import esJobs from "./es/jobs.json";

const messages = {
  en: {
    $vuetify: enVuetify,
    common: enCommon,
    sidebar: enSidebar,
    projects: enProjects,
    jobs: enJobs,
  },
  es: {
    $vuetify: esVuetify,
    common: esCommon,
    sidebar: esSidebar,
    projects: esProjects,
    jobs: esJobs,
  },
};

export default messages;
