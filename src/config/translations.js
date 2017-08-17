import I18n from 'i18n-js';
import fr from './translate/fr.json';
import en from './translate/en.json';

export const availableLocales = { fr: 'Français', en: 'English' };
export const defaultLocale = 'en';

I18n.fallbacks = true;
I18n.defaultLocale = defaultLocale;
I18n.translations = { fr, en };
