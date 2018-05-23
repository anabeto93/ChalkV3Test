import I18n from 'i18n-js';
import { Button } from '@material-ui/core';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { UnBlockSession } from '../services/session/UnBlockSession';
import stringifyUserAnswers from '../services/quiz/stringifyUserAnswers';
import CourseManager from '../services/CourseManager';
import getConfig from '../config/index';
import NotWorking from '../components/SMS/NotWorking';

const DEFAULT_STATE = {
  showNotWorking: false
};

class SendSMSScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentDidMount() {
    const { sessionUuid, userUuid, sessionQuestions } = this.props;
    let validationCode = UnBlockSession.getUnlockCodeForSession(
      userUuid,
      sessionUuid,
      getConfig().appPrivateKey
    );

    if (sessionQuestions) {
      validationCode += ' ' + stringifyUserAnswers(sessionQuestions);
    }

    this.setState({ validationCode });
  }

  openSMSAppLink = validationCode => {
    if (/iPhone|iPad|iPod|Mac/i.test(navigator.userAgent)) {
      if (/iP(hone|od|ad)/i.test(navigator.userAgent)) {
        var v = navigator.appVersion.match(/OS (\d+)_(\d+)_?(\d+)?/);
        //Below iOS 8
        if (parseInt(v[1], 10) < 8) {
          return `sms:${getConfig().apiPhoneNumber};body=${validationCode}`;
        }
      }

      //iOS 8 and above
      return `sms:${getConfig().apiPhoneNumber}&body=${validationCode}`;
    }

    //Every other platform
    return `sms:${getConfig().apiPhoneNumber}?body=${validationCode}`;
  };

  showNotWorking = () => {
    this.setState({ showNotWorking: true });
  };

  render() {
    const { locale } = this.props;
    const validationCode = this.state.validationCode;

    return (
      <div className="screen-centered">
        <h4>
          {I18n.t('send.sms.title', { locale })}
        </h4>

        <p>
          {I18n.t('send.sms.label', { locale })}
        </p>

        {!this.state.showNotWorking &&
          <div>
            <div>
              <Button
                variant="raised"
                href={this.openSMSAppLink(validationCode)}
                target="_blank"
                color="primary"
              >
                {I18n.t('send.sms.button', { locale })}
              </Button>
            </div>

            <div style={{ margin: '20px' }}>
              <Button
                onClick={this.showNotWorking}
                color="secondary"
                style={{ fontSize: '12px' }}
              >
                {I18n.t('send.sms.notworking.button', { locale })}
              </Button>
            </div>
          </div>}

        {this.state.showNotWorking &&
          <NotWorking validationCode={validationCode} locale={locale} />}
      </div>
    );
  }
}

const mapStateToProps = (state, props) => {
  const { settings: { locale } } = state;
  const { match: { params: { sessionUuid } } } = props;
  const { currentUser: { uuid } } = state;

  const session = CourseManager.getSession(state.content.sessions, sessionUuid);
  let sessionQuestions;

  if (session !== undefined) {
    sessionQuestions = session.questions;
  }

  return { locale, sessionUuid, userUuid: uuid, sessionQuestions };
};

export default connect(mapStateToProps)(SendSMSScreen);
