import { expect } from 'chai';

import UnBlockSession from './UnBlockSession';

it('should return correct session uuid', () => {
  expect(
    UnBlockSession.getUnlockCodeForSession('userUuid', 'sessionUuid')
  ).to.equal('sessionUuid');
});
