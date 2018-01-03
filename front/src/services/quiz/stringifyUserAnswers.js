export default function stringifyUserAnswers(questions) {
  const answers = questions
    .map((key, index) => {
      return questions[index].userAnswers.join();
    })
    .join(';');

  return answers;
}
