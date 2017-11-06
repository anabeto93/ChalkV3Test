export const QUESTIONS_MOCK = {
  0: {
    title: 'Is my first question right or wrong?',
    createdAt: '2017-07-19 07:00:00',
    updatedAt: '2017-07-19 07:00:00',
    isMultiple: false,
    answers: {
      0: {
        title: 'Right'
      },
      1: {
        title: 'Wrong'
      }
    }
  },
  1: {
    title:
      'This is my second question with a very very very very very very very very very very long sentence? There are multiple correct answers.',
    createdAt: '2017-07-19 07:00:00',
    updatedAt: '2017-07-19 07:00:00',
    isMultiple: true,
    answers: {
      0: {
        title:
          'Answer with a very very very very very very very very very very very very very long sentence'
      },
      1: {
        title: 'Small answer'
      },
      2: {
        title: 'Another answer'
      },
      3: {
        title: 'Last answer'
      }
    }
  },
  2: {
    title: 'My last question?',
    createdAt: '2017-07-19 07:00:00',
    updatedAt: '2017-07-19 07:00:00',
    isMultiple: false,
    answers: {
      0: {
        title: 'OK'
      },
      1: {
        title: 'Not ok with that'
      },
      2: {
        title:
          'Answer with a very very very very very very very very very very very very very long sentence'
      }
    }
  }
};
