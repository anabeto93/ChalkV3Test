import gql from 'graphql-tag';

export default gql`
  query {
    hasUpdates {
      hasUpdates
      size
    }
  }
`;
