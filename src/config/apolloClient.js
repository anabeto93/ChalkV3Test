import { ApolloClient, createNetworkInterface } from 'apollo-client';

export default new ApolloClient({
  networkInterface: createNetworkInterface({
    uri: 'http://api.chalkboardeducation.dev/api/graphql/', // don't forget to set last slash to the uri for prevent redirect
    transportBatching: true,
  }),
  connectDevTools: true,
});
