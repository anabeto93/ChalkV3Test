<template>
  <div>
    <h1 class="mdc-typography--display1">Courses list</h1>

    <ul class="mdc-list mdc-list--two-line mdc-list--avatar-list two-line-avatar-text-icon-demo">
      <li class="mdc-list-item" v-for="course in this.$data.courses">
        <span class="mdc-list-item__text">
          {{ course.title }}
          <span class="mdc-list-item__text__secondary">{{ course.teachers }}</span>
        </span>
        <i href="#" class="mdc-list-item__end-detail material-icons" title="More info">
          keyboard_arrow_right
        </i>
      </li>
    </ul>
  </div>
</template>

<script>
import data from '@/data/data';

export default {
  data() {
    return {
      courses: data.courses,
    };
  },
};
</script>
