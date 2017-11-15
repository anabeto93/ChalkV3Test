import React, { Component } from 'react';
import { withRouter, Redirect } from 'react-router-dom';
import { connect } from 'react-redux';
import I18n from 'i18n-js';
import { QUESTION_DETAIL, SESSION_LIST, SESSION_SEND } from '../config/routes';
import CourseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';
import { RaisedButton } from 'material-ui';
import Arrow from 'material-ui/svg-icons/hardware/keyboard-arrow-right';
import QuestionAnswers from './../components/Quiz/QuestionAnswers';

class QuestionDetailScreen extends Component {
  handleRadioChange = answer => {
    //Do something with the answer
    console.log(answer);
  };

  handleCheckChange = answer => {
    //Do something with multiple answers
    console.log(answer);
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

    if (question !== undefined) {
      return (
        <div>
          <div className="content">
            <h1>
              {question.title}
            </h1>

            <QuestionAnswers
              question={question}
              handleRadioChange={this.handleRadioChange}
              handleCheckChange={this.handleCheckChange}
            />
          </div>

          <footer className="next-session-footer background-grey">
            <RaisedButton
              label={I18n.t('question.nextButton', { locale })}
              labelPosition="before"
              primary={true}
              onClick={this.handleNext}
              icon={<Arrow />}
            />
          </footer>
        </div>
      );
    }

    return (
      <Redirect
        to={generateUrl(SESSION_LIST, {
          ':courseUuid': session.courseUuid,
          ':folderUuid': session.folderUuid
        })}
      />
    );
  }
}

function mapStateToProps(state, props) {
  const sessionUuid = props.match.params.sessionUuid;
  const questionIndex = props.match.params.questionIndex;

  if (sessionUuid === undefined) {
    return {};
  }

  if (questionIndex === undefined) {
    return {};
  }

  const session = CourseManager.getSession(state.content.sessions, sessionUuid);

  if (session === null) {
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
