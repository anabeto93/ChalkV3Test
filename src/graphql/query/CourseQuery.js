import gql from 'graphql-tag';

export default gql `
    query Course($uuid: String!) {
      course(uuid: $uuid) {
        uuid
        title
        teacherName
        description
      }
    }
`;
