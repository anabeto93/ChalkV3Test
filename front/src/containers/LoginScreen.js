import React, { Component } from 'react';
import { connect } from 'react-redux';

import { login } from '../actions/actionCreators';

export class LoginScreen extends Component {
  handleSubmit = event => {
    event.preventDefault();
    this.props.dispatch(login());
  };

  render() {
    return (
      <div>
        <form onSubmit={this.handleSubmit}>
          <button type="submit">Login</button>
        </form>
      </div>
    );
  }
}

const mapStateToProps = ({ currentUser: { loginState } }) => ({ loginState });

export default connect(mapStateToProps)(LoginScreen);
