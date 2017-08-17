import React, { Component } from 'react';
import { connect } from 'react-redux';
import CourseManager from '../services/CourseManager';

class SessionDetailScreen extends Component {
  renderContent() {
    return { __html: this.props.session.content };
  }

  render() {
    const { session } = this.props;

    if (session !== undefined) {
      return (
        <div>
          <h1>
            {session.title}
          </h1>
          <div dangerouslySetInnerHTML={this.renderContent()} />
        </div>
      );
    }

    return <div />; // apollo persist/REHYDRATE trigger after render
  }
}

function mapStateToProps(state, props) {
  let session = CourseManager.getSessionFromCourseAndSession(
    state.courses.items,
    props.match.params.courseUuid,
    props.match.params.sessionUuid
  );

  return { session };
}

export default connect(mapStateToProps)(SessionDetailScreen);
