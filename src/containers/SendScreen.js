import { RadioButton, RadioButtonGroup } from 'material-ui/RadioButton';
import React, { Component } from 'react';
import { validateSession } from '../actions/actionCreators';
import store from '../store/store';
import CourseManager from "../services/CourseManager";
import { connect } from 'react-redux';
import { RaisedButton } from "material-ui";

const DEFAULT_STATE = { sendMode: null, submitEnabled: false };
const SEND_MODE_INTERNET = 'internet';

class SendScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentWillReceiveProps(nextProps) {
    const nextSession = CourseManager.getNextSession(nextProps.sessions, nextProps.session);

    if (nextSession !== null) {
      nextProps.history.push(`/courses/${nextSession.courseUuid}/session/${nextSession.uuid}`);
    }
  }

  handleFormChange = (event, value) => {
    this.setState({ sendMode: value, submitEnabled: true });
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
    return (
      <div>
        <h1>Send</h1>
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
