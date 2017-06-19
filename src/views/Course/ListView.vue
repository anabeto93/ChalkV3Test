<template>
  <div>
    <header class="mdc-toolbar">
      <div class="mdc-toolbar__row">
        <section class="mdc-toolbar__section mdc-toolbar__section--align-start">
          <!-- <a href="#"><i class="material-icons">keyboard_arrow_left</i></a> -->
        </section>
        <section class="mdc-toolbar__section mdc-toolbar__section--align-center">
          <span class="mdc-toolbar__title">
            <img src="./../../assets/logo.png" alt="CHALKBOARD EDUCATION" class="logo">
            CHALKBOARD EDUCATION
          </span>
        </section>
        <section class="mdc-toolbar__section mdc-toolbar__section--align-end">
          <i class="material-icons">account_circle</i>
        </section>
      </div>
    </header>

    <main>
      <div class="mdc-card user">
        <section class="mdc-card__primary">
          <h1 class="mdc-card__title mdc-card__title--large">Firstname LASTNAME</h1>
          <p class="mdc-card__subtitle">University of Ghana</p>
          <p class="mdc-card__subtitle"><i class="material-icons md-16">phone_android</i> +555 555 555 555</p>
        </section>
      </div>

      <ul class="courses-list">
        <li v-for="course in this.$data.courses">
          <a href="#" v-ripple>
            <span class="title">
              {{ course.title }}
              <span class="teachers">{{ course.teachers }}</span>
            </span>
            <i class="material-icons arrow" title="More info">keyboard_arrow_right</i>
          </a>
        </li>
      </ul>
    </main>
  </div>
</template>

<script>
import gql from 'graphql-tag';
import Ripple from '@/directives/mdc/Ripple';

const coursesQuery = gql`
  query allCourses {
    course {
      id
      title
      teachers
      image
      sessions
    }
  }
`;

export default {
  data: () => ({
    courses: [],
  }),
  apollo: {
    courses: {
      query: coursesQuery,
    },
  },
  directives: {
    Ripple,
  },
};
</script>

<style lang="scss">
@import "@material/card/mdc-card";
@import '@material/ripple/mdc-ripple';

.mdc-card.user { margin: 16px; }
.material-icons.md-16 { font-size: 16px; }

.courses-list {
  list-style-type: none;
  margin: 0;
  padding: 0;

  li {
    border-bottom: 1px solid #ccc;

    a {
      text-decoration: none;
      color: #000;
      display: -webkit-flex;
      display: flex;
      -webkit-flex-direction: row;
      -ms-flex-direction: row;
      flex-direction: row;
      -webkit-flex-wrap: nowrap;
      -ms-flex-wrap: nowrap;
      flex-wrap: nowrap;
      -webkit-justify-content: center;
      -ms-flex-pack: center;
      justify-content: center;
      -webkit-align-content: stretch;
      -ms-flex-line-pack: stretch;
      align-content: stretch;
      -webkit-align-items: center;
      -ms-flex-align: center;
      align-items: center;
      padding: 16px;
    }

    .title {
      flex: 10;
      -webkit-box-flex: 10;
      -ms-flex: 10;

      .teachers {
        color: #555;
        display: block;
        font-size: 85%;
      }
    }

    .arrow {
      flex: 1;
      -webkit-box-flex: 1;
      -ms-flex: 1;
      font-size: 36px;
      color: #555;
      text-align: right;
    }
  }
}

.mdc-toolbar__section--align-center {
  flex: 10;
  -webkit-box-flex: 10;
  -ms-flex: 10;
}

.mdc-toolbar__title {
  font-size: 90%;
}

.logo {
  height: 24px;
  float: left;
  margin-right: 10px;
}
</style>
