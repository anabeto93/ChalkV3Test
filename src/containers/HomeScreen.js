import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Link } from 'react-router-dom';
import { COURSES } from '../config/routes';

export class HomeScreen extends Component {
  render() {
    if (this.props.loggedIn) {
      return (
        <div>
          <h1>You're logged</h1>
          <Link to={COURSES}>Courses</Link>
        </div>
      );
    }

    return (
      <div>
        Your link has a problem - please contact your Chalkboard Education for
        support
      </div>
    );
  }
}

function mapStateToProps({ currentUser: { loginState } }) {
  return { loggedIn: loginState === 'logged' };
}

export default connect(mapStateToProps)(HomeScreen);
