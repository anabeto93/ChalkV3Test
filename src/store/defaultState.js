const defaultState = {
  network: {
    isOnline: true
  },
  currentUser: {
    // temporarly set user is logged and has a token
    loginState: 'logged',
    token: 'api-key-token-user-1'
  },
  courses: {
    isFetching: false,
    isErrorFetching: false,
    items: []
  },
  updates: {
    isFetching: false,
    isErrorFetching: false,
    hasUpdates: false,
    dateLastCheck: null,
    size: 0
  }
};

export default defaultState;
