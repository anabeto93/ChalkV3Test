import gql from 'graphql-tag';

export default gql`
  {
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
