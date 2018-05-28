import React, { Component } from 'react';
import {
  Button,
  LinearProgress,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogContentText,
  DialogActions
} from '@material-ui/core';
import I18n from 'i18n-js';

class UpdateDialog extends Component {
  constructor(props) {
    super(props);
    this.state = { open: true };
  }

  componentWillReceiveProps(nextProps) {
    if (
      nextProps.percentSpoolCompleted &&
      nextProps.percentSpoolCompleted === 100
    ) {
      this.handleClose();
    }
  }

  handleClose = () => {
    this.setState({ open: false });
  };

  render() {
    const { locale, percentSpoolCompleted } = this.props;

    return (
      <Dialog
        disableBackdropClick={true}
        open={this.state.open}
        onClose={this.handleClose}
      >
        <DialogTitle>
          {I18n.t('update.downloadingContent', { locale })}
        </DialogTitle>

        <DialogContent>
          <DialogContentText style={{ marginBottom: '1em' }}>
            {I18n.t('update.stayOnline', { locale })}
          </DialogContentText>

          <LinearProgress variant="determinate" value={percentSpoolCompleted} />
        </DialogContent>

        <DialogActions>
          <Button color="primary" onClick={this.handleClose} fullWidth>
            {I18n.t('update.dismiss', { locale })}
          </Button>
        </DialogActions>
      </Dialog>
    );
  }
}

export default UpdateDialog;
