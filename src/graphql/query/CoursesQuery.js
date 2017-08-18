import gql from 'graphql-tag';

export default gql`
  {
    courses {
      uuid
      title
      teacherName
      description
      folders {
        uuid
        title
        updatedAt
        sessions {
          uuid
          title
          content
          updatedAt
        }
      }
    }
    user {
      uuid
      firstName
      lastName
      country
      phoneNumber
      locale
    }
  }
`;
