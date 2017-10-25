import { RaisedButton } from 'material-ui';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import { SESSION_DETAIL, SESSION_LIST, SESSION_SEND } from '../config/routes';
import CourseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';

const footer = props => {
  const { courseUuid, session, sessions, history } = props;

  const handleNext = () => {
    if (session.needValidation) {
      return history.push(
        generateUrl(SESSION_SEND, {
          ':courseUuid': courseUuid,
          ':sessionUuid': session.uuid
        })
      );
    }

    const nextSession = CourseManager.getNextSession(sessions, session);

    if (nextSession !== null) {
      return history.push(
        generateUrl(SESSION_DETAIL, {
          ':courseUuid': nextSession.courseUuid,
          ':sessionUuid': nextSession.uuid
        })
      );
    }

    return history.push(
      generateUrl(SESSION_LIST, {
        ':courseUuid': session.courseUuid,
        ':folderUuid': session.folderUuid
      })
    );
  };

  return (
    <footer className="next-session-footer background-grey">
      <RaisedButton
        label="Next"
        labelPosition="before"
        primary={true}
        onClick={handleNext}
        icon={<Arrow />}
      />
    </footer>
  );
};

function mapStateToProps(state) {
  return { sessions: state.content.sessions };
}

export default connect(mapStateToProps)(withRouter(footer));
