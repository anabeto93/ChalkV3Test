import { Snackbar } from 'material-ui';
import React, { Component } from 'react';
import store from '../store/store';

class Success extends Component {
  constructor(...args) {
    super(...args);
    this.state = {
      show: this.props.show
    };
  }

  handleDismiss = () => {
    const { dispatchOnDismiss } = this.props;
    this.setState({ ...this.state, show: !this.state.show });
    store.dispatch(dispatchOnDismiss());
  };

  render() {
    const style = {
      backgroundColor: '#d8497d',
      color: '#ffffff'
    };

    return (
      <Snackbar
        open={this.state.show}
        message={this.props.message}
        autoHideDuration={3000}
        onRequestClose={this.handleDismiss}
        onActionTouchTap={this.handleDismiss}
        bodyStyle={style}
      />
    );
  }
}

Success.defaultProps = {
  show: false
};

export default Success;
