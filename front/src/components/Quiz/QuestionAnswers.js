import React from 'react';
import { RadioButtonGroup, RadioButton, Checkbox } from 'material-ui';

const QuestionAnswers = props => {
  const { question } = props;
  const optionStyle = {
    marginBottom: 16
  };

  const userAnswers = question.userAnswers ? question.userAnswers : [];

  if (question.isMultiple) {
    return (
      <div>
        {Object.keys(question.answers).map((key, index) => {
          const answer = question.answers[index];

          return (
            <Checkbox
              key={index}
              label={answer.title}
              checked={userAnswers.includes(index)}
              onCheck={() => {
                props.handleAnswerChange(index);
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
      valueSelected={userAnswers[0]}
      onChange={(e, value) => {
        props.handleAnswerChange(value);
      }}
    >
      {Object.keys(question.answers).map((key, index) => {
        const answer = question.answers[index];

        return (
          <RadioButton
            key={index}
            label={answer.title}
            value={index}
            style={optionStyle}
          />
        );
      })}
    </RadioButtonGroup>
  );
};

export default QuestionAnswers;
