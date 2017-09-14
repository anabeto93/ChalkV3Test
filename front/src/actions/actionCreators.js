import CoursesQuery from '../graphql/query/CoursesQuery';
import GraphqlClient from '../graphql/client/GraphqlClient';
import HasUpdatesQuery from '../graphql/query/HasUpdatesQuery';
import SessionContentQuery from '../graphql/query/SessionContentQuery';
import validateSessionMutation from '../graphql/query/mutations/validateSessionMutation';

// NETWORK STATUS
export const SET_NETWORK_STATUS = '@@CHALKBOARDEDUCATION/SET_NETWORK_STATUS';

export function setNetworkStatus(isOnline) {
  return { type: SET_NETWORK_STATUS, payload: isOnline };
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

export const FILE_LOADED = '@@CHALKBOARDEDUCATION/FILE_LOADED';

export const SPOOL_TERMINATED = '@@CHALKBOARDEDUCATION/SPOOL_TERMINATED';

export function requestCoursesInformations() {
  return { type: REQUEST_COURSES_INFORMATIONS };
}

export function receiveCoursesInformations({ courses, currentDate }) {
  return {
    type: RECEIVE_COURSES_INFORMATIONS,
    payload: { courses, currentDate }
  };
}

export function failGetCoursesInformations(message) {
  return { type: FAIL_GET_COURSES_INFORMATIONS, payload: { message } };
}

export function receiveUserInformations(user) {
  return { type: RECEIVE_USER_INFORMATIONS, payload: { user } };
}

export function getCoursesInformations() {
  return function(dispatch) {
    dispatch(requestCoursesInformations());

    GraphqlClient.query({ query: CoursesQuery, fetchPolicy: 'network-only' })
      .then(response => {
        dispatch(
          receiveCoursesInformations({
            courses: response.data.courses,
            currentDate: response.data.currentDate
          })
        );
        dispatch(receiveUserInformations(response.data.user));
      })
      .catch(error => {
        dispatch(
          failGetCoursesInformations(`Bad response from server: ${error}`)
        );
      });
  };
}

export function fileLoaded(file) {
  return { type: FILE_LOADED, payload: { file } };
}

export function spoolTerminated() {
  return { type: SPOOL_TERMINATED };
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

export function getUpdates(updatedAt) {
  return function(dispatch) {
    dispatch(requestUpdates());

    GraphqlClient.query({
      query: HasUpdatesQuery,
      fetchPolicy: 'network-only',
      variables: { dateLastUpdate: updatedAt }
    })
      .then(response => {
        dispatch(receiveUpdates(response.data.hasUpdates));
      })
      .catch(error => {
        dispatch(failGetUpdates(`Bad response from server: ${error}`));
      });
  };
}

// VALIDATE SESSION

export const REQUEST_VALIDATE_SESSION_INTERNET =
  '@@CHALKBOARDEDUCATION/REQUEST_VALIDATE_SESSION_INTERNET';
export const RECEIVE_VALIDATE_SESSION_INTERNET =
  '@@CHALKBOARDEDUCATION/RECEIVE_VALIDATE_SESSION_INTERNET';
export const FAIL_VALIDATE_SESSION_INTERNET =
  '@@CHALKBOARDEDUCATION/FAIL_VALIDATE_SESSION_INTERNET';

export function requestValidateSessionInternet(sessionUuid) {
  return { type: REQUEST_VALIDATE_SESSION_INTERNET, payload: { sessionUuid } };
}

export function failValidateSessionInternet() {
  return { type: FAIL_VALIDATE_SESSION_INTERNET };
}

export function receiveValidateSessionInternet({ sessionUuid, response }) {
  return {
    type: RECEIVE_VALIDATE_SESSION_INTERNET,
    payload: { sessionUuid, response }
  };
}

export function validateSession(sessionUuid) {
  return dispatch => {
    dispatch(requestValidateSessionInternet(sessionUuid));

    GraphqlClient.mutate({
      mutation: validateSessionMutation,
      variables: { sessionUuid }
    })
      .then(data => {
        dispatch(
          receiveValidateSessionInternet({ sessionUuid, response: data })
        );
      })
      .catch(() => {
        dispatch(failValidateSessionInternet());
      });
  };
}

// USER SETTINGS

export const SETTINGS_SET_LOCALE = '@@CHALKBOARDEDUCATION/SETTINGS/SET_LOCALE';

export function setLocale(locale) {
  return { type: SETTINGS_SET_LOCALE, payload: { locale } };
}

// SESSION CONTENT
export const REQUEST_SESSION_CONTENT =
  '@@CHALKBOARDEDUCATION/REQUEST_SESSION_CONTENT';

export const RECEIVE_SESSION_CONTENT =
  '@@CHALKBOARDEDUCATION/RECEIVE_SESSION_CONTENT';

export const FAIL_GET_SESSION_CONTENT =
  '@@CHALKBOARDEDUCATION/FAIL_GET_SESSION_CONTENT';

export function requestSessionContent() {
  return { type: REQUEST_SESSION_CONTENT };
}

export function receiveSessionContent(sessionContent) {
  return { type: RECEIVE_SESSION_CONTENT, payload: { sessionContent } };
}

export function failGetSessionContent(message) {
  return { type: FAIL_GET_SESSION_CONTENT, payload: { message } };
}

export function getSessionContent(sessionUuid) {
  return function(dispatch) {
    dispatch(requestSessionContent());

    GraphqlClient.query({
      query: SessionContentQuery,
      fetchPolicy: 'network-only',
      variables: { uuid: sessionUuid }
    })
      .then(response => {
        dispatch(
          receiveSessionContent({
            uuid: sessionUuid,
            content: response.data.session.content,
            contentUpdatedAt: response.data.session.contentUpdatedAt
          })
        );
      })
      .catch(error => {
        dispatch(failGetSessionContent(`Bad response from server: ${error}`));
      });
  };
}
