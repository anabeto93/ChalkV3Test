import { connect } from 'react-redux';
import { Link } from 'react-router-dom';
import React, { Component } from 'react';

import LoginScreen from './LoginScreen';

export class HomeScreen extends Component {
  render() {
    return this.props.loggedIn
      ? <div>
          <h1>You're logged</h1>
          <Link to="/courses">Courses</Link>
        </div>
      : <LoginScreen />;
  }
}

function mapStateToProps({ currentUser: { loginState } }) {
  return { loggedIn: loginState === 'logged' };
}

export default connect(mapStateToProps)(HomeScreen);
