import React, { Component } from 'react';
import { connect } from 'react-redux';
import QuestionFooter from '../components/QuestionFooter';
import CourseManager from '../services/CourseManager';
import { RadioButtonGroup, RadioButton, Checkbox } from 'material-ui';

class QuestionDetailScreen extends Component {
  handleRadioChange = answer => {
    //Do something with the answer
  };

  handleCheckChange = answer => {
    //Do something with multiple answers
  };

  render() {
    const { question, session } = this.props;

    const styles = {
      option: {
        marginBottom: 16
      }
    };

    if (question !== undefined) {
      return (
        <div>
          <div className="content">
            <h1>
              {question.title}
            </h1>

            {/* Single Answer [RadioButton] in [RadioButtonGroup] */}
            {!question.isMultiple &&
              <RadioButtonGroup
                name={question.title}
                onChange={(e, value) => {
                  this.handleRadioChange(value);
                }}
              >
                {question.answers.map((key, index) => {
                  const answer = question.answers[key];

                  return (
                    <RadioButton
                      key={index}
                      label={answer.title}
                      value={answer.uuid}
                      style={styles.option}
                    />
                  );
                })}
              </RadioButtonGroup>}

            {/* Multiple Answers [Checkbox] */}
            {question.isMultiple &&
              question.answers.map((key, index) => {
                const answer = question.answers[key];

                return (
                  <Checkbox
                    key={index}
                    label={answer.title}
                    onCheck={(e, value) => {
                      this.handleCheckChange(answer.uuid);
                    }}
                    style={styles.option}
                  />
                );
              })}
          </div>
          <QuestionFooter session={session} />
        </div>
      );
    }

    return <div />;
  }
}

function mapStateToProps(state, props) {
  let sessionUuid = props.match.params.sessionUuid;
  let questionUuid = props.match.params.questionUuid;

  if (sessionUuid === undefined) {
    return {};
  }

  if (questionUuid === undefined) {
    return {};
  }

  const session = CourseManager.getSession(state.content.sessions, sessionUuid);

  if (session === null || session.questions === undefined) {
    return {};
  }

  const question = CourseManager.getQuestion(session, questionUuid);

  return {
    sessionUuid,
    session,
    question
  };
}

export default connect(mapStateToProps)(QuestionDetailScreen);
