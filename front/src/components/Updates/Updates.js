import I18n from 'i18n-js';
import LinearProgress from 'material-ui/LinearProgress';
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
  isErrorWhileUpdating: false,
  isUpdated: false,
  spoolCompleted: 0
};
const MESSAGE_DELAY_IN_SECONDS = 5;

class Updates extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentWillReceiveProps(nextProps) {
    const { isFetching: currentIsFetching } = this.props.updates;
    const { hasUpdates, isErrorFetching, isFetching } = nextProps.updates;

    if (
      0 < this.props.content.spool.total &&
      0 === nextProps.content.spool.total
    ) {
      this.setState({ ...this.state, spoolCompleted: 0, isUpdated: true });
      this.handleShortMessage('isUpdated');
      return;
    }

    const isErrorWhileCheckingUpdates =
      isErrorFetching && currentIsFetching && !isFetching;

    if (isErrorWhileCheckingUpdates) {
      this.setState({ ...this.state, isErrorWhileCheckingUpdates: true });
      return;
    }

    const isErrorWhileUpdating =
      this.props.content.isFetching &&
      nextProps.content.isErrorFetching &&
      !nextProps.content.isFetching &&
      hasUpdates;

    if (isErrorWhileUpdating) {
      this.setState({ ...this.state, isErrorWhileUpdating: true });
      this.handleShortMessage('isErrorWhileUpdating');
      return;
    }

    const isAlreadyUpToDate =
      !isErrorFetching && currentIsFetching && !isFetching && !hasUpdates;

    if (isAlreadyUpToDate) {
      this.setState({ ...this.state, isAlreadyUpToDate: true });
      this.handleShortMessage('isAlreadyUpToDate');
      return;
    }

    this.setState({
      ...this.state,
      isAlreadyUpToDate: false,
      isErrorWhileCheckingUpdates: false,
      isErrorWhileUpdating: false
    });
  }

  handleShortMessage(stateName) {
    setTimeout(
      () => this.setState({ ...this.state, [stateName]: false }),
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
    const { content, locale, network, updates } = this.props;
    const spoolTotal = content.spool.total;
    const spoolUncompleted =
      content.spool.sessionText.length + content.spool.sessionFiles.length;
    const spoolCompleted = spoolTotal - spoolUncompleted;
    const percentSpoolCompleted =
      spoolTotal > 0 ? Math.round(spoolCompleted * 100 / spoolTotal) : 100;

    const style = {
      container: {
        padding: '10px',
        backgroundColor: '#eeeeee',
        textAlign: 'center'
      }
    };

    if (this.state.isUpdated) {
      return (
        <div style={style.container}>
          <p>App updated successfully!</p>
        </div>
      );
    }

    if (!network.isOnline) {
      return (
        <div style={style.container}>
          <small>You are offline.</small>
        </div>
      );
    }

    if (percentSpoolCompleted < 100) {
      return (
        <div>
          <LinearProgress mode="determinate" value={percentSpoolCompleted} />
          <div style={style.container}>
            <p>Please stay online when downloading updates</p>
          </div>
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

    if (content.isFetching) {
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

function mapStateToProps({ content, network, settings: { locale }, updates }) {
  return { content, locale, network, updates };
}

export default connect(mapStateToProps)(Updates);
