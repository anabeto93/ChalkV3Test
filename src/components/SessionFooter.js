import I18n from 'i18n-js';
import { Button } from '@material-ui/core';
import Arrow from '@material-ui/icons/KeyboardArrowRight';
import React from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import {
  SESSION_DETAIL,
  SESSION_LIST,
  SESSION_SEND,
  QUESTION_DETAIL
} from '../config/routes';
import CourseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';

const footer = props => {
  const { courseUuid, session, sessions, history, locale } = props;

  const handleNext = () => {
    if (session.questions && !session.validated) {
      return history.push(
        generateUrl(QUESTION_DETAIL, {
          ':courseUuid': courseUuid,
          ':sessionUuid': session.uuid,
          ':questionIndex': 0
        })
      );
    }

    if (session.needValidation && !session.validated) {
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
      <Button variant="raised" color="primary" onClick={handleNext}>
        {I18n.t(
          session.questions && !session.validated
            ? 'session.quizButton'
            : 'session.nextButton',
          { locale }
        )}
        <Arrow />
      </Button>
    </footer>
  );
};

function mapStateToProps(state) {
  return { sessions: state.content.sessions, locale: state.settings.locale };
}

export default withRouter(connect(mapStateToProps)(footer));
