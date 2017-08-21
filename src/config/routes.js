export const HOME = '/';
export const LOGIN = '/login/:token';
export const COURSES = '/courses';
export const FOLDER_LIST = '/courses/:courseId/folders/list';
export const SESSION_LIST =
  '/courses/:courseId/folders/:folderId/sessions/list';
export const SESSION_LIST_WITHOUT_FOLDER = '/courses/:courseId/sessions/list';
export const SESSION_DETAIL = '/courses/:courseId/session/:sessionId';
export const SESSION_SEND = '/courses/:courseId/session/:sessionId/send';
