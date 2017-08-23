export const LOGIN_STATE_LOGGED_IN = 'logged-in';
export const LOGIN_STATE_LOGGED_OUT = 'logged-out';

const defaultState = {
  currentUser: {
    loginState: LOGIN_STATE_LOGGED_OUT,
    token: 'api-key-token-user-1'
  },
  courses: {
    isFetching: false,
    items: []
  },
  updates: {
    isFetching: false,
    hasUpdates: false,
    dateLastCheck: null,
    size: 0
  }
};

export default defaultState;
