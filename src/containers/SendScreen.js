import I18n from 'i18n-js';
import { RaisedButton } from 'material-ui';
import React, { Component } from 'react';
import { connect } from 'react-redux';

import { validateSession } from '../actions/actionCreators';
import Error from '../components/Error';
import ValidatedSession from '../components/ValidatedSession';
import CourseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';
import { SESSION_SEND_SMS } from '../config/routes';
import store from '../store/store';

const DEFAULT_STATE = {
  nextSession: null,
  isSessionValided: false
};

class SendScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentWillReceiveProps(nextProps) {
    if (!this.props.session.validated && nextProps.session.validated) {
      const nextSession = CourseManager.getNextSession(
        nextProps.sessions,
        nextProps.session
      );

      this.setState({ ...this.state, nextSession, isSessionValided: true });
    }
  }

  handleSendByInternet = () => {
    this.setState({ ...this.state });
    store.dispatch(validateSession(this.props.session.uuid));
  };

  handleSendBySms = () => {
    return this.props.history.push(
      generateUrl(SESSION_SEND_SMS, {
        ':courseUuid': this.props.session.courseUuid,
        ':sessionUuid': this.props.session.uuid
      })
    );
  };

  render() {
    const { session, locale, isValidating, isFailValidating } = this.props;

    if (this.state.isSessionValided) {
      return (
        <ValidatedSession
          session={session}
          nextSession={this.state.nextSession}
          local={locale}
        />
      );
    }

    return (
      <div>
        <div className="screen-centered">
          <div className="content">
            <p>
              {I18n.t('send.label', { locale })}
            </p>

            <RaisedButton
              label={I18n.t('send.medium.internet', { locale })}
              disabled={isValidating}
              onClick={this.handleSendByInternet}
              style={{ marginRight: '10px', width: '40%' }}
            />

            <RaisedButton
              label={I18n.t('send.medium.sms', { locale })}
              onClick={this.handleSendBySms}
              style={{ width: '40%' }}
            />
          </div>
        </div>

        {isFailValidating &&
          <Error
            message={I18n.t('send.validation.fail', { locale })}
            show={true}
          />}
      </div>
    );
  }
}

function mapStateToProps(state, props) {
  const session = CourseManager.getSession(
    state.content.sessions,
    props.match.params.sessionUuid
  );

  return {
    sessions: state.content.sessions,
    session,
    isValidating: state.content.isSessionValidating,
    isFailValidating: state.content.isSessionFailValidating,
    locale: state.settings.locale
  };
}

export default connect(mapStateToProps)(SendScreen);
