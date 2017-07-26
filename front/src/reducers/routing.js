import { SET_CURRENT_COURSE } from "../actions/actionCreators";

export default function routing(state = {}, action) {
  switch (action.type) {
    case SET_CURRENT_COURSE:
      return Object.assign({}, state, action.payload);
    default:
      return state;
  }
}
