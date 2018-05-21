import I18n from 'i18n-js';
import React, { Component } from 'react';
import Dialog from 'material-ui/Dialog';
import RaisedButton from 'material-ui/RaisedButton';
import FlatButton from 'material-ui/FlatButton';
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
