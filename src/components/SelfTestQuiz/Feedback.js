import React, { Component } from 'react';
import { connect } from 'react-redux';
import I18n from 'i18n-js';
import { ThumbUp, ThumbDown, Check, Close } from '@material-ui/icons';
import { red, lightGreen } from '@material-ui/core/colors';
import {
  Avatar,
  Dialog,
  DialogTitle,
  DialogContent,
  DialogContentText,
  DialogActions,
  Button,
  Table,
  TableBody,
  TableRow,
  TableCell,
  Typography
} from '@material-ui/core';

const positiveStyle = {
  color: '#fff',
  backgroundColor: lightGreen[500]
};

const negativeStyle = {
  color: '#fff',
  backgroundColor: red[500]
};

class Feedback extends Component {
  handleClose = () => {
    this.props.close();
  };

  renderIcon = () => {
    if (this.props.correct) {
      return (
        <Avatar style={positiveStyle}>
          <ThumbUp />
        </Avatar>
      );
    }

    return (
      <Avatar style={negativeStyle}>
        <ThumbDown />
      </Avatar>
    );
  };

  showResponses = () => {
    const { question, locale } = this.props;

    return (
      <React.Fragment>
        <Typography style={{ paddingTop: '1em' }}>
          {I18n.t('selfTestQuiz.answers', { locale })}:
        </Typography>
        <Table>
          <TableBody>
            {Object.keys(question.answers).map((key, index) => {
              const answer = question.answers[index];

              return (
                <TableRow key={key}>
                  <TableCell padding="dense">
                    {question.correctAnswers.includes(index)
                      ? <Check style={positiveStyle} />
                      : <Close style={negativeStyle} />}
                  </TableCell>
                  <TableCell>
                    {answer.title}
                  </TableCell>
                </TableRow>
              );
            })}
          </TableBody>
        </Table>
      </React.Fragment>
    );
  };

  render() {
    const { open, question: { feedback }, locale } = this.props;

    return (
      <Dialog
        open={open}
        onClose={this.handleClose}
        aria-labelledby="alert-dialog-title"
        aria-describedby="alert-dialog-description"
      >
        <DialogTitle id="alert-dialog-title">
          {this.props.correct ? feedback.good : feedback.bad}
        </DialogTitle>

        <DialogContent>
          <div
            style={{
              display: 'flex',
              justifyContent: 'center',
              paddingBottom: '0.7em'
            }}
          >
            {this.renderIcon()}
          </div>
          <DialogContentText id="alert-dialog-description">
            {feedback.text}
          </DialogContentText>

          {this.showResponses()}
        </DialogContent>

        <DialogActions>
          <Button
            onClick={this.handleClose}
            color="primary"
            variant="raised"
            fullWidth
          >
            {I18n.t('selfTestQuiz.okay', { locale })}
          </Button>
        </DialogActions>
      </Dialog>
    );
  }
}

function mapStateToProps(state) {
  return { locale: state.settings.locale };
}

export default connect(mapStateToProps)(Feedback);
