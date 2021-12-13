<template>
  <div id="seed-generate">
    <div v-if="error" class="alert alert-danger" role="alert">
      <button type="button" class="close" aria-label="Close">
        <img class="icon" src="/i/svg/x.svg" alt="clear" @click="error = false" />
      </button>
      <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
      <span class="sr-only">{{ $t('error.title') }}:</span>
      <span class="message">{{ this.error }}</span>
    </div>

    <div v-if="mw_host" class="alert alert-success" role="alert">
      <button type="button" class="close" aria-label="Close">
        <img class="icon" src="/i/svg/x.svg" alt="clear" @click="mw_host = false" />
      </button>
      <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
      <span class="message">{{ this.mw_host }}</span>
    </div>

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
            <div class="row">
              <div class="col-md-6 mb-3"></div>
              <div class="col-md-6 mb-3">
                <div class="btn-group btn-flex" role="group" v-if="this.multi">
                  <button
                    class="btn btn-primary text-center"
                    @click="hostMultidata"
                  >{{ $t('multiworld.host') }}</button>
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
      error: null,
      mw_host: null,
    };
  },
  created() {
    axios.get(`/multi/` + this.hash).then(response => {
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
    hostMultidata() {
      axios
        .post(
          `/api/mw/host/${this.hash}`,
          {
            responseType: "json"
          }
        )
        .then(response => {
          if (response.data.port && response.data.token) {
            this.mw_host = `Your game is hosted at ws://mw.gwaa.kiwi:${response.data.port} with room token ${response.data.token}`;
            this.error = false;
            window.open(`https://mw.gwaa.kiwi/game/${response.data.token}`, '_blank');
          } else {
            this.mw_host = false;
            this.error = this.$i18n.t("error.failed_host");
          }
        })
        .catch(error => {
          this.mw_host = false;
          this.error = this.$i18n.t("error.failed_host");
        });
    }
  },
};
</script>

<style scoped>
.card-body {
  padding: 0.5rem;
}
</style>
