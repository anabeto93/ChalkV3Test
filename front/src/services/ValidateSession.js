import React, { Component } from 'react';
import { Redirect } from 'react-router-dom';
import { COURSES, HOME } from '../config/routes';

class ValidateSession extends Component {
  render() {
    const { validationCode } = this.props.match.params;

    return <Redirect to={COURSES} />;
  }
}

export default ValidateSession;
