import { RaisedButton } from 'material-ui';
import { RadioButton, RadioButtonGroup } from 'material-ui/RadioButton';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import I18n from 'i18n-js';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Redirect } from 'react-router-dom';
import { validateSession } from '../actions/actionCreators';
import Error from '../components/Error';
import CourseManager from '../services/CourseManager';
import store from '../store/store';
import generateUrl from '../services/generateUrl';
import {
  SESSION_DETAIL,
  SESSION_LIST,
  SESSION_SEND_SMS
} from '../config/routes';

const DEFAULT_STATE = {
  sendMode: null,
  submitEnabled: false,
  redirectToSessionList: false,
  hasNextSession: false,
  hasSubmit: false
};
const SEND_MODE_INTERNET = 'internet';
const SEND_MODE_SMS = 'sms';

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

  handleFormChange = (event, value) => {
    this.setState({ ...this.state, sendMode: value, submitEnabled: true });
  };

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

  handleRedirectSendSMS = (courseUuid, sessionUuid) => {
    return this.props.history.push(
      generateUrl(SESSION_SEND_SMS, {
        ':courseUuid': courseUuid,
        ':sessionUuid': sessionUuid
      })
    );
  };

  handleFormSubmit = () => {
    this.setState({ ...this.state, hasSubmit: true });
    const sessionUuid = this.props.session.uuid;
    const courseUuid = this.props.session.courseUuid;

    switch (this.state.sendMode) {
      case SEND_MODE_INTERNET:
        store.dispatch(validateSession(sessionUuid));
        break;
      case SEND_MODE_SMS:
        this.handleRedirectSendSMS(courseUuid, sessionUuid);
        break;
      default:
        break;
    }
  };

  render() {
    const { session, locale } = this.props;

    if (this.state.hasNextSession && !this.props.isFailValidating) {
      return (
        <div className="content-layout">
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
      <div className="content-layout">
        {this.props.isFailValidating &&
          <Error
            message={I18n.t('send.validation.fail', { locale })}
            show={this.props.isFailValidating}
          />}

        <p>Submit your progression with :</p>
        <RadioButtonGroup name="sendMode" onChange={this.handleFormChange}>
          <RadioButton value={SEND_MODE_INTERNET} label="Internet" />
          <RadioButton value={SEND_MODE_SMS} label="SMS" />
        </RadioButtonGroup>
        <RaisedButton
          disabled={!this.state.submitEnabled || this.state.hasSubmit}
          label="Ok"
          className="button-primary"
          onClick={this.handleFormSubmit}
        />
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
    isValidating: state.content.isValidating,
    isFailValidating: state.content.isFailValidating,
    locale: state.settings.locale
  };
}

export default connect(mapStateToProps)(SendScreen);
