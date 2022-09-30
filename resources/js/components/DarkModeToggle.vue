<template>
  <div>
    <label @click="onClickLabel">
      Dark Mode:
    </label>
    <toggle-button
      ref="toggle"
      :value="darkMode"
      @input="onInput"
      :sync="true"
      :width="70"
      :height="35"
      :labels="{checked: 'On', unchecked: 'Off'}"
      color="gray"
    ></toggle-button>
  </div>
</template>

<script>
import { ToggleButton } from "vue-js-toggle-button";
import localforage from "localforage";

export default {
  components: {
    ToggleButton
  },
  data() {
    return {
      darkMode: false,
    };
  },
  async created() {
    const value = await localforage.getItem('darkMode');
    this.setMode(value);
  },
  methods: {
    onInput(input) {
      this.setMode(input);
    },
    onClickLabel() {
      this.setMode(!this.darkMode);
    },
    setMode(darkMode) {
      if (darkMode) {
        this.darkMode = true;
        $('body').removeClass('bootstrap');
        $('body').addClass('bootstrap-dark');
      } else {
        this.darkMode = false;
        $('body').removeClass('bootstrap-dark');
        $('body').addClass('bootstrap');
      }
      localforage.setItem('darkMode', this.darkMode);
    }
  }
};
</script>

<style scoped>
.vue-js-switch {
  margin: 2px;
}
.vue-js-switch {
  font-size: 16px !important;
}
.icon {
  vertical-align: middle;
}
.has-tooltip {
  cursor: help;
}
.disabled {
  color: #bcc3c9;
}
</style>
