// Actions Creators for Redux

export const GET_COURSES_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/GET_COURSES_INFORMATIONS';

export function getCoursesInformations() {
  return { type: GET_COURSES_INFORMATIONS, payload: {} };
}

export const LOGIN_SUCCESS = '@@CHALKBOARDEDUCATION/LOGIN_SUCCESS';

export function login() {
  return { type: LOGIN_SUCCESS };
}
