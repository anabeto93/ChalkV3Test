import React from 'react';
import {
  RadioGroup,
  Radio,
  Checkbox,
  FormControl,
  FormControlLabel
} from '@material-ui/core';

const QuestionAnswers = props => {
  const { question } = props;

  const userAnswers = question.userAnswers ? question.userAnswers : [];

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
                  checked={userAnswers.includes(index)}
                  onChange={() => {
                    props.handleAnswerChange(index);
                  }}
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
        value={userAnswers[0] && userAnswers[0].toString()}
        onChange={(e, value) => {
          props.handleAnswerChange(value);
        }}
      >
        {Object.keys(question.answers).map((key, index) => {
          const answer = question.answers[index];

          return (
            <FormControlLabel
              key={index}
              label={answer.title}
              value={index.toString()}
              checked={index === userAnswers[0]}
              control={<Radio color="primary" />}
            />
          );
        })}
      </RadioGroup>
    </FormControl>
  );
};

export default QuestionAnswers;
