import React from 'react';
import { RadioButtonGroup, RadioButton, Checkbox } from 'material-ui';

const QuestionAnswers = props => {
  const { question } = props;
  const optionStyle = {
    marginBottom: 16
  };

  if (question.isMultiple) {
    return (
      <div>
        {Object.keys(question.answers).map((key, index) => {
          const answer = question.answers[key];

          return (
            <Checkbox
              key={index}
              label={answer.title}
              onCheck={() => {
                props.handleAnswerChange(key);
              }}
              style={optionStyle}
            />
          );
        })}
      </div>
    );
  }

  return (
    <RadioButtonGroup
      name={question.title}
      onChange={(e, value) => {
        props.handleAnswerChange(value);
      }}
    >
      {Object.keys(question.answers).map((key, index) => {
        const answer = question.answers[key];

        return (
          <RadioButton
            key={index}
            label={answer.title}
            value={key}
            style={optionStyle}
          />
        );
      })}
    </RadioButtonGroup>
  );
};

export default QuestionAnswers;
