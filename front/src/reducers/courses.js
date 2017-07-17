import { GET_COURSES_INFORMATIONS } from '../actions/actionCreators'

export default function courses (state = [], action) {
  switch (action.type) {
    case GET_COURSES_INFORMATIONS: {
      const { courses } = action.payload
      return courses
    }

    default:
      return state
  }
}
