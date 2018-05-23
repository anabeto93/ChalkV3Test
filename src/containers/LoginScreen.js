import I18n from 'i18n-js';
import { Button } from '@material-ui/core';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { getUserInformations, getUpdates } from '../actions/actionCreators';
import UserPanel from '../components/Course/UserPanel';
import * as moment from 'moment';
import ReactGA from 'react-ga';

import { COURSES, HOME } from '../config/routes';
import { LOGIN_STATE_LOGGED_IN } from '../store/defaultState';

class LoginScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = { isFetching: false };
  }

  componentDidMount() {
    const { currentUser } = this.props;
    const token = this.props.match.params.token;
    const tokenIssuedAt = moment().format('X');

    //Set userID for Google Analytics
    ReactGA.set({ userId: token });

    if (null === token) {
      this.props.history.push(HOME);
    }

    const isTokenChanged =
      currentUser.token !== null && currentUser.token !== token;

    if (isTokenChanged || currentUser.loginState !== LOGIN_STATE_LOGGED_IN) {
      this.setState({ isFetching: true });
      this.props.dispatch(getUserInformations({ token, tokenIssuedAt }));

      ReactGA.event({
        category: 'Login',
        action: 'Logged in',
        label: token
      });

      return;
    }

    // user already logged, redirect to courses list
    this.props.history.push(COURSES);
  }

  componentWillReceiveProps(nextProps) {
    const { token: nextToken, loginState } = nextProps.currentUser;

    if (nextToken === null) {
      this.props.history.push(HOME);
    }

    if (loginState === LOGIN_STATE_LOGGED_IN) {
      this.setState({ isFetching: false });
    }
  }

  handleRedirectCourses = () => {
    // Get updates
    this.props.dispatch(getUpdates(null));

    // Go to courses list
    this.props.history.push(COURSES);
  };

  render() {
    const { locale, currentUser } = this.props;

    if (this.state.isFetching) {
      return (
        <div className="flash-container">
          {I18n.t('login.checking', { locale })}
        </div>
      );
    }

    if (currentUser.loginState === LOGIN_STATE_LOGGED_IN) {
      return (
        <div className="screen-centered">
          <UserPanel />
          <Button
            variant="raised"
            color="primary"
            style={{ margin: '10px' }}
            onClick={this.handleRedirectCourses}
          >
            {I18n.t('login.start', { locale })}
          </Button>
        </div>
      );
    }

    return <div />;
  }
}

const mapStateToProps = ({ currentUser, settings: { locale } }) => ({
  currentUser,
  locale
});

export default connect(mapStateToProps)(LoginScreen);
