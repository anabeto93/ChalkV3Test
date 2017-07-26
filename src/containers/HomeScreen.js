import { connect } from 'react-redux';
import { Link } from 'react-router-dom';
import React, { Component } from 'react';
import store from '../store/store';
import { resetRoutingData } from '../actions/actionCreators';

import LoginScreen from './LoginScreen';

export class HomeScreen extends Component {
  componentDidMount() {
    store.dispatch(resetRoutingData());
  }

  render() {
    console.log('rendering HomeScreen');

    return this.props.loggedIn
      ? <div>
          <h1>Welcome!</h1>
          <p>This is init!!!</p>
          <Link to="/courses">Course</Link>
        </div>
      : <LoginScreen />;
  }
}

function mapStateToProps({ currentUser: { loginState } }) {
  return { loggedIn: loginState === 'logged' };
}

export default connect(mapStateToProps)(HomeScreen);
