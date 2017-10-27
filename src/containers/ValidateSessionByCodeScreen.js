import I18n from 'i18n-js';
import React, { Component } from 'react';
import { connect } from 'react-redux';

import { receiveValidateSessionSMS } from '../actions/actionCreators';
import getConfig from '../config/index';
import store from '../store/store';
import CourseManager from '../services/CourseManager';
import UnBlockSession from '../services/session/UnBlockSession';
import ValidatedSession from '../components/ValidatedSession';

const DEFAULT_STATE = {
  session: null,
  isInvalidCode: true
};

class ValidateSessionByCodeScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentDidMount() {
    const { validationCode } = this.props.match.params;

    const userUuid = UnBlockSession.getUserUuidFromCode(
      validationCode,
      getConfig().apiPrivateKey
    );

    if (userUuid !== this.props.userUuid) {
      return;
    }

    const sessionUuid = UnBlockSession.getSessionUuidFromCode(
      validationCode,
      getConfig().apiPrivateKey
    );

    const session = CourseManager.getSession(this.props.sessions, sessionUuid);

    if (session === undefined) {
      return;
    }

    this.setState({ session: session, isInvalidCode: false });
    store.dispatch(receiveValidateSessionSMS(session.uuid));
  }

  render() {
    const { session } = this.state;
    const { sessions, locale } = this.props;

    if (this.state.isInvalidCode) {
      return (
        <div className="screen-centered">
          {I18n.t('tokenError', { locale })}
        </div>
      );
    }

    const nextSession = CourseManager.getNextSession(sessions, session);

    return (
      <ValidatedSession
        session={session}
        nextSession={nextSession}
        locale={locale}
      />
    );
  }
}

const mapStateToProps = state => {
  const {
    currentUser: { uuid: userUuid },
    content: { sessions },
    settings: { locale }
  } = state;
  return { sessions, locale, userUuid };
};

export default connect(mapStateToProps)(ValidateSessionByCodeScreen);
