import { RaisedButton } from 'material-ui';
import { RadioButton, RadioButtonGroup } from 'material-ui/RadioButton';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { Redirect } from 'react-router-dom';
import { validateSession } from '../actions/actionCreators';
import Error from '../components/Error';
import CourseManager from '../services/CourseManager';
import store from '../store/store';
import I18n from 'i18n-js';

const DEFAULT_STATE = {
  sendMode: null,
  submitEnabled: false,
  redirectToSessionList: false,
  hasNextSession: false,
  hasSubmit: false
};
const SEND_MODE_INTERNET = 'internet';

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

    if (nextSession !== null && !nextProps.isFailValidating) {
      this.setState({ ...this.state, nextSession, hasNextSession: true });
    } else if (!nextProps.isFailValidating) {
      this.setState({ ...this.state, redirectToSessionList: true });
    } else {
      this.setState({ ...this.state, hasSubmit: false });
    }
  }

  handleFormChange = (event, value) => {
    this.setState({ ...this.state, sendMode: value, submitEnabled: true });
  };

  handleRedirectNextSession = () => {
    return this.props.history.push(
      `/courses/${this.state.nextSession.courseUuid}/session/${this.state
        .nextSession.uuid}`
    );
  };

  handleRedirectSessionList = () => {
    this.props.history.push(
      `/courses/${this.state.nextSession.courseUuid}/folders/${this.state
        .nextSession.folderUuid}/sessions/list`
    );
  };

  handleFormSubmit = () => {
    this.setState({ ...this.state, hasSubmit: true });
    const sessionUuid = this.props.match.params.sessionUuid;

    switch (this.state.sendMode) {
      case SEND_MODE_INTERNET:
        store.dispatch(validateSession(sessionUuid));
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
          to={`/courses/${session.courseUuid}/folders/${session.folderUuid}/sessions/list`}
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
    isFailValidating: state.content.isFailValidating,
    locale: state.settings.locale
  };
}

export default connect(mapStateToProps)(SendScreen);
