import React, { Component } from 'react';
import {
  TextField,
  Popover,
  InputAdornment,
  Typography
} from '@material-ui/core';
import { Smartphone, Sms } from '@material-ui/icons';
import ClipboardJS from 'clipboard';
import I18n from 'i18n-js';
import getConfig from '../../config/index';

class NotWorking extends Component {
  constructor(props) {
    super(props);
    this.state = { anchorEl: null };
  }

  componentDidMount() {
    new ClipboardJS('#phone-number', {
      target: function(trigger) {
        return trigger;
      }
    });

    new ClipboardJS('#validation-code', {
      target: function(trigger) {
        return trigger;
      }
    });
  }

  handlePopoverOpen = e => {
    this.setState({
      anchorEl: e.target
    });

    setTimeout(() => this.handlePopoverClose(), 1000);
  };

  handlePopoverClose = () => {
    this.setState({
      anchorEl: null
    });
  };

  render() {
    const { validationCode, locale } = this.props;

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
            label={I18n.t('send.sms.notworking.toPhone', { locale })}
            onClick={this.handlePopoverOpen}
            fullWidth={true}
            InputProps={{
              startAdornment: (
                <InputAdornment position="start">
                  <Smartphone />
                </InputAdornment>
              )
            }}
          />
        </div>
        <div style={{ marginTop: '1em' }}>
          <TextField
            id="validation-code"
            data-clipboard-target="#validation-code"
            defaultValue={validationCode}
            label={I18n.t('send.sms.notworking.validationCode', {
              locale
            })}
            onClick={this.handlePopoverOpen}
            fullWidth={true}
            InputProps={{
              startAdornment: (
                <InputAdornment position="start">
                  <Sms />
                </InputAdornment>
              )
            }}
          />
        </div>
        <Popover
          open={Boolean(this.state.anchorEl)}
          anchorEl={this.state.anchorEl}
          onClose={this.handlePopoverClose}
          anchorOrigin={{
            vertical: 'top',
            horizontal: 'right'
          }}
          transformOrigin={{
            vertical: 'bottom',
            horizontal: 'right'
          }}
        >
          <Typography style={{ margin: '0.75em' }}>Copied</Typography>
        </Popover>
      </div>
    );
  }
}

export default NotWorking;
