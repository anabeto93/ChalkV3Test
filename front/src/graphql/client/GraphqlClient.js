import ApolloClient, { createNetworkInterface } from 'apollo-client';
import getConfig from '../../config/config';

const GraphqlClient = new ApolloClient({
  networkInterface: createNetworkInterface({
    uri: getConfig().api.endpoint
  })
});

export default GraphqlClient;
