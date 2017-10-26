import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Redirect } from 'react-router-dom';

import {
  failValidateSessionSMS,
  receiveValidateSessionSMS
} from '../actions/actionCreators';
import getConfig from '../config/index';
import { COURSES, SESSION_DETAIL, SESSION_LIST } from '../config/routes';
import store from '../store/store';
import CourseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';
import UnBlockSession from '../services/session/UnBlockSession';

const DEFAULT_STATE = {
  session: null
};

class ValidateSessionScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  redirectNextSession = session => {
    return generateUrl(SESSION_DETAIL, {
      ':courseUuid': session.courseUuid,
      ':sessionUuid': session.uuid
    });
  };

  redirectSessionList = session => {
    return generateUrl(SESSION_LIST, {
      ':courseUuid': session.courseUuid,
      ':folderUuid': session.folderUuid
    });
  };

  componentDidMount() {
    const { validationCode } = this.props.match.params;

    const sessionUuid = UnBlockSession.getSessionUuidFromCode(
      validationCode,
      getConfig().apiPrivateKey
    );

    const session = CourseManager.getSession(this.props.sessions, sessionUuid);
    store.dispatch(receiveValidateSessionSMS(session.uuid));

    this.setState({ session: session });
  }

  render() {
    const { session } = this.state;

    if (session === null || session === undefined) {
      store.dispatch(failValidateSessionSMS());

      return <Redirect to={COURSES} />;
    }

    let nextSession = CourseManager.getNextSession(
      this.props.sessions,
      session
    );

    if (nextSession !== null) {
      return <Redirect to={this.redirectNextSession(nextSession)} />;
    }

    return <Redirect to={this.redirectSessionList(session)} />;
  }
}

const mapStateToProps = state => {
  const { content: { sessions } } = state;
  return { sessions };
};

export default connect(mapStateToProps)(ValidateSessionScreen);
