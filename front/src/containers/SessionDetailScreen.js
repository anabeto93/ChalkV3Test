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
        <div className="content-layout">
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
  let session = CourseManager.getSession(
    state.content.sessions,
    props.match.params.sessionUuid
  );

  return { session, courseUuid: props.match.params.courseUuid };
}

export default connect(mapStateToProps)(SessionDetailScreen);
