import { reinitUpdates } from '../actions/actionCreators';
import store from '../store/store';

function select(state) {
  return state.courses.isFetching;
}

let currentValue;

export default function CoursesIsFetchingSubscriber() {
  let previousValue = currentValue;
  currentValue = select(store.getState());

  if (previousValue && !currentValue) {
    // if courses isFetching changed from true to false, reinit updates
    store.dispatch(reinitUpdates());
  }
}
