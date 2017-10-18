import Clipboard from 'clipboard';
import I18n from 'i18n-js';
import { RaisedButton, TextField } from 'material-ui';
import React, { Component } from 'react';
import { connect } from 'react-redux';
import { UnBlockSession } from '../services/session/UnBlockSession';
import getConfig from '../config/index';

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
      <div>
        <span>
          {I18n.t('send.sms.notworking.toPhone', { locale })}:{' '}
        </span>
        <TextField
          id="phone-number"
          data-clipboard-target="#phone-number"
          defaultValue={getConfig().apiPhoneNumber}
        />
      </div>
      <div>
        <span>
          {I18n.t('send.sms.notworking.validationCode', { locale })}:{' '}
        </span>
        <TextField
          id="validation-code"
          data-clipboard-target="#validation-code"
          defaultValue={validationCode}
        />
      </div>
    </div>
  );
};

class SendSMSScreen extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentDidMount() {
    const { sessionUuid, userUuid } = this.props;
    const validationCode = UnBlockSession.getUnlockCodeForSession(
      userUuid,
      sessionUuid,
      getConfig().apiPrivateKey
    );

    new Clipboard('#phone-number');
    new Clipboard('#validation-code');

    this.setState({ validationCode });
  }

  openSMSAppLink = validationCode => {
    return `sms:${getConfig().apiPhoneNumber}?body=${validationCode}`;
  };

  showNotWorking = () => {
    this.setState({ showNotWorking: true });
  };

  render() {
    const { locale } = this.props;
    const validationCode = this.state.validationCode;

    const style = {
      notWorking: {
        display: 'block',
        textDecoration: 'underline',
        marginTop: '15px',
        cursor: 'pointer',
        padding: '0',
        border: 'none',
        background: 'none'
      }
    };

    return (
      <div className="content-layout">
        <h4>
          {I18n.t('send.sms.title', { locale })}
        </h4>
        <p>
          {I18n.t('send.sms.label', { locale })}
        </p>
        <RaisedButton
          href={this.openSMSAppLink(validationCode)}
          target="_blank"
          label={I18n.t('send.sms.button', { locale })}
          fullWidth={true}
        />

        <button style={style.notWorking} onClick={this.showNotWorking}>
          {I18n.t('send.sms.notworking.button', { locale })}
        </button>

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

  return { locale, sessionUuid, userUuid: uuid };
};

export default connect(mapStateToProps)(SendSMSScreen);
