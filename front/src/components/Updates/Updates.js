import I18n from 'i18n-js';
import RaisedButton from 'material-ui/RaisedButton';
import React, { Component } from 'react';
import { connect } from 'react-redux';

import {
  getCoursesInformations,
  getUpdates
} from '../../actions/actionCreators';

const DEFAULT_STATE = {
  isAlreadyUpToDate: false,
  isErrorWhileCheckingUpdates: false,
  isErrorWhileUpdating: false
};
const MESSAGE_DELAY_IN_SECONDS = 5;

class Updates extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentWillReceiveProps(nextProps) {
    console.log(nextProps);
    const { isFetching: currentIsFetching } = this.props.updates;
    const { hasUpdates, isErrorFetching, isFetching } = nextProps.updates;

    const isErrorWhileCheckingUpdates =
      isErrorFetching && currentIsFetching && !isFetching;

    if (isErrorWhileCheckingUpdates) {
      this.setState({ ...DEFAULT_STATE, isErrorWhileCheckingUpdates: true });
      return;
    }

    const isErrorWhileUpdating =
      this.props.courses.isFetching &&
      nextProps.courses.isErrorFetching &&
      !nextProps.courses.isFetching &&
      hasUpdates;

    if (isErrorWhileUpdating) {
      this.setState({ ...DEFAULT_STATE, isErrorWhileUpdating: true });
      this.handleShortMessage('isErrorWhileUpdating');
      return;
    }

    const isAlreadyUpToDate =
      !isErrorFetching && currentIsFetching && !isFetching && !hasUpdates;

    if (isAlreadyUpToDate) {
      this.setState({ ...DEFAULT_STATE, isAlreadyUpToDate: true });
      this.handleShortMessage('isAlreadyUpToDate');
      return;
    }

    this.setState(DEFAULT_STATE);
  }

  handleShortMessage(stateName) {
    setTimeout(
      () => this.setState({ ...DEFAULT_STATE, [stateName]: false }),
      MESSAGE_DELAY_IN_SECONDS * 1000
    );
  }

  handleRetryCheckUpdates = event => {
    event.preventDefault();
    this.props.dispatch(getUpdates());
  };

  handleLoad = event => {
    event.preventDefault();
    this.props.dispatch(getCoursesInformations());
  };

  render() {
    const { courses, locale, network, updates } = this.props;

    const style = {
      container: {
        padding: '10px',
        backgroundColor: '#eeeeee',
        textAlign: 'center'
      }
    };

    if (!network.isOnline) {
      return (
        <div style={style.container}>
          <small>You are offline.</small>
        </div>
      );
    }

    if (this.state.isErrorWhileUpdating) {
      return (
        <div style={style.container}>
          <p>There is a network problem while updating.</p>
        </div>
      );
    }

    if (this.state.isErrorWhileCheckingUpdates) {
      return (
        <div style={style.container}>
          <p>There is a network problem while checking updates.</p>
          <RaisedButton
            label="Retry"
            primary={true}
            onClick={this.handleRetryCheckUpdates}
          />
        </div>
      );
    }

    if (courses.isFetching) {
      return (
        <div style={style.container}>
          {I18n.t('update.updating', { locale })}
        </div>
      );
    }

    if (updates.isFetching) {
      return (
        <div style={style.container}>
          {I18n.t('update.checking', { locale })}
        </div>
      );
    }

    if (updates.hasUpdates) {
      return (
        <div style={style.container}>
          <p>Your app must be updated</p>
          <p>
            <small>
              {I18n.t('update.download', {
                amount: Math.round(1000 * updates.size / 1024) / 1000,
                locale
              })}
            </small>
          </p>
          <RaisedButton
            label={I18n.t('update.label', { locale })}
            primary={true}
            onClick={this.handleLoad}
          />
        </div>
      );
    }

    if (this.state.alreadyUpToDate) {
      return (
        <div style={style.container}>
          {I18n.t('update.upToDate', { locale })}
        </div>
      );
    }

    return null;
  }
}

function mapStateToProps({ courses, network, settings: { locale }, updates }) {
  return { courses, locale, network, updates };
}

export default connect(mapStateToProps)(Updates);
