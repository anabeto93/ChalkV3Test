<template>
  <div>
    <h1 class="mdc-typography--display1">Courses list</h1>

    <ul class="mdc-list mdc-list--two-line mdc-list--avatar-list two-line-avatar-text-icon-demo">
      <li class="mdc-list-item">
        <span class="mdc-list-item__text">
          Vacation Itinerary
          <span class="mdc-list-item__text__secondary">Jan 10, 2014</span>
        </span>
        <i href="#" class="mdc-list-item__end-detail material-icons" title="More info">
          keyboard_arrow_right
        </i>
      </li>

      <li class="mdc-list-item">
        <span class="mdc-list-item__text">
          Kitchen Remodel
          <span class="mdc-list-item__text__secondary">Jan 20, 2014</span>
        </span>
        <i class="mdc-list-item__end-detail material-icons" title="More info">
          keyboard_arrow_right
        </i>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'hello',
  data() {
    return {
      msg: 'Welcome to Your Vue.js PWA',
    };
  },
};
</script>
