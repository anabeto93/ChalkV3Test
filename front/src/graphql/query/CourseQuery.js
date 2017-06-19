import gql from 'graphql-tag';

export default gql `
  query {
    course($uuid: Int!) {
      uuid
      title
      teacherName
      description
    }
  }
`;
