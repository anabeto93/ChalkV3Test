import gql from 'graphql-tag';

export default gql`
  {
    courses {
      uuid
      title
      teacherName
      description
      updatedAt
      folders {
        uuid
        title
        updatedAt
        sessions {
          uuid
          title
          contentUpdatedAt
          updatedAt
          validated
          files {
            url
            size
            createdAt
            updatedAt
          }
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
