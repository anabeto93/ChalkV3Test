import { Snackbar } from 'material-ui';
import React, { Component } from 'react';

class Error extends Component {
  constructor(...args) {
    super(...args);
    this.state = {
      show: this.props.show
    };
  }

  handleDismiss = () => {
    this.setState({ ...this.state, show: !this.state.show });
  };

  render() {
    return (
      <Snackbar
        open={this.state.show}
        message={this.props.message}
        action="Dismiss"
        autoHideDuration={3000}
        onRequestClose={this.handleDismiss}
        onActionTouchTap={this.handleDismiss}
      />
    );
  }
}

Error.defaultProps = {
  show: false
};

export default Error;
