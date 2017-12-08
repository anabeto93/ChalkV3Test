import gql from 'graphql-tag';

export default gql`
  mutation AnswerSessionQuiz($answerSessionQuizInput: answerSessionQuizInput!) {
    answerSessionQuiz(input: $answerSessionQuizInput)
  }
`;
