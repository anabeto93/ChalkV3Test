import { RaisedButton } from 'material-ui';
import { RadioButton, RadioButtonGroup } from 'material-ui/RadioButton';
import React, { Component } from 'react';

const DEFAULT_STATE = { sendMode: null, submitEnabled: false };

class SendScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  handleFormChange = (event, value) => {
    this.setState({ sendMode: value, submitEnabled: true });
  };

  handleFormSubmit = () => {
    console.log(this.state.sendMode);
  };

  render() {
    const SEND_MODE_INTERNET = 'internet';

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
