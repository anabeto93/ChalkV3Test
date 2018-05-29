import I18n from 'i18n-js';
import { Snackbar, Button } from '@material-ui/core';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import store from '../store/store';

class Error extends Component {
  constructor(...args) {
    super(...args);
    this.state = {
      show: this.props.show
    };
  }

  handleDismiss = () => {
    const { dispatchOnDismiss } = this.props;
    this.setState({ ...this.state, show: !this.state.show });

    if (dispatchOnDismiss !== undefined) {
      store.dispatch(dispatchOnDismiss());
    }
  };

  render() {
    const { locale } = this.props;
    const snackbarStyle = { bottom: '56px' };

    return (
      <Snackbar
        open={this.state.show}
        message={this.props.message}
        action={
          <Button color="secondary">
            {I18n.t('error.dismiss', { locale })}
          </Button>
        }
        autoHideDuration={3000}
        onClose={this.handleDismiss}
        onClick={this.handleDismiss}
        style={snackbarStyle}
      />
    );
  }
}

Error.defaultProps = {
  show: false
};

const mapStateToProps = ({ settings: { locale } }) => ({ locale });

export default connect(mapStateToProps)(Error);
