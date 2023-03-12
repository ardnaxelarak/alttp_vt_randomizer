<template>
  <div class="row">
    <div class="col">
      <label class="btn btn-outline-primary btn-file">
        {{ $t("mystery.generate.yaml_select") }}
        <input
          type="file"
          accept=".yaml,.yml"
          @change="loadBlob"
        />
      </label>
    </div>
    <div class="col" v-if="fileSelected">{{ fileNameText }}</div>
  </div>
</template>

<script>
const yaml = require('js-yaml');

export default {
  props: {
    storageKey: { default: null }
  },
  data() {
    return {
      fileSelected: false,
      fileNameText: "",
      fileContents: "",
      file: null
    };
  },
  created() {
    this.$emit('mystery-data', null);
    localforage.getItem('mystery.mystery-blob').then(value => {
      if (value === null) return;
      this.file = value;
      this.loadFile()
    });
  },
  methods: {
    loadBlob(change) {
      let blob = change.target.files[0];
      this.file = blob;
      this.loadFile();
    },
    loadFile() {
      const fileName = this.file.name;
      const fileReader = new FileReader();

      fileReader.onload = event => {
        this.fileContents = event.target.result;
      };

      fileReader.onloadend = function() {
        this.fileSelected = true;

        if (typeof this.fileContents === "undefined") {
          this.fileNameText = "Could not read YAML file: " + fileName;
          throw new Error("Could not read this.fileContents");
        }

        const data = yaml.load(this.fileContents);

        if (!data.base_settings) {
          this.fileNameText = `YAML should have root node of base_settings (${fileName})`;
          return;
        }

        const unexpectedKeys = Object.keys(data).filter(key => !['base_settings', 'modifiers'].includes(key));
        if (unexpectedKeys.length > 0) {
          this.fileNameText = `YAML has unexpected root nodes: ${unexpectedKeys.join(', ')} (${fileName})`;
          return;
        }

        // validate the file or something I guess

        localforage
          .setItem(this.storageKey, data)
          .then(function(data) {
            this.$emit('mystery-data', data);
            this.fileNameText = "Loaded: " + fileName;
          }.bind(this))
          .catch(function() {
            this.$emit('mystery-data', null);
            this.fileNameText = "Could not save mystery data to local storage: " + fileName;
          }.bind(this));
      }.bind(this);

      fileReader.readAsText(this.file);
      localforage.setItem('mystery.mystery-blob', this.file);
    }
  }
};
</script>
