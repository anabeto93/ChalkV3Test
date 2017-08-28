import { RaisedButton } from 'material-ui';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import I18n from 'i18n-js';

import { COURSES } from '../config/routes';
import { getCoursesInformations } from '../actions/actionCreators';
import { LOGIN_STATE_LOGGED_OUT } from '../store/defaultState';
import store from '../store/store';
import UserPanel from '../components/Course/UserPanel';

class LoginScreen extends Component {
  componentDidMount() {
    if (store.getState().currentUser.loginState === LOGIN_STATE_LOGGED_OUT) {
      this.props.dispatch(getCoursesInformations());
    } else {
      this.handleRedirectCourses();
    }
  }

  handleRedirectCourses = () => {
    this.props.history.push(COURSES);
  };

  render() {
    const { locale } = this.props;

    if (this.props.content.isFetching) {
      return (
        <div className="flash-container">
          {I18n.t('login.checking', { locale })}
        </div>
      );
    }

    if (this.props.user.uuid !== undefined) {
      return (
        <div>
          <UserPanel />
          <RaisedButton
            style={{ margin: '10px' }}
            onClick={this.handleRedirectCourses}
          >
            {I18n.t('login.start', { locale })}
          </RaisedButton>
        </div>
      );
    }

    return <div />;
  }
}

const mapStateToProps = ({ content, currentUser, settings: { locale } }) => ({
  content,
  user: currentUser,
  locale
});

export default connect(mapStateToProps)(LoginScreen);
