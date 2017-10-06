import React, { Component } from 'react';
import { Redirect } from 'react-router-dom';
import { HOME } from '../config/routes';

class ValidateSession extends Component {
  render() {
    const { validationCode } = this.props.match.params;

    return <Redirect to={HOME} />;
  }
}

export default ValidateSession;
