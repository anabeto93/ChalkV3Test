import { connect } from 'react-redux';
import LinearProgress from 'material-ui/LinearProgress';
import RaisedButton from 'material-ui/RaisedButton';
import React, { Component } from 'react';

import {
  getCoursesInformations,
  getUpdates
} from '../../actions/actionCreators';

const DEFAULT_STATE = {
  isAlreadyUpToDate: false,
  isErrorWhileCheckingUpdates: false,
  isErrorWhileUpdating: false,
  spoolCompleted: 0
};
const MESSAGE_DELAY_IN_SECONDS = 5;

export class Updates extends Component {
  constructor(...args) {
    super(...args);
    this.state = DEFAULT_STATE;
  }

  componentWillReceiveProps(nextProps) {
    const { isFetching: currentIsFetching } = this.props.updates;
    const { hasUpdates, isErrorFetching, isFetching } = nextProps.updates;

    const isErrorWhileCheckingUpdates =
      isErrorFetching && currentIsFetching && !isFetching;

    if (isErrorWhileCheckingUpdates) {
      this.setState({ ...this.state, isErrorWhileCheckingUpdates: true });
      return;
    }

    const isErrorWhileUpdating =
      this.props.courses.isFetching &&
      nextProps.courses.isErrorFetching &&
      !nextProps.courses.isFetching &&
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
    const { courses, network, updates } = this.props;
    const currentSpoolTotal = courses.spool.total;
    const spoolCompleted = this.state.spoolCompleted;
    const percentSpoolCompleted =
      currentSpoolTotal > 0
        ? Math.round(spoolCompleted * 100 / currentSpoolTotal)
        : 100;

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

    if (courses.isFetching) {
      return <div style={style.container}>Updating app...</div>;
    }

    if (updates.isFetching) {
      return <div style={style.container}>Checking updates...</div>;
    }

    if (updates.hasUpdates) {
      return (
        <div style={style.container}>
          <p>Your app must be updated</p>
          <p>
            <small>
              {Math.round(1000 * updates.size / 1024) / 1000}
              kb to download
            </small>
          </p>
          <RaisedButton
            label="Update"
            primary={true}
            onClick={this.handleLoad}
          />
        </div>
      );
    }

    if (this.state.isAlreadyUpToDate) {
      return <div style={style.container}>Your app is already up to date!</div>;
    }

    return null;
  }
}

function mapStateToProps({ courses, network, updates }) {
  return { courses, network, updates };
}

export default connect(mapStateToProps)(Updates);
