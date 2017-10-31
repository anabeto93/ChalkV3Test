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
                props.handleCheckChange(answer.uuid);
              }}
              style={optionStyle}
            />
          );
        })}
      </div>
    );
  } else {
    return (
      <RadioButtonGroup
        name={question.title}
        onChange={(e, value) => {
          props.handleRadioChange(value);
        }}
      >
        {Object.keys(question.answers).map((key, index) => {
          const answer = question.answers[key];

          return (
            <RadioButton
              key={index}
              label={answer.title}
              value={answer.uuid}
              style={optionStyle}
            />
          );
        })}
      </RadioButtonGroup>
    );
  }
};

export default QuestionAnswers;
