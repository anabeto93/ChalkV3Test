import { expect } from 'chai';

import UnBlockSession from './UnBlockSession';

it('should return correct Session Uuid and User Uuid', () => {
  const appPrivateKey =
    'i5Rj10bMdZGeK9fWg6qhQkmACn8YrPpBstuv2DwNXVxayz7EFUcH3JLS4T';

  const code = UnBlockSession.getUnlockCodeForSession(
    'd1fae8c298-adb2e-e878b',
    'a6342f0bf7-a2aae-5a485'
  );

  const userUuid = UnBlockSession.getUserUuidFromCode(code, appPrivateKey);
  const sessionUuid = UnBlockSession.getSessionUuidFromCode(
    code,
    appPrivateKey
  );

  expect(userUuid).to.equal('d1fae8c298-adb2e-e878b');
  expect(sessionUuid).to.equal('a6342f0bf7-a2aae-5a485');
});
