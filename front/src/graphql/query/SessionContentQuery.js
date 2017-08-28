import gql from 'graphql-tag';

export default gql`
  query session($uuid: String!) {
    session(uuid: $uuid) {
      content
      contentUpdatedAt
    }
  }
`;
