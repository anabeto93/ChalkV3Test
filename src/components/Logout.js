import I18n from 'i18n-js';
import React, { Component } from 'react';
import {
  Button,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogContentText,
  DialogActions
} from '@material-ui/core';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import ReactGA from 'react-ga';

import { userLogout, cancelUserLogout } from '../actions/actionCreators';
import { HOME } from '../config/routes';

class Logout extends Component {
  constructor(props) {
    super(props);
    this.state = { open: props.logout.loggingOut };
  }

  componentWillReceiveProps(nextProps) {
    if (nextProps.logout.loggingOut) {
      this.setState({ open: true });
      return;
    }

    this.setState({
      open: false
    });
  }

  handleLogout = () => {
    this.props.dispatch(userLogout());
    this.handleClose();

    ReactGA.event({
      category: 'Logout',
      action: 'Logged out',
      label: this.props.logout.isForced ? 'Forced' : 'Voluntary'
    });

    return this.props.history.push(HOME);
  };

  handleClose = () => {
    this.props.dispatch(cancelUserLogout());
  };

  render() {
    const { logout, locale } = this.props;
    const forcedLogout = logout.isForced;

    return (
      <Dialog
        disableBackdropClick={forcedLogout}
        open={this.state.open}
        onClose={this.handleClose}
      >
        <DialogTitle>
          {I18n.t(forcedLogout ? 'error.authenticationError' : 'logout.title', {
            locale
          })}
        </DialogTitle>

        <DialogContent>
          <DialogContentText>
            {I18n.t(forcedLogout ? 'tokenError.402' : 'logout.message', {
              locale
            })}
          </DialogContentText>
        </DialogContent>

        <DialogActions>
          {!forcedLogout &&
            <Button color="secondary" onClick={this.handleClose}>
              {I18n.t('logout.cancelButton', { locale })}
            </Button>}

          <Button variant="raised" color="primary" onClick={this.handleLogout}>
            {I18n.t('logout.button', { locale })}
          </Button>
        </DialogActions>
      </Dialog>
    );
  }
}

function mapStateToProps({ logout, settings: { locale } }, props) {
  return { logout, locale };
}

export default withRouter(connect(mapStateToProps)(Logout));
