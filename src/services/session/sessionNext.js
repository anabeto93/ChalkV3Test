import generateUrl from '../generateUrl';
import CourseManager from '../CourseManager';
import {
  SESSION_DETAIL,
  SESSION_LIST,
  SESSION_SEND,
  QUESTION_DETAIL
} from '../../config/routes';

export default function sessionNext(props) {
  const { courseUuid, session, sessions, history } = props;

  if (session.questions && !session.validated) {
    return history.push(
      generateUrl(QUESTION_DETAIL, {
        ':courseUuid': courseUuid,
        ':sessionUuid': session.uuid,
        ':questionIndex': 0
      })
    );
  }

  if (session.needValidation && !session.validated) {
    return history.push(
      generateUrl(SESSION_SEND, {
        ':courseUuid': courseUuid,
        ':sessionUuid': session.uuid
      })
    );
  }

  const nextSession = CourseManager.getNextSession(sessions, session);

  if (nextSession !== null) {
    return history.push(
      generateUrl(SESSION_DETAIL, {
        ':courseUuid': nextSession.courseUuid,
        ':sessionUuid': nextSession.uuid
      })
    );
  }

  return history.push(
    generateUrl(SESSION_LIST, {
      ':courseUuid': session.courseUuid,
      ':folderUuid': session.folderUuid
    })
  );
}
