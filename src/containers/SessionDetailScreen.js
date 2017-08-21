import React, { Component } from 'react';
import { connect } from 'react-redux';
import CourseManager from '../services/CourseManager';
import SessionFooter from '../components/SessionFooter';

class SessionDetailScreen extends Component {
  renderContent() {
    return { __html: this.props.session.content };
  }

  render() {
    const { session, courseUuid } = this.props;

    if (session !== undefined) {
      return (
        <div>
          <h1>
            {session.title}
          </h1>
          <div dangerouslySetInnerHTML={this.renderContent()} />
          <SessionFooter courseUuid={courseUuid} sessionUuid={session.uuid} />
        </div>
      );
    }

    return <div />; // apollo persist/REHYDRATE trigger after render
  }
}

function mapStateToProps(state, props) {
  let session = CourseManager.getSessionFromCourseIdAndSessionId(
    props.match.params.courseId,
    props.match.params.sessionId
  );

  return { session, courseUuid: props.match.params.courseId };
}

export default connect(mapStateToProps)(SessionDetailScreen);
