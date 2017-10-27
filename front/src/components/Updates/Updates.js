import I18n from 'i18n-js';
import LinearProgress from 'material-ui/LinearProgress';
import RaisedButton from 'material-ui/RaisedButton';
import React, { Component } from 'react';
import Snackbar from 'material-ui/Snackbar';
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
      return;
    }

    const isAlreadyUpToDate =
      !isErrorFetching && currentIsFetching && !isFetching && !hasUpdates;

    if (isAlreadyUpToDate) {
      this.setState({ ...this.state, isAlreadyUpToDate: true });
      return;
    }

    this.setState({
      ...this.state,
      isUpdated: false,
      isAlreadyUpToDate: false,
      isErrorWhileCheckingUpdates: false,
      isErrorWhileUpdating: false
    });
  }

  handleRequestClose = () => {
    this.setState({
      ...this.state,
      isUpdated: false,
      isAlreadyUpToDate: false,
      isErrorWhileCheckingUpdates: false,
      isErrorWhileUpdating: false
    });
  };

  handleRetryCheckUpdates = event => {
    event.preventDefault();
    this.props.dispatch(getUpdates(this.props.content.updatedAt));
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

    if (!network.isOnline) {
      return (
        <div className="updates-container">
          <small>
            {I18n.t('update.offline', { locale })}
          </small>
        </div>
      );
    }

    if (percentSpoolCompleted < 100) {
      return (
        <div>
          <LinearProgress mode="determinate" value={percentSpoolCompleted} />
          <div className="updates-container">
            <p>
              {I18n.t('update.stayOnline', { locale })}
            </p>
          </div>
        </div>
      );
    }

    if (this.state.isErrorWhileCheckingUpdates) {
      return (
        <div className="updates-container">
          <p>
            {I18n.t('update.errorWhileCheckingUpdates', { locale })}
          </p>
          <RaisedButton
            label={I18n.t('update.retry', { locale })}
            primary={true}
            onClick={this.handleRetryCheckUpdates}
          />
        </div>
      );
    }

    if (content.isFetching) {
      return (
        <div className="updates-container">
          {I18n.t('update.updating', { locale })}
        </div>
      );
    }

    if (updates.isFetching) {
      return (
        <div className="updates-container">
          {I18n.t('update.checking', { locale })}
        </div>
      );
    }

    if (updates.hasUpdates) {
      return (
        <div className="updates-container">
          <p>
            {I18n.t('update.needUpdate', { locale })}
          </p>
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

    return (
      <div>
        <Snackbar
          open={this.state.isAlreadyUpToDate}
          message={I18n.t('update.upToDate', { locale })}
          autoHideDuration={MESSAGE_DELAY_IN_SECONDS * 1000}
        />
        <Snackbar
          open={this.state.isUpdated}
          message={I18n.t('update.updateSuccess', { locale })}
          autoHideDuration={MESSAGE_DELAY_IN_SECONDS * 1000}
        />
        <Snackbar
          open={this.state.isErrorWhileUpdating}
          message={I18n.t('errorWhileUpdating', { locale })}
          autoHideDuration={MESSAGE_DELAY_IN_SECONDS * 1000}
          onRequestClose={this.handleRequestClose}
        />
      </div>
    );
  }
}

function mapStateToProps({ content, network, settings: { locale }, updates }) {
  return { content, locale, network, updates };
}

export default connect(mapStateToProps)(Updates);
