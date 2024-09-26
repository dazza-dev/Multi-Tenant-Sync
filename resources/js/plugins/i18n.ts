import { createI18n } from "vue-i18n";
import messages from "@/locales/messages";

let i18n;

export const SUPPORT_LOCALES = ["es", "en"];

export function setI18nLanguage(locale: string) {
  if (i18n.mode === "legacy") {
    i18n.global.locale = locale;
  } else {
    i18n.global.locale.value = locale;
  }
  localStorage.setItem("lang", locale);
}

export default function setupI18n() {
  if (!i18n) {
    let locale = localStorage.getItem("lang") || "es";

    i18n = createI18n({
      globalInjection: true,
      legacy: false,
      locale: locale,
      fallbackLocale: "es",
      messages: messages,
    });

    setI18nLanguage(locale);
  }
  return i18n;
}
