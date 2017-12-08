import CoursesQuery from '../graphql/query/CoursesQuery';
import GraphqlClient from '../graphql/client/GraphqlClient';
import HasUpdatesQuery from '../graphql/query/HasUpdatesQuery';
import SessionContentQuery from '../graphql/query/SessionContentQuery';
import validateSessionMutation from '../graphql/query/mutations/validateSessionMutation';
import answerSessionQuizMutation from '../graphql/query/mutations/answerSessionQuizMutation';
import UserQuery from '../graphql/query/UserQuery';

// NETWORK STATUS
export const SET_NETWORK_STATUS = '@@CHALKBOARDEDUCATION/SET_NETWORK_STATUS';

export function setNetworkStatus(isOnline) {
  return { type: SET_NETWORK_STATUS, payload: isOnline };
}

// USER
export const REQUEST_USER_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/REQUEST_USER_INFORMATIONS';

export const RECEIVE_USER_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/RECEIVE_USER_INFORMATIONS';

export const FAIL_GET_USER_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/FAIL_GET_USER_INFORMATIONS';

export function requestUserInformations(token) {
  return { type: REQUEST_USER_INFORMATIONS, payload: { token } };
}

export function receiveUserInformations(user) {
  return { type: RECEIVE_USER_INFORMATIONS, payload: user };
}

export function failGetUserInformations(message) {
  return { type: FAIL_GET_USER_INFORMATIONS, payload: { message } };
}

export function getUserInformations(token) {
  return function(dispatch) {
    dispatch(requestUserInformations(token));

    GraphqlClient.query({ query: UserQuery, fetchPolicy: 'network-only' })
      .then(response => {
        dispatch(receiveUserInformations(response.data.user));
      })
      .catch(error => {
        dispatch(failGetUserInformations(`Bad response from server: ${error}`));
      });
  };
}

// GET COURSES
export const REQUEST_COURSES_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/REQUEST_COURSES_INFORMATIONS';

export const RECEIVE_COURSES_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/RECEIVE_COURSES_INFORMATIONS';

export const FAIL_GET_COURSES_INFORMATIONS =
  '@@CHALKBOARDEDUCATION/FAIL_GET_COURSES_INFORMATIONS';

export const REINIT_CONTENT_STATES =
  '@@CHALKBOARDEDUCATION/REINIT_CONTENT_STATES';

export function reInitContentStates() {
  return { type: REINIT_CONTENT_STATES };
}

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
        dispatch(failGetCoursesInformations(error));
      });
  };
}

// FILE
export const FILE_LOADED = '@@CHALKBOARDEDUCATION/FILE_LOADED';

export const SPOOL_TERMINATED = '@@CHALKBOARDEDUCATION/SPOOL_TERMINATED';

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
export const RECEIVE_VALIDATE_SESSION_SMS =
  '@@CHALKBOARDEDUCATION/RECEIVE_VALIDATE_SESSION_SMS';
export const DONE_VALIDATE_SESSION =
  '@@CHALKBOARDEDUCATION/DONE_VALIDATE_SESSION';

export function requestValidateSessionInternet(sessionUuid) {
  return { type: REQUEST_VALIDATE_SESSION_INTERNET, payload: { sessionUuid } };
}

export function failValidateSessionInternet() {
  return { type: FAIL_VALIDATE_SESSION_INTERNET };
}

export function doneValidateSession() {
  return { type: DONE_VALIDATE_SESSION };
}

export function receiveValidateSessionInternet({ sessionUuid, response }) {
  return {
    type: RECEIVE_VALIDATE_SESSION_INTERNET,
    payload: { sessionUuid, response }
  };
}

export function receiveValidateSessionSMS(sessionUuid) {
  return {
    type: RECEIVE_VALIDATE_SESSION_SMS,
    payload: { sessionUuid }
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

// QUIZ
export const SET_USER_ANSWERS = '@@CHALKBOARDEDUCATION/SET_USER_ANSWERS';

export function setUserAnswers({ sessionUuid, questionIndex, answerIndex }) {
  return {
    type: SET_USER_ANSWERS,
    payload: { sessionUuid, questionIndex, answerIndex }
  };
}

export function answerSessionQuiz({ sessionUuid, answers }) {
  return dispatch => {
    dispatch(requestValidateSessionInternet(sessionUuid));

    GraphqlClient.mutate({
      mutation: answerSessionQuizMutation,
      variables: {
        answerSessionQuizInput: {
          uuid: sessionUuid,
          answers: answers
        }
      }
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
