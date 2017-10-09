import I18n from 'i18n-js';
import { Snackbar } from 'material-ui';
import React, { Component } from 'react';
import { connect } from 'react-redux';
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
    const { locale } = this.props;

    const style = {
      backgroundColor: '#d8497d',
      color: '#ffffff'
    };

    return (
      <Snackbar
        open={this.state.show}
        message={this.props.message}
        // action={I18n.t('error.dismiss', { locale })}
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

const mapStateToProps = ({ settings: { locale } }) => ({ locale });

export default connect(mapStateToProps)(Success);
