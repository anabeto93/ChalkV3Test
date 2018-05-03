import I18n from 'i18n-js';
import React, { Component } from 'react';
import Dialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';

import { userLogout } from '../actions/actionCreators';
import { HOME } from '../config/routes';

class Logout extends Component {
  constructor(...args) {
    super(...args);
    this.state = { open: false };
  }

  componentWillReceiveProps(nextProps) {
    if (nextProps.logout.loggingOut) {
      this.setState({ open: true });
    }
  }

  handleLogout = () => {
    if (!this.props.logout.forcedLogout) {
      this.props.dispatch(userLogout());
    }

    this.handleClose();
    return this.props.history.push(HOME);
  };

  handleClose = () => {
    this.setState({ open: false });
  };

  render() {
    const { logout, locale } = this.props;
    const forcedLogout = logout.isForced;

    const actions = [
      !forcedLogout
        ? [
            <FlatButton
              label={I18n.t('logout.cancelButton', { locale })}
              secondary={true}
              onClick={this.handleClose}
            />
          ]
        : [],
      <RaisedButton
        label={I18n.t('logout.button', { locale })}
        primary={true}
        onClick={this.handleLogout}
      />
    ];

    return (
      <Dialog
        title={I18n.t(
          forcedLogout ? 'error.authenticationError' : 'logout.title',
          { locale }
        )}
        actions={actions}
        modal={true}
        open={this.state.open}
      >
        {I18n.t(forcedLogout ? 'tokenError.402' : 'logout.message', { locale })}
      </Dialog>
    );
  }
}

function mapStateToProps({ logout, settings: { locale } }, props) {
  return { logout, locale };
}

export default withRouter(connect(mapStateToProps)(Logout));
