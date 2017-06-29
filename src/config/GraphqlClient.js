import { ApolloClient, createNetworkInterface } from 'react-apollo';

const networkInterface = createNetworkInterface({
  uri: 'http://api.githunt.com/graphql' // //api.chalkboardeducation.dev/api/graphql/
});

const GraphqlClient = new ApolloClient({
  networkInterface: networkInterface
});

export default GraphqlClient
