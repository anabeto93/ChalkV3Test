import React, { Component } from 'react';
import { withRouter } from 'react-router-dom';
import { connect } from 'react-redux';
import I18n from 'i18n-js';
import { QUESTION_DETAIL, SESSION_LIST } from '../config/routes';
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
    const { session, questionUuid } = this.props;
    const nextQuestion = CourseManager.getNextQuestion(session, questionUuid);

    if (nextQuestion !== null) {
      return this.props.history.push(
        generateUrl(QUESTION_DETAIL, {
          ':courseUuid': session.courseUuid,
          ':sessionUuid': session.uuid,
          ':questionUuid': nextQuestion.uuid
        })
      );
    }

    return this.props.history.push(
      generateUrl(SESSION_LIST, {
        ':courseUuid': session.courseUuid,
        ':folderUuid': session.folderUuid
      })
    );
  };

  render() {
    const { question, locale } = this.props;

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

    return <div />;
  }
}

function mapStateToProps(state, props) {
  let sessionUuid = props.match.params.sessionUuid;
  let questionUuid = props.match.params.questionUuid;

  if (sessionUuid === undefined) {
    return {};
  }

  if (questionUuid === undefined) {
    return {};
  }

  const session = CourseManager.getSession(state.content.sessions, sessionUuid);

  if (session === null) {
    return {};
  }

  if (session.questions === undefined) {
    return {};
  }

  const question = CourseManager.getQuestion(session, questionUuid);

  return {
    sessionUuid,
    questionUuid,
    session,
    question,
    locale: state.settings.locale
  };
}

export default withRouter(connect(mapStateToProps)(QuestionDetailScreen));
