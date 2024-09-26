import { CategoryIcon, DatabaseCogIcon } from "vue-tabler-icons";

export interface menu {
  header?: string;
  title?: string;
  icon?: any;
  to?: string;
  chip?: string;
  chipBgColor?: string;
  chipColor?: string;
  chipVariant?: string;
  chipIcon?: string;
  children?: menu[];
  disabled?: boolean;
  type?: string;
  subCaption?: string;
}

const sidebarItems: menu[] = [
  {
    title: "sidebar.projects",
    icon: CategoryIcon,
    to: "/projects",
  },
  {
    title: "sidebar.executeJob",
    icon: DatabaseCogIcon,
    to: "/jobs/execute",
  },
];

export default sidebarItems;
