import React, { Component } from 'react';
import { connect } from 'react-redux';
import CourseManager from '../services/CourseManager';

class SessionDetailScreen extends Component {
  render() {
    const { session } = this.props;

    return (
      <div>
        { session !== undefined &&
          <h1>{session.title}</h1>
        }
      </div>
    )
  }
}

function mapStateToProps(state, props) {
  let session = CourseManager.getSessionFromCourseIdAndSessionId(
    props.match.params.courseId,
    props.match.params.sessionId
  );

  return { session };
}

export default connect(mapStateToProps)(SessionDetailScreen);

