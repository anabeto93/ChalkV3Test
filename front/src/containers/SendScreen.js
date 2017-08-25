import { RadioButton, RadioButtonGroup } from 'material-ui/RadioButton';
import React, { Component } from 'react';
import { validateSession } from '../actions/actionCreators';
import store from '../store/store';
import CourseManager from "../services/CourseManager";
import { connect } from 'react-redux';
import { RaisedButton } from "material-ui";
import { Redirect } from "react-router-dom";
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';

const DEFAULT_STATE = { sendMode: null, submitEnabled: false, redirectToSessionList: false, hasNextSession: false };
const SEND_MODE_INTERNET = 'internet';

class SendScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentWillReceiveProps(nextProps) {
    const nextSession = CourseManager.getNextSession(nextProps.sessions, nextProps.session);

    if (nextSession !== null) {
      this.setState({ ...this.state, nextSession, hasNextSession: true });
    } else {
      this.setState({ ...this.state, redirectToSessionList: true });
    }
  }

  handleFormChange = (event, value) => {
    this.setState({ ...this.state, sendMode: value, submitEnabled: true });
  };

  handleRedirectNextSession = () => {
    return this.props.history.push(`/courses/${this.state.nextSession.courseUuid}/session/${this.state.nextSession.uuid}`);
  };

  handleFormSubmit = () => {
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
    const { session } = this.props;

    if (this.state.hasNextSession) {
      return (
        <div className="content-layout">
          <h4>Thank you for submitted your answers.</h4>
          <p>Your session was successfully validated and you can go to the next session.</p>
          <RaisedButton style={{ float: 'left' }} label="Back to the list"/>
          <RaisedButton
            className="button-primary"
            primary={true}
            onClick={this.handleRedirectNextSession}
            label="Next"
            labelPosition="before"
            icon={<Arrow/>}
          />
        </div>
      )
    }

    if (this.state.redirectToSessionList) {
      return <Redirect to={`/courses/${session.courseUuid}/folders/${session.folderUuid}/sessions/list`}/>
    }

    return (
      <div className="content-layout">
        <p>Submit your progression with :</p>
        <RadioButtonGroup name="sendMode" onChange={this.handleFormChange}>
          <RadioButton value={SEND_MODE_INTERNET} label="Internet"/>
        </RadioButtonGroup>
        <RaisedButton
          disabled={!this.state.submitEnabled}
          label="Ok"
          className="button-primary"
          onClick={this.handleFormSubmit}
        />
      </div>
    );
  }
}

function mapStateToProps(state, props) {
  const session = CourseManager.getSession(state.content.sessions, props.match.params.sessionUuid);

  return { sessions: state.content.sessions, session };
}

export default connect(mapStateToProps)(SendScreen);
