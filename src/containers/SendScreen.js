import { RaisedButton } from 'material-ui';
import { RadioButton, RadioButtonGroup } from 'material-ui/RadioButton';
import React, { Component } from 'react';
import {
  requestValidateSessionInternet,
  validateSession
} from '../actions/actionCreators';
import store from '../store/store';

const DEFAULT_STATE = { sendMode: null, submitEnabled: false };
const SEND_MODE_INTERNET = 'internet';

class SendScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  handleFormChange = (event, value) => {
    this.setState({ sendMode: value, submitEnabled: true });
  };

  handleFormSubmit = () => {
    const sessionUuid = this.props.match.params.sessionId;

    switch (this.state.sendMode) {
      case SEND_MODE_INTERNET:
        store.dispatch(validateSession(sessionUuid));
    }
  };

  render() {
    return (
      <div>
        <h1>Send</h1>
        <RadioButtonGroup name="sendMode" onChange={this.handleFormChange}>
          <RadioButton value={SEND_MODE_INTERNET} label="Internet" />
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

export default SendScreen;
