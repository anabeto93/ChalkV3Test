import gql from 'graphql-tag';

export default gql`
  query user($tokenIssuedAt: String!) {
    user (tokenIssuedAt: $tokenIssuedAt) {
      uuid
      firstName
      lastName
      country
      phoneNumber
      locale
    }
  }
`;
