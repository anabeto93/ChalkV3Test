import I18n from 'i18n-js';
import { RaisedButton } from 'material-ui';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import React from 'react';
import { connect } from 'react-redux';
import { withRouter } from 'react-router-dom';
import { QUESTION_DETAIL, SESSION_LIST } from '../config/routes';
import CourseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';

const footer = props => {
  const { session, questionUuid, history, locale } = props;

  const handleNext = () => {
    const nextQuestion = CourseManager.getNextQuestion(session, questionUuid);

    if (nextQuestion !== null) {
      return history.push(
        generateUrl(QUESTION_DETAIL, {
          ':courseUuid': session.courseUuid,
          ':sessionUuid': session.uuid,
          ':questionUuid': questionUuid
        })
      );
    } else {
      return history.push(
        generateUrl(SESSION_LIST, {
          ':courseUuid': session.courseUuid,
          ':folderUuid': session.folderUuid
        })
      );
    }
  };

  return (
    <footer className="next-session-footer background-grey">
      <RaisedButton
        label={I18n.t('question.nextButton', { locale })}
        labelPosition="before"
        primary={true}
        onClick={handleNext}
        icon={<Arrow />}
      />
    </footer>
  );
};

function mapStateToProps(state, props) {
  return {
    sessions: state.content.sessions,
    locale: state.settings.locale
  };
}

export default withRouter(connect(mapStateToProps)(footer));
