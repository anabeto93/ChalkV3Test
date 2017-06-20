<template>
  <router-view></router-view>
</template>

<script>
export default {
  name: 'app',
};
</script>

<style lang="scss">
@import url('https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic');
@import url('https://fonts.googleapis.com/icon?family=Material+Icons');

$mdc-theme-primary: #fc3691;
$mdc-theme-accent: #555;
$mdc-theme-background: #fff;

@import "@material/theme/mdc-theme";
@import "@material/typography/mdc-typography";
@import "@material/toolbar/mdc-toolbar";
@import "@material/list/mdc-list";

body { margin: 0; }
</style>
