import React, { Component } from 'react';
import { connect } from 'react-redux';
import I18n from 'i18n-js';
import {
  RadioGroup,
  Radio,
  Checkbox,
  FormControl,
  FormControlLabel,
  Button
} from '@material-ui/core';

class MCQ extends Component {
  constructor(props) {
    super(props);
    this.state = {
      enabled: true,
      userAnswers: []
    };
  }

  handleAnswerChange = answerIndex => {
    answerIndex = parseInt(answerIndex, 10);

    if (
      this.props.question.isMultiple &&
      this.state.userAnswers !== undefined
    ) {
      if (this.state.userAnswers.indexOf(answerIndex) > -1) {
        //Answer already exists in state so remove it
        this.setState({
          userAnswers: [
            ...this.state.userAnswers.filter(answer => {
              return answer !== answerIndex;
            })
          ]
        });
      } else {
        this.setState({
          userAnswers: [...this.state.userAnswers, answerIndex]
        });
      }

      return;
    }

    //Singular answer
    this.setState({
      userAnswers: [answerIndex]
    });
  };

  submitAnswer = () => {
    const { question } = this.props;

    let correct = false;

    if (question.isMultiple) {
      //Check the answers in the userAnswers array if they're the same length and contain the same answers when sorted
      if (
        this.state.userAnswers.length === question.correctAnswers.length &&
        JSON.stringify(this.state.userAnswers.sort()) ===
          JSON.stringify(question.correctAnswers.sort())
      ) {
        correct = true;
      }
    } else {
      if (this.state.userAnswers[0] === question.correctAnswers[0]) {
        correct = true;
      }
    }

    this.props.openFeedback(correct);
    this.setState({ enabled: false });
  };

  renderAnswers = () => {
    const { question } = this.props;

    if (question.isMultiple) {
      return (
        <FormControl component="fieldset">
          {Object.keys(question.answers).map((key, index) => {
            const answer = question.answers[index];

            return (
              <FormControlLabel
                key={index}
                control={
                  <Checkbox
                    color="primary"
                    checked={this.state.userAnswers.includes(index)}
                    onChange={() => {
                      this.handleAnswerChange(index);
                    }}
                    disabled={this.state.enabled === false}
                  />
                }
                label={answer.title}
              />
            );
          })}
        </FormControl>
      );
    }

    return (
      <FormControl component="fieldset">
        <RadioGroup
          aria-label={question.title}
          name={question.title}
          value={
            this.state.userAnswers.length > 0
              ? this.state.userAnswers[0].toString()
              : ''
          }
          onChange={(e, value) => {
            this.handleAnswerChange(value);
          }}
        >
          {Object.keys(question.answers).map((key, index) => {
            const answer = question.answers[index];

            return (
              <FormControlLabel
                key={index}
                label={answer.title}
                value={index.toString()}
                checked={index === this.state.userAnswers[0]}
                control={<Radio color="primary" />}
                disabled={this.state.enabled === false}
              />
            );
          })}
        </RadioGroup>
      </FormControl>
    );
  };

  render() {
    const { locale } = this.props;

    return (
      <div>
        {this.renderAnswers()}

        <div style={{ flex: 1, textAlign: 'center', padding: '1em' }}>
          <Button
            variant="raised"
            color="primary"
            disabled={this.state.userAnswers.length < 1}
            onClick={this.submitAnswer}
          >
            {I18n.t('selfTestQuiz.checkAnswer', { locale })}
          </Button>
        </div>
      </div>
    );
  }
}

function mapStateToProps(state) {
  return { locale: state.settings.locale };
}

export default connect(mapStateToProps)(MCQ);
