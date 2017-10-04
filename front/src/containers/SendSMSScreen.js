import I18n from 'i18n-js';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { UnBlockSession } from '../services/session/UnBlockSession';

const NotWorking = props => {
  const { validationCode, locale } = props;
  return (
    <div>
      <p>
        {I18n.t('send.sms.notworking.label', { locale })}
      </p>
      <p>To phone</p>
      <p>
        Text to send: <span>{validationCode}</span>
      </p>
    </div>
  );
};

class SendSMSScreen extends Component {
  openSMSAppLink = (phone, validationCode) => {
    return `sms://+${phone}?body=${validationCode}`;
  };

  render() {
    const { locale, sessionUuid, phoneNumber } = this.props;
    const validationCode = UnBlockSession.getCodeFromUuid(sessionUuid);

    return (
      <div className="content-layout">
        <p>
          {I18n.t('send.sms.label', { locale })}
        </p>
        <a
          href={this.openSMSAppLink(phoneNumber, validationCode)}
          className="button-primary"
        >
          {I18n.t('send.sms.button', { locale })}
        </a>

        <NotWorking validationCode={validationCode} locale={locale} />
      </div>
    );
  }
}

const mapStateToProps = (state, props) => {
  const { currentUser: { phoneNumber } } = state;
  const { settings: { locale } } = state;
  const { match: { params: { sessionUuid } } } = props;

  return { phoneNumber, locale, sessionUuid };
};

export default connect(mapStateToProps)(SendSMSScreen);
