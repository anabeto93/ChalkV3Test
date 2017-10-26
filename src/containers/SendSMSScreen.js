import Clipboard from 'clipboard';
import I18n from 'i18n-js';
import { FlatButton, RaisedButton, TextField } from 'material-ui';
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
        <TextField
          id="phone-number"
          data-clipboard-target="#phone-number"
          defaultValue={getConfig().apiPhoneNumber}
          floatingLabelText={I18n.t('send.sms.notworking.toPhone', { locale })}
          fullWidth={true}
        />
      </div>
      <div>
        <TextField
          id="validation-code"
          data-clipboard-target="#validation-code"
          defaultValue={validationCode}
          floatingLabelText={I18n.t('send.sms.notworking.validationCode', {
            locale
          })}
          fullWidth={true}
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
      getConfig().appPrivateKey
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
              <RaisedButton
                href={this.openSMSAppLink(validationCode)}
                target="_blank"
                label={I18n.t('send.sms.button', { locale })}
              />
            </div>

            <div style={{ margin: '20px' }}>
              <FlatButton
                label={I18n.t('send.sms.notworking.button', { locale })}
                onClick={this.showNotWorking}
                secondary={true}
                labelStyle={{ fontSize: '12px' }}
              />
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

  return { locale, sessionUuid, userUuid: uuid };
};

export default connect(mapStateToProps)(SendSMSScreen);
