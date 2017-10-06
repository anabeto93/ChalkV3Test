import getConfig from '../../config/index';

export class UnBlockSession {
  static getHexString() {
    return '0123456789abcdef';
  }

  static getUnlockCodeForSession(userUuid, sessionUuid) {
    return this.getCodeFromUuid(userUuid) + this.getCodeFromUuid(sessionUuid);
  }

  static getCodeFromUuid(uuid) {
    const privateKey = getConfig().privateKey;
    const hexString = this.getHexString();

    const remainingLength = privateKey.length - hexString.length - 1;
    const begin = Math.floor(Math.random() * remainingLength);
    const firstChar = privateKey.charAt(begin);

    let chars = uuid.split('');

    // remove '-'
    chars = chars.filter(char => char !== '-');

    // replace hex by equivalent int
    chars = chars.map(char => hexString.indexOf(char));

    return (
      firstChar +
      chars.map(char => privateKey.charAt(begin + char + 1)).join('')
    );
  }

  static getUuidFromCode(code) {
    const privateKey = getConfig().privateKey;
    const hexString = this.getHexString();

    let chars = code.split('');
    const firstChar = chars[0];
    const begin = privateKey.indexOf(firstChar);

    chars.shift();
    chars = chars.map(char => privateKey.indexOf(char) - begin - 1);

    const result = chars.map(char => hexString.charAt(char)).join('');

    // set '-' separator
    return (
      result.substr(0, 10) +
      '-' +
      result.substr(10, 5) +
      '-' +
      result.substr(15, 5)
    );
  }

  static getSessionUuidFromCode(code) {
    return this.getUuidFromCode(code.substr(code.length / 2, code.length));
  }

  static getUserUuidFromCode(code) {
    return this.getUuidFromCode(code.substr(0, code.length / 2));
  }
}

export default UnBlockSession;
