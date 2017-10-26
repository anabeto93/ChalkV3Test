import React, { Component } from 'react';
import { connect } from 'react-redux';
import SessionFooter from '../components/SessionFooter';
import CourseManager from '../services/CourseManager';

class SessionDetailScreen extends Component {
  renderContent() {
    return { __html: this.props.session.content };
  }

  render() {
    const { session, courseUuid } = this.props;

    if (session !== undefined) {
      return (
        <div>
          <div className="content">
            <h1>
              {session.title}
            </h1>
            <div
              className="session-content"
              dangerouslySetInnerHTML={this.renderContent()}
            />
          </div>
          <SessionFooter courseUuid={courseUuid} session={session} />
        </div>
      );
    }

    return <div />;
  }
}

function mapStateToProps(state, props) {
  const session = CourseManager.getSession(
    state.content.sessions,
    props.match.params.sessionUuid
  );

  return {
    session,
    courseUuid: props.match.params.courseUuid
  };
}

export default connect(mapStateToProps)(SessionDetailScreen);
