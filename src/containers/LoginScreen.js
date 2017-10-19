import I18n from 'i18n-js';
import { RaisedButton } from 'material-ui';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import {
  getUserInformations,
  purgeUserInformations
} from '../actions/actionCreators';
import UserPanel from '../components/Course/UserPanel';

import { COURSES, HOME } from '../config/routes';
import { LOGIN_STATE_LOGGED_IN } from '../store/defaultState';
import { Redirect } from 'react-router-dom';

class LoginScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = { isFetching: false };
  }

  componentDidMount() {
    const { currentUser } = this.props;

    if (currentUser.loginState !== LOGIN_STATE_LOGGED_IN) {
      this.setState({ isFetching: true });
      this.props.dispatch(getUserInformations(this.props.match.params.token));
    }
  }

  componentWillReceiveProps(nextProps) {
    const { token: previousToken } = this.props.currentUser;
    const { token: nextToken, loginState } = nextProps.currentUser;

    if (null !== previousToken && nextToken === null) {
      this.props.history.push(HOME);
    }

    if (loginState === LOGIN_STATE_LOGGED_IN) {
      this.setState({ isFetching: false });
    }
  }

  handleRedirectCourses = () => {
    this.props.history.push(COURSES);
  };

  render() {
    const { locale, currentUser } = this.props;
    const { token } = this.props.match.params;

    if (currentUser.token === token) {
      return <Redirect to={HOME} />;
    } else if (currentUser.token !== null && currentUser.token !== token) {
      this.props.dispatch(purgeUserInformations());
      this.props.dispatch(getUserInformations(token));
    }

    if (this.state.isFetching) {
      return (
        <div className="flash-container">
          {I18n.t('login.checking', { locale })}
        </div>
      );
    }

    if (currentUser.loginState === LOGIN_STATE_LOGGED_IN) {
      return (
        <div>
          <UserPanel />
          <RaisedButton
            label={I18n.t('login.start', { locale })}
            style={{ margin: '10px' }}
            onClick={this.handleRedirectCourses}
          />
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
