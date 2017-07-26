import { SET_CURRENT_COURSE, RESET_ROUTING_DATA } from "../actions/actionCreators";

export default function routing(state = {}, action) {
  switch (action.type) {
    case SET_CURRENT_COURSE:
      return Object.assign({}, state, action.payload);
    case RESET_ROUTING_DATA:
      return action.payload;
    default:
      return state;
  }
}
