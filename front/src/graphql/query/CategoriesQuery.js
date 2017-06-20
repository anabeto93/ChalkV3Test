import gql from 'graphql-tag';

export default gql `
    query Categories($uuidCourse: String!) {
      categories(uuidCourse: $uuidCourse) {
        id
        uuidCourse
        title
      }
    }
`;
