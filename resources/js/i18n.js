import { createI18n } from 'vue-i18n'

import en from '../../lang/en/i18n.json'
import ru from '../../lang/ru/i18n.json'

export const i18n = createI18n({
    legacy: false, // Composition API mode
    locale: import.meta.env.VITE_APP_LOCALE || 'en', // Use environment variable
    fallbackLocale: 'en',
    messages: {
        en,
        ru
    }
})
