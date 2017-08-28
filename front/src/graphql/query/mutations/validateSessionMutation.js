import gql from 'graphql-tag';

export default gql `
  mutation Validate($sessionUuid: String!) {
    validateSessionMutation(uuid: $sessionUuid)
  }
`
