import gql from 'graphql-tag';

export default gql`
  query {
    courses {
      uuid
      title
      description
      teacherName
      createdAt
    }
  }
`;
