<template>
  <div id="seed-generate">
    <div
      id="seed-details"
      class="card border-success"
      v-if="gameLoaded"
    >
      <div class="card-header bg-success text-white card-heading-btn">
        <h3 class="card-title text-white float-left">{{ $t('multiworld.info.title') }}</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md mb-3">
            <vt-multi-info :multi="multi"></vt-multi-info>
          </div>
          <div class="col-md mb-3">
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="btn-group btn-flex" role="group" v-if="this.multi">
                  <button
                    class="btn btn-light border-secondary text-center"
                    @click="saveSpoiler"
                  >{{ $t('randomizer.details.save_spoiler') }}</button>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="btn-group btn-flex" role="group" v-if="this.multi">
                  <button
                    class="btn btn-success text-center"
                    @click="saveMultidata"
                  >{{ $t('multiworld.save') }}</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- <vt-spoiler v-model="show_spoiler" :multi="multi"></vt-spoiler> -->
      </div>
    </div>
  </div>
</template>

<script>
import Multiworld from "../multi";
import FileSaver from "file-saver";
import axios from "axios";
import { mapState } from "vuex";

export default {
  props: {
    hash: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      gameLoaded: false,
      multi: null,
    };
  },
  created() {
    axios.post(`/multi/` + this.hash).then(response => {
      this.multi = new Multiworld(response.data);
      this.gameLoaded = true;
    });
  },
  methods: {
    saveSpoiler() {
      return FileSaver.saveAs(
        new Blob([JSON.stringify(this.multi.spoiler, null, 4)]),
        this.multi.downloadFilename() + ".txt"
      );
    },
    saveMultidata() {
      return this.multi.save(this.multi.downloadFilename() + "_multidata");
    },
  },
};
</script>

<style scoped>
.card-body {
  padding: 0.5rem;
}
</style>
