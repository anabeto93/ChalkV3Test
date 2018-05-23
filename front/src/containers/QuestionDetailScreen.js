import React, { Component } from 'react';
import { withRouter, Redirect } from 'react-router-dom';
import { connect } from 'react-redux';
import I18n from 'i18n-js';

import { QUESTION_DETAIL, SESSION_LIST, SESSION_SEND } from '../config/routes';
import CourseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';
import { setUserAnswers } from '../actions/actionCreators';
import store from '../store/store';
import { Button } from '@material-ui/core';
import Arrow from '@material-ui/icons/KeyboardArrowRight';
import QuestionAnswers from './../components/Quiz/QuestionAnswers';

class QuestionDetailScreen extends Component {
  handleAnswerChange = answerIndex => {
    const { sessionUuid, questionIndex } = this.props;
    store.dispatch(
      setUserAnswers({
        sessionUuid,
        questionIndex,
        answerIndex: answerIndex
      })
    );
  };

  handleNext = () => {
    const { session, questionIndex } = this.props;
    const nextQuestion = CourseManager.getNextQuestion(session, questionIndex);

    if (nextQuestion !== null) {
      return this.props.history.push(
        generateUrl(QUESTION_DETAIL, {
          ':courseUuid': session.courseUuid,
          ':sessionUuid': session.uuid,
          ':questionIndex': parseInt(questionIndex, 10) + 1
        })
      );
    }

    return this.props.history.push(
      generateUrl(SESSION_SEND, {
        ':courseUuid': session.courseUuid,
        ':sessionUuid': session.uuid
      })
    );
  };

  render() {
    const { session, question, locale } = this.props;

    if (session !== undefined && session.validated) {
      return (
        <Redirect
          to={generateUrl(SESSION_LIST, {
            ':courseUuid': session.courseUuid,
            ':folderUuid': session.folderUuid
          })}
        />
      );
    } else if (question !== undefined) {
      return (
        <div>
          <div className="content">
            <h1>
              {question.title}
            </h1>

            <QuestionAnswers
              question={question}
              handleAnswerChange={this.handleAnswerChange}
            />
          </div>

          <footer className="next-session-footer background-grey">
            <Button variant="raised" color="primary" onClick={this.handleNext}>
              {I18n.t('question.nextButton', { locale })}
              <Arrow />
            </Button>
          </footer>
        </div>
      );
    }

    return <div />;
  }
}

function mapStateToProps(state, props) {
  const sessionUuid = props.match.params.sessionUuid;
  const questionIndex = parseInt(props.match.params.questionIndex, 10);

  if (sessionUuid === undefined) {
    return {};
  }

  if (questionIndex === undefined) {
    return {};
  }

  const session = CourseManager.getSession(state.content.sessions, sessionUuid);

  if (session === undefined) {
    return {};
  }

  if (session.questions === undefined) {
    return {};
  }

  const question = CourseManager.getQuestion(session, questionIndex);

  return {
    sessionUuid,
    questionIndex,
    session,
    question,
    locale: state.settings.locale
  };
}

export default withRouter(connect(mapStateToProps)(QuestionDetailScreen));
