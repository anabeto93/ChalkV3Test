import I18n from 'i18n-js';
import { Button, Typography } from '@material-ui/core';
import InternetIcon from '@material-ui/icons/Language';
import SMSIcon from '@material-ui/icons/Sms';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { validateSession, answerSessionQuiz } from '../actions/actionCreators';
import Error from '../components/Error';
import ValidatedSession from '../components/ValidatedSession';
import CourseManager from '../services/CourseManager';
import generateUrl from '../services/generateUrl';
import stringifyUserAnswers from '../services/quiz/stringifyUserAnswers';
import { SESSION_SEND_SMS, QUESTION_DETAIL } from '../config/routes';
import store from '../store/store';
import ReactGA from 'react-ga';

const DEFAULT_STATE = {
  nextSession: null,
  isSessionValidated: false
};

class SendScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentWillReceiveProps(nextProps) {
    if (!this.props.session.validated && nextProps.session.validated) {
      const nextSession = CourseManager.getNextSession(
        nextProps.sessions,
        nextProps.session
      );

      this.setState({ ...this.state, nextSession, isSessionValidated: true });
    }
  }

  handleSendByInternet = () => {
    this.setState({ ...this.state });

    const { session } = this.props;

    ReactGA.event({
      category: 'Session',
      action: 'Validated via internet',
      label: session.uuid
    });

    if (session && session.questions) {
      store.dispatch(
        answerSessionQuiz({
          sessionUuid: session.uuid,
          answers: stringifyUserAnswers(session.questions)
        })
      );

      ReactGA.event({
        category: 'Quiz',
        action: 'Submitted via Internet',
        label: 'Session: ' + session.uuid
      });
    } else {
      store.dispatch(validateSession(session.uuid));
    }
  };

  handleSendBySms = () => {
    return this.props.history.push(
      generateUrl(SESSION_SEND_SMS, {
        ':courseUuid': this.props.session.courseUuid,
        ':sessionUuid': this.props.session.uuid
      })
    );
  };

  handleBackToQuizStart = () => {
    return this.props.history.push(
      generateUrl(QUESTION_DETAIL, {
        ':courseUuid': this.props.session.courseUuid,
        ':sessionUuid': this.props.session.uuid,
        ':questionIndex': 0
      })
    );
  };

  checkQuestionAnswers = () => {
    const { session: { questions } } = this.props;

    for (let i = 0; i < questions.length; i++) {
      if (!questions[i].userAnswers || questions[i].userAnswers.length === 0) {
        return false;
      }
    }

    return true;
  };

  render() {
    const { session, locale, isValidating, isFailValidating } = this.props;
    const iconStyle = { marginRight: '0.3em' };

    if (this.state.isSessionValidated) {
      return (
        <ValidatedSession
          session={session}
          nextSession={this.state.nextSession}
          locale={locale}
        />
      );
    }

    if (session.questions && !this.checkQuestionAnswers()) {
      return (
        <div className="screen-centered">
          <Typography variant="headline" component="h1">
            {I18n.t('question.answerAll', { locale })}
          </Typography>
          <Button
            variant="raised"
            color="primary"
            onClick={this.handleBackToQuizStart}
            style={{ margin: '2em' }}
          >
            {I18n.t('question.backToStart', { locale })}
          </Button>
        </div>
      );
    }

    return (
      <div>
        <div className="screen-centered">
          <div className="content">
            <p>
              {I18n.t('send.label', { locale })}
            </p>

            <Button
              variant="raised"
              color="primary"
              disabled={isValidating}
              onClick={this.handleSendByInternet}
              style={{ marginRight: '10px', width: '40%' }}
            >
              <InternetIcon style={iconStyle} />
              {I18n.t('send.medium.internet', { locale })}
            </Button>

            {!session.questions &&
              <Button
                variant="raised"
                color="primary"
                onClick={this.handleSendBySms}
                style={{ width: '40%' }}
              >
                <SMSIcon style={iconStyle} />
                {I18n.t('send.medium.sms', { locale })}
              </Button>}
          </div>
        </div>

        {isFailValidating &&
          <Error
            message={I18n.t('send.validation.fail', { locale })}
            show={true}
          />}
      </div>
    );
  }
}

function mapStateToProps(state, props) {
  const session = CourseManager.getSession(
    state.content.sessions,
    props.match.params.sessionUuid
  );

  return {
    sessions: state.content.sessions,
    session,
    isValidating: state.content.isSessionValidating,
    isFailValidating: state.content.isSessionFailValidating,
    locale: state.settings.locale
  };
}

export default connect(mapStateToProps)(SendScreen);
