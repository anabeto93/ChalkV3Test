const defaultState = {
  currentUser: {
    // temporarly set user is logged and has a token
    loginState: 'logged',
    token: 'api-key-token-user-1'
  },
  courses: {
    isFetching: false,
    items: []
  }
};

export default defaultState;
