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
import CourseManager from './CourseManager';
import generateUrl from './generateUrl';
import UnBlockSession from './session/UnBlockSession';

class ValidateSession extends Component {
  redirectNextSession = session => {
    return generateUrl(SESSION_DETAIL, {
      ':courseUuid': session.courseUuid,
      ':sessionUuid': session.uuid
    });
  };

  redirectSessionList = session => {
    return generateUrl(SESSION_LIST, {
      ':courseUuid': session.courseUuid,
      ':folderUuid': session.folderUUid
    });
  };

  render() {
    const { validationCode } = this.props.match.params;

    const sessionUuid = UnBlockSession.getSessionUuidFromCode(
      validationCode,
      getConfig().apiPrivateKey
    );

    const session = CourseManager.getSession(this.props.sessions, sessionUuid);

    if (session === null) {
      store.dispatch(failValidateSessionSMS);

      return <Redirect to={COURSES} />;
    }

    store.dispatch(receiveValidateSessionSMS(sessionUuid));

    const nextSession = CourseManager.getNextSession(
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

export default connect(mapStateToProps)(ValidateSession);
