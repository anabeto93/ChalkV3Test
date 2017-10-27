export const QESTIONS_MOCK = {
  'aaaaabbbbb-11111-11111': {
    uuid: 'aaaaabbbbb-11111-11111',
    title: 'Is my first question right or wrong?',
    createdAt: '2017-07-19 07:00:00',
    updatedAt: '2017-07-19 07:00:00',
    isMultiple: false,
    answers: {
      0: {
        uuid: 'cccccbbbbb-11111-11111',
        title: 'Right'
      },
      1: {
        uuid: 'cccccbbbbb-11111-22222',
        title: 'Wrong'
      }
    }
  },
  'aaaaabbbbb-22222-11111': {
    uuid: 'aaaaabbbbb-22222-11111',
    title:
      'This is my second question with a very very very very very very very very very very long sentence? There are multiple correct answers.',
    createdAt: '2017-07-19 07:00:00',
    updatedAt: '2017-07-19 07:00:00',
    isMultiple: true,
    answers: {
      0: {
        uuid: 'dddddbbbbb-22222-11111',
        title:
          'Answer with a very very very very very very very very very very very very very long sentence'
      },
      1: {
        uuid: 'dddddbbbbb-22222-22222',
        title: 'Small answer'
      },
      2: {
        uuid: 'dddddbbbbb-22222-33333',
        title: 'Another answer'
      },
      3: {
        uuid: 'dddddbbbbb-22222-44444',
        title: 'Last answer'
      }
    }
  },
  'aaaaabbbbb-33333-11111': {
    uuid: 'aaaaabbbbb-33333-11111',
    title: 'My last question?',
    createdAt: '2017-07-19 07:00:00',
    updatedAt: '2017-07-19 07:00:00',
    isMultiple: false,
    answers: {
      0: {
        uuid: 'cccccbbbbb-33333-11111',
        title: 'OK'
      },
      1: {
        uuid: 'cccccbbbbb-33333-22222',
        title: 'Not ok with that'
      },
      2: {
        uuid: 'cccccbbbbb-33333-33333',
        title:
          'Answer with a very very very very very very very very very very very very very long sentence'
      }
    }
  }
};
