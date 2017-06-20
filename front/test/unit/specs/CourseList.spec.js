import Vue from 'vue';
import CourseListView from '@/views/Course/ListView';

describe('ListView.vue', () => {
  it('should render correct contents', () => {
    const Constructor = Vue.extend(CourseListView);
    const vm = new Constructor().$mount();
    expect(vm.$el.querySelector('.mdc-card__title').textContent)
      .to.equal('Firstname LASTNAME');
  });
});
