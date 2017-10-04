import I18n from 'i18n-js';
import { RaisedButton } from 'material-ui';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { UnBlockSession } from '../services/session/UnBlockSession';

const DEFAULT_STATE = {
  showNotWorking: false
};

const NotWorking = props => {
  const { validationCode, locale } = props;
  return (
    <div>
      <p>
        {I18n.t('send.sms.notworking.label', { locale })}
      </p>
      <p>
        {I18n.t('send.sms.notworking.toPhone', { locale })} : +3323232323
      </p>
      <p>
        {I18n.t('send.sms.notworking.validationCode', { locale })} :{' '}
        <span>{validationCode}</span>
      </p>
    </div>
  );
};

class SendSMSScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  openSMSAppLink = (phone, validationCode) => {
    return `sms://${phone}?body=${validationCode}`;
  };

  showNotWorking = () => {
    this.setState({ showNotWorking: true });
  };

  render() {
    const { locale, sessionUuid, phoneNumber } = this.props;
    const validationCode = UnBlockSession.getCodeFromUuid(sessionUuid);

    return (
      <div className="content-layout">
        <p>
          {I18n.t('send.sms.label', { locale })}
        </p>
        <RaisedButton
          href={this.openSMSAppLink(phoneNumber, validationCode)}
          target="_blank"
          label={I18n.t('send.sms.button', { locale })}
        />

        <div onClick={this.showNotWorking}>
          {I18n.t('send.sms.notworking.button', { locale })}
        </div>

        {this.state.showNotWorking &&
          <NotWorking validationCode={validationCode} locale={locale} />}
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
