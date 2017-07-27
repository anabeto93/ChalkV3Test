import GraphqlClient from '../graphql/client/GraphqlClient';
import CoursesQuery from '../graphql/query/CoursesQuery';

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

export function requestCoursesInformations() {
  return { type: REQUEST_COURSES_INFORMATIONS };
}

export function receiveCoursesInformations(courses) {
  return { type: RECEIVE_COURSES_INFORMATIONS, payload: { courses } };
}

export function failGetCoursesInformations(message) {
  return { type: FAIL_GET_COURSES_INFORMATIONS, payload: { message } };
}

export function getCoursesInformations() {
  return function(dispatch) {
    dispatch(requestCoursesInformations());

    GraphqlClient.query({ query: CoursesQuery })
      .then(response => {
        dispatch(receiveCoursesInformations(response.data.courses));
      })
      .catch(error => {
        dispatch(failGetCoursesInformations('Bad response from server'));
      });
  };
}
