import getConfig from '../../config/index';

export class UnBlockSession {
  static getUnlockCodeForSession(userUuid, sessionUuid) {
    const privateKey = getConfig().api.privateKey;

    return sessionUuid;
  }
}

export default UnBlockSession;
