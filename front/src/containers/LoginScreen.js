import { RaisedButton } from 'material-ui';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { getCoursesInformations } from '../actions/actionCreators';
import UserPanel from '../components/Course/UserPanel';
import { COURSES } from '../config/routes';
import { LOGIN_STATE_LOGOUT } from '../store/defaultState';
import store from '../store/store';

class LoginScreen extends Component {
  componentDidMount() {
    if (store.getState().currentUser.loginState === LOGIN_STATE_LOGOUT) {
      this.props.dispatch(getCoursesInformations());
    } else {
      this.redirectCourses();
    }
  }

  redirectCourses() {
    this.props.history.push(COURSES);
  }

  render() {
    console.log('rendering LoginScreen');
    console.log(this.props);

    if (this.props.courses.isFetching) {
      return <div className="flash-container">Checking ...</div>;
    }

    if (this.props.user.uuid !== undefined) {
      return (
        <div>
          <h1>Welcome</h1>
          <UserPanel />
          <RaisedButton
            style={{ margin: '10px' }}
            onClick={this.redirectCourses.bind(this)}
          >
            Start
          </RaisedButton>
        </div>
      );
    }

    return <div />;
  }
}

const mapStateToProps = ({ courses, currentUser }) => ({
  courses,
  user: currentUser
});

export default connect(mapStateToProps)(LoginScreen);
