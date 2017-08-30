import gql from 'graphql-tag';

export default gql`
  query hasUpdates($dateLastUpdate: DateTime!) {
    hasUpdates(dateLastUpdate: $dateLastUpdate) {
      hasUpdates
      size
    }
  }
`;
