export const LOGIN_STATE_LOGIN = 'logged';
export const LOGIN_STATE_LOGOUT = 'logout';

const defaultState = {
  currentUser: {
    loginState: LOGIN_STATE_LOGOUT,
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
