export const HOME = '/';
export const LOGIN = '/login/:token';
export const COURSES = '/courses';
export const ACCOUNT = '/account';
export const FOLDER_LIST = '/courses/:courseUuid/folders/list';
export const SESSION_LIST =
  '/courses/:courseUuid/folders/:folderUuid/sessions/list';
export const SESSION_LIST_WITHOUT_FOLDER = '/courses/:courseUuid/sessions/list';
export const SESSION_DETAIL = '/courses/:courseUuid/session/:sessionUuid';
export const SESSION_SEND = '/courses/:courseUuid/session/:sessionUuid/send';
export const SESSION_VALIDATE_SMS = '/session/validate/sms/:validationCode';
export const SESSION_SEND_SMS =
  '/courses/:courseUuid/session/:sessionUuid/sms/send';
export const QUESTION_UUID =
  '/courses/:courseUuid/session/:sessionUuid/question/:questionUuid';
