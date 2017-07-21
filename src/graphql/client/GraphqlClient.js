import ApolloClient, { createNetworkInterface } from 'apollo-client';
import getConfig from '../../config';

export const networkInterface = createNetworkInterface({
  uri: getConfig().api.endpoint
});

const GraphqlClient = new ApolloClient({ networkInterface });

export default GraphqlClient;
