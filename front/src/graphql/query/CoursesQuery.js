import gql from 'graphql-tag';

export default gql `
    query CourseList {
      courses {
        uuid
        title
        teacherName
        description
      }
    }
`;
