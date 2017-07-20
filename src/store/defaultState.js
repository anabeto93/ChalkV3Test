const defaultState = {
  currentUser: {
    // temporarly set user is logged and has a token
    loginState: 'logged',
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
    sizeToDownload: 0
  }
};

export default defaultState;
