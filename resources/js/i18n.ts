import { createI18n } from 'vue-i18n'

import en from '../../lang/en/i18n.json'
import ru from '../../lang/ru/i18n.json'
import enLocale from '@fullcalendar/core/locales/en-gb'
import ruLocale from '@fullcalendar/core/locales/ru'

export const fullCalendarLocales = [enLocale, ruLocale] as const

const LOCALES = {
    en: 'en-gb',
    ru: 'ru'
} as const

type LocaleKey = keyof typeof LOCALES

export const i18n = createI18n({
    legacy: false, // Composition API mode
    locale: (import.meta.env.VITE_APP_LOCALE as LocaleKey) || 'en',
    fallbackLocale: 'en',
    messages: {
        en,
        ru
    }
})

export function getCurrentLocale(): string {
    const current = i18n.global.locale.value as LocaleKey
    return LOCALES[current] || LOCALES.en
}
