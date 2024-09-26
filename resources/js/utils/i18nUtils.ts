import { watch } from 'vue';
import { useI18n } from 'vue-i18n';

export const useI18nTranslation = (setTranslations: any) => {
    const { t, locale } = useI18n({ useScope: 'global' });

    watch(
        () => locale.value,
        () => {
            setTranslations();
        }
    );

    setTranslations();
};
