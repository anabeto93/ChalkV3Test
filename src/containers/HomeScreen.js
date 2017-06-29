import _ from 'lodash'
import React, { Component } from 'react'
import { gql, graphql } from 'react-apollo';
import { Link } from 'react-router-dom'

export class HomeScreen extends Component {
  shouldComponentUpdate(nextProps) {
    if (!this.props.data || !this.props.data.feed) {
      return true;
    }

    if (!_.isEqual(this.props.data.feed, nextProps.data.feed)) {
      console.log('Please render HomeScreen')
      return true;
    }

    console.log('No render HomeScreen')

    return false;
  }

  render () {
    const { data } = this.props

    console.log('rendering HomeScreen')

    return (
      <div>
        <h1>Welcome!</h1>
        <p>This is init!!!</p>
        <Link to='/course'>Course</Link>

        <ul>
        { undefined !== data && undefined !== data.feed && data.feed.map((item) => {
            const name = `${item.repository.owner.login}/${item.repository.name}`

            return (
              <li key={name}>
                <h1>{name}</h1>
                <p>{`Posted by ${item.postedBy.login}`}</p>
                <p>{`☆ ${item.repository.stargazers_count}`}</p>
              </li>
            )
          }
        ) }
        </ul>
      </div>
    )
  }
}

const HomeScreenWithData = graphql(gql`{
  feed (type: TOP, limit: 10) {
    repository {
      name, owner { login }
      stargazers_count
    }
    postedBy { login }
  }
}`)(HomeScreen);

export default HomeScreenWithData
