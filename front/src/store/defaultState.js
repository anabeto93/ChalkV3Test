import { defaultLocale } from '../config/translations';

export const LOGIN_STATE_LOGGED_IN = 'logged-in';
export const LOGIN_STATE_LOGGED_OUT = 'logged-out';

const defaultState = {
  network: {
    isOnline: true
  },
  currentUser: {
    loginState: LOGIN_STATE_LOGGED_OUT,
    token: 'api-key-token-user-1'
  },
  content: {
    isFetching: false,
    isErrorFetching: false,
    updatedAt: null,
    courses: {},
    folders: {},
    sessions: {},
    spool: {
      sessionText: [],
      sessionFiles: [],
      total: 0
    }
  },
  updates: {
    isFetching: false,
    isErrorFetching: false,
    hasUpdates: false,
    dateLastCheck: null,
    size: 0
  },
  settings: {
    locale: defaultLocale
  }
};

export default defaultState;
