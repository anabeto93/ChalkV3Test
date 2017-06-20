<template>
    <div>
        <header class="mdc-toolbar">
            <div class="mdc-toolbar__row">
                <section class="mdc-toolbar__section mdc-toolbar__section--align-start">
                    <!--  -->
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
            <ul class="courses-list">
                <li>

                </li>
            </ul>
        </main>
    </div>
</template>

<script>
  import Ripple from '@/directives/mdc/Ripple';

  export default {
    mounted() {
      this.$store.dispatch('GET_CATEGORIES', this.uuidCourse);
    },
    computed: {
      categories() {
        return this.$store.state.categories;
      },
      uuidCourse() {
        return this.$route.params.uuid;
      },
    },
    directives: {
      Ripple,
    },
  };
</script>

<style lang="scss">
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
