import React, { Component } from 'react';
import { connect } from 'react-redux';
// import QuestionFooter from '../components/QuestionFooter';
import CourseManager from '../services/CourseManager';
import { RadioButtonGroup, RadioButton, Checkbox } from 'material-ui';

class QuestionDetailScreen extends Component {
	handleRadioChange = (answer) => {
		//Do something with the answer
	}

	handleCheckChange = (answer) => {
		//Do something with multiple answers
	}

	render() {
		const { question, sessionUuid } = this.props;

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
						{
							!question.isMultiple &&
							<RadioButtonGroup
								name={question.title}
								onChange={(e, value)=>{this.handleRadioChange(value)}}>

								{question.answers.map((key, index) => {
									let answer = question.answers[key];

									return (
										<RadioButton
											key={index}
											label={answer}
											value={answer}
											style={styles.option}
										/>
									)
								})}

							</RadioButtonGroup>
						}

						{/* Multiple Answers [Checkbox] */}
						{
							question.isMultiple &&
							question.answers.map((key, index) => {
								let answer = question.answers[key];

								return (
									<Checkbox
										label={answer}
										onCheck={(e, value)=>{this.handleCheckChange(value)}}
										style={styles.option}
									/>
								)
							})
						}
					</div>
					{/* <QuestionFooter sessionUuid={sessionUuid} question={question} /> */}
				</div>
			);
		}

		return <div />;
	}
}

function mapStateToProps(state, props) {
	let sessionUuid = props.match.params.sessionUuid;

	if(sessionUuid === undefined) {
		return {};
	}

	const question = CourseManager.getQuestion(
		state.content.sessions[sessionUuid],
		props.match.params.questionUuid
    );

    return {
		question,
		sessionUuid: props.match.params.sessionUuid
    };
}

export default connect(mapStateToProps)(QuestionDetailScreen);
