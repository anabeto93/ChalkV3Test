module.exports = {
  'default courses list tests': function test(browser) {

    browser
      .url('/courses/list')
      .waitForElementVisible('#app', 5000)
      .assert.elementPresent('.mdc-card')
      .assert.containsText('h1', 'Firstname LASTNAME')
      .assert.elementCount('img', 1)
      .end();
  },
};
