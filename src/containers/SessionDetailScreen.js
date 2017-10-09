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
    const { session, courseUuid, isValidated, locale } = this.props;

    if (session !== undefined) {
      return (
        <div className="content-layout">
          <h1>
            {session.title}
          </h1>
          <div dangerouslySetInnerHTML={this.renderContent()} />
          <SessionFooter courseUuid={courseUuid} session={session} />
          {isValidated &&
            <Success
              message={I18n.t('send.sms.validation.done', { locale })}
              show={true}
              dispatchOnDismiss={doneValidateSession}
            />}
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

  const { content: { isValidated } } = state;
  const { settings: { locale } } = state;

  return {
    session,
    courseUuid: props.match.params.courseUuid,
    isValidated,
    locale
  };
}

export default connect(mapStateToProps)(SessionDetailScreen);
