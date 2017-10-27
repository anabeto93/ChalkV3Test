import I18n from 'i18n-js';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { doneValidateSession } from '../actions/actionCreators';
import SessionFooter from '../components/SessionFooter';
import Success from '../components/Success';
import CourseManager from '../services/CourseManager';

class SessionDetailScreen extends Component {
  renderContent() {
    return { __html: this.props.session.content };
  }

  render() {
    const { session, courseUuid, isSessionValidated, locale } = this.props;

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
            {isSessionValidated &&
              <Success
                message={I18n.t('send.sms.validation.done', { locale })}
                show={true}
                dispatchOnDismiss={doneValidateSession}
              />}
          </div>
          <SessionFooter courseUuid={courseUuid} session={session} />
        </div>
      );
    }

    return <div />; // apollo persist/REHYDRATE trigger after render
  }
}

function mapStateToProps(state, props) {
  const session = CourseManager.getSession(
    state.content.sessions,
    props.match.params.sessionUuid
  );

  const { content: { isSessionValidated } } = state;
  const { settings: { locale } } = state;

  return {
    session,
    courseUuid: props.match.params.courseUuid,
    isSessionValidated,
    locale
  };
}

export default connect(mapStateToProps)(SessionDetailScreen);
