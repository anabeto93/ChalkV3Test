import I18n from 'i18n-js';
import { Snackbar } from 'material-ui';
import React, { Component } from 'react';
import { connect } from 'react-redux';

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
    const { locale } = this.props;

    return (
      <Snackbar
        open={this.state.show}
        message={this.props.message}
        action={I18n.t('error.dismiss', { locale })}
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

const mapStateToProps = ({ settings: { locale } }) => ({ locale });

export default connect(mapStateToProps)(Error);
