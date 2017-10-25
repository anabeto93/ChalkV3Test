import I18n from 'i18n-js';
import { RaisedButton } from 'material-ui';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Redirect } from 'react-router-dom';
import { validateSession } from '../actions/actionCreators';
import Error from '../components/Error';
import CourseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';
import {
  SESSION_DETAIL,
  SESSION_LIST,
  SESSION_SEND_SMS
} from '../config/routes';
import store from '../store/store';

const DEFAULT_STATE = {
  sendMode: null,
  redirectToSessionList: false,
  hasNextSession: false,
  hasSubmit: false
};

class SendScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentWillReceiveProps(nextProps) {
    const nextSession = CourseManager.getNextSession(
      nextProps.sessions,
      nextProps.session
    );

    if (
      nextSession !== null &&
      !nextProps.isFailValidating &&
      !nextProps.isValidating
    ) {
      this.setState({ ...this.state, nextSession, hasNextSession: true });
    } else if (!nextProps.isValidating && !nextProps.isFailValidating) {
      this.setState({ ...this.state, redirectToSessionList: true });
    } else {
      this.setState({
        ...this.state,
        submitEnabled: true,
        hasSubmit: false,
        redirectToSessionList: false
      });
    }
  }

  handleRedirectNextSession = () => {
    return this.props.history.push(
      generateUrl(SESSION_DETAIL, {
        ':courseUuid': this.state.nextSession.courseUuid,
        ':sessionUuid': this.state.nextSession.uuid
      })
    );
  };

  handleRedirectSessionList = () => {
    this.props.history.push(
      generateUrl(SESSION_LIST, {
        ':courseUuid': this.state.nextSession.courseUuid,
        ':folderUuid': this.state.nextSession.folderUuid
      })
    );
  };

  handleSendByInternet = () => {
    this.setState({ ...this.state, hasSubmit: true });
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
    const { session, locale } = this.props;

    if (this.state.hasNextSession && !this.props.isFailValidating) {
      return (
        <div className="content-layout">
          <div className="content">
            <h4>Thank you for submitted your answers.</h4>
            <p>
              Your session was successfully validated and you can go to the next
              session.
            </p>
            <RaisedButton
              style={{ float: 'left' }}
              label="Back to the list"
              onClick={this.handleRedirectSessionList}
            />
            <RaisedButton
              className="button-primary"
              primary={true}
              onClick={this.handleRedirectNextSession}
              label="Next"
              labelPosition="before"
              icon={<Arrow />}
            />
          </div>
        </div>
      );
    }

    if (this.state.redirectToSessionList) {
      return (
        <Redirect
          to={generateUrl(SESSION_LIST, {
            ':courseUuid': session.courseUuid,
            ':folderUuid': session.folderUuid
          })}
        />
      );
    }

    return (
      <div>
        <div className="send-screen">
          <div className="content">
            <p>
              {I18n.t('send.label', { locale })}
            </p>

            <RaisedButton
              label={I18n.t('send.medium.internet', { locale })}
              disabled={this.state.hasSubmit}
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

        {this.props.isFailValidating &&
          <Error
            message={I18n.t('send.validation.fail', { locale })}
            show={this.props.isFailValidating}
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
