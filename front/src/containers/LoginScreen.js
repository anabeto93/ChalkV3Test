import { RaisedButton } from 'material-ui';
import React, { Component } from 'react';
import { connect } from 'react-redux';

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
    if (this.props.content.isFetching) {
      return <div className="flash-container">Checking ...</div>;
    }

    if (this.props.user.uuid !== undefined) {
      return (
        <div>
          <UserPanel />
          <RaisedButton
            style={{ margin: '10px' }}
            onClick={this.handleRedirectCourses}
          >
            Start
          </RaisedButton>
        </div>
      );
    }

    return <div />;
  }
}

const mapStateToProps = ({ content, currentUser }) => ({
  content,
  user: currentUser
});

export default connect(mapStateToProps)(LoginScreen);
