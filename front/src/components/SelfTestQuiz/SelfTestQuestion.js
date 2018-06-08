import React, { Component } from 'react';
import { Typography } from '@material-ui/core';
import Feedback from './Feedback';
import MCQ from './MCQ';

class SelfTestQuiz extends Component {
  constructor(props) {
    super(props);
    this.state = {
      feedback: false,
      correct: false
    };
  }

  openFeedback = correct => {
    const { question } = this.props;
    if (question.feedback) {
      this.setState({
        feedback: true,
        correct: correct
      });
    }
  };

  closeFeedback = () => {
    this.setState({
      feedback: false
    });
  };

  renderQuizType = () => {
    const { question } = this.props;

    if (/mcq/i.test(question.type)) {
      return (
        <MCQ
          question={question}
          openFeedback={this.openFeedback}
          correct={this.state.correct}
        />
      );
    }

    return <div />;
  };

  render() {
    const { question } = this.props;

    return (
      <div>
        <Typography variant="headline">
          {question.title}
        </Typography>

        {question.subtitle &&
          <Typography variant="subheading">
            {question.subtitle}
          </Typography>}

        {this.renderQuizType()}

        <Feedback
          open={this.state.feedback}
          close={this.closeFeedback}
          correct={this.state.correct}
          question={question}
        />
      </div>
    );
  }
}

export default SelfTestQuiz;
