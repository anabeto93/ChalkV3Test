import GraphqlClient from "../graphql/client/GraphqlClient";

import CoursesQuery from "../graphql/query/CoursesQuery";
import HasUpdatesQuery from "../graphql/query/HasUpdatesQuery";

// LOGIN
export const LOGIN_SUCCESS = '@@CHALKBOARDEDUCATION/LOGIN_SUCCESS';

export function login() {
  return { type: LOGIN_SUCCESS };
}

// GET COURSES
export const REQUEST_COURSES_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/REQUEST_COURSES_INFORMATIONS';

export const RECEIVE_COURSES_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/RECEIVE_COURSES_INFORMATIONS';

export const FAIL_GET_COURSES_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/FAIL_GET_COURSES_INFORMATIONS';

export const RECEIVE_USER_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/RECEIVE_USER_INFORMATIONS';

export function requestCoursesInformations() {
  return { type: REQUEST_COURSES_INFORMATIONS };
}

export function receiveCoursesInformations(courses) {
  return { type: RECEIVE_COURSES_INFORMATIONS, payload: { courses } };
}

export function failGetCoursesInformations(message) {
  return { type: FAIL_GET_COURSES_INFORMATIONS, payload: { message } };
}

export function receiveUserInformations(user) {
  return { type: RECEIVE_USER_INFORMATIONS, payload: { user } };
}

export function getCoursesInformations() {
  return function (dispatch) {
    dispatch(requestCoursesInformations());

    GraphqlClient.query({ query: CoursesQuery, fetchPolicy: 'network-only' })
      .then(response => {
        dispatch(receiveCoursesInformations(response.data.courses));
        dispatch(receiveUserInformations(response.data.user));
      })
      .catch(error => {
        dispatch(failGetCoursesInformations('Bad response from server'));
      });
  };
}

// GET UPDATES

export const REQUEST_UPDATES = '@@CHALKBOARDEDUCATION/REQUEST_UPDATES';

export const RECEIVE_UPDATES = '@@CHALKBOARDEDUCATION/RECEIVE_UPDATES';

export const FAIL_GET_UPDATES = '@@CHALKBOARDEDUCATION/FAIL_GET_UPDATES';

export const REINIT_UPDATES = '@@CHALKBOARDEDUCATION/REINIT_UPDATES';

export function requestUpdates() {
  return { type: REQUEST_UPDATES };
}

export function receiveUpdates(updates) {
  return { type: RECEIVE_UPDATES, payload: { updates } };
}

export function failGetUpdates(message) {
  return { type: FAIL_GET_UPDATES, payload: { message } };
}

export function reinitUpdates() {
  return { type: REINIT_UPDATES };
}

export function getUpdates() {
  return function (dispatch) {
    dispatch(requestUpdates());

    GraphqlClient.query({ query: HasUpdatesQuery, fetchPolicy: 'network-only' })
      .then(response => {
        dispatch(receiveUpdates(response.data.hasUpdates));
      })
      .catch(error => {
        dispatch(failGetUpdates('Bad response from server'));
      });
  };
}
