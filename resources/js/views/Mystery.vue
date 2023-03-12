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
    <rom-loader v-if="!romLoaded" @update="updateRom" @error="onError"></rom-loader>
    <div v-if="romLoaded && !gameLoaded && !generating" class="card border-success">
      <div class="card-header bg-success card-heading-btn">
        <h3 class="card-title text-white float-left">{{ $t('mystery.title') }}</h3>
        <div class="btn-toolbar float-right" v-if="gameGenerated">
          <a
            class="btn btn-light text-dark border-secondary"
            role="button"
            @click="gameLoaded = true"
          >
            {{ $t('mystery.generate.forward') }}
            <img class="icon" src="/i/svg/arrow-right.svg" alt />
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="card border-info my-1">
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <vt-mystery-loader
                  id="mystery-loader"
                  storage-key="mystery.mystery-data"
                  @mystery-data="updateMysteryData"
                ></vt-mystery-loader>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-md">
            <div class="btn-group btn-flex" role="group">
              <button
                class="btn btn-primary w-50 text-center"
                v-tooltip="$t('mystery.generate.race_warning')"
                :disabled="generating || disableGenerateButtons"
                name="generate-tournament-rom"
                @click="applyTournamentSeed"
              >{{ $t('mystery.generate.race') }}</button>
            </div>
          </div>
          <div class="col-md">
            <div class="btn-group btn-flex" role="group">
              <button
                class="btn btn-info w-50 text-center"
                name="generate-tournament-rom"
                :disabled="generating || disableGenerateButtons"
                @click="applyTournamentSpoilerSeed"
              >{{ $t('mystery.generate.spoiler_race') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-show="generating" class="center">
      <div class="loading" />
      <h1>{{ $t('mystery.generate.generating') }}</h1>
      <h5>{{ $t('mystery.generate.slow_warning') }}</h5>
    </div>

    <div
      id="seed-details"
      class="card border-success"
      v-if="gameLoaded && romLoaded && !generating"
    >
      <div class="card-header bg-success text-white card-heading-btn">
        <h3 class="card-title text-white float-left">{{ $t('mystery.details.title') }}</h3>
        <div class="btn-toolbar float-right">
          <a
            class="btn btn-light text-dark border-secondary"
            role="button"
            @click="gameLoaded = false"
          >
            {{ $t('mystery.generate.back') }}
            <img class="icon" src="/i/svg/cog.svg" alt />
          </a>
          <a
            class="btn btn-light text-dark border-secondary ml-3"
            role="button"
            v-tooltip="$t('mystery.generate.regenerate_tooltip')"
            @click="applySeed"
          >
            {{ $t('mystery.generate.regenerate') }}
            <img class="icon" src="/i/svg/reload.svg" alt />
          </a>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md mb-3">
            <vt-rom-info :rom="rom"></vt-rom-info>
          </div>
          <div class="col-md mb-3">
            <div class="row">
              <div class="col-md-6 mb-3">
                <div class="btn-group btn-flex" role="group" v-if="this.rom">
                  <button
                    class="btn btn-light border-secondary text-center"
                    @click="saveSpoiler"
                  >{{ $t('mystery.details.save_spoiler') }}</button>
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <div class="btn-group btn-flex" role="group" v-if="this.rom">
                  <button
                    class="btn btn-success text-center"
                    :disabled="disableSaveRomButton"
                    @click="saveRom"
                  >{{ $t('mystery.details.save_rom') }}</button>
                </div>
              </div>
            </div>
            <div class="row">
              <vt-rom-settings
                class="col-12"
                :rom="rom"
                @disallow-save-rom="disallowSaveRom"
              ></vt-rom-settings>
            </div>
          </div>
        </div>
        <vt-spoiler v-model="show_spoiler" :rom="rom"></vt-spoiler>
      </div>
    </div>
  </div>
</template>

<script>
import EventBus from "../core/event-bus";
import FileSaver from "file-saver";
import RomLoader from "../components/VTRomLoader.vue";
import Select from "../components/Select.vue";
import localforage from "localforage";
import axios from "axios";
import { mapMutations, mapActions, mapState } from "vuex";
import { retry } from "@lifeomic/attempt";

export default {
  components: {
    RomLoader,
    Select
  },
  data() {
    return {
      rom: null,
      error: false,
      generating: false,
      generationId: null,
      romLoaded: false,
      rom_infos: {},
      gameLoaded: false,
      gameGenerated: false,
      show_spoiler: false,
      tournament: false,
      disableGenerateButtons: true,
      disableSaveRomButton: false,
      spoilers: "off",
      mysteryData: null,
    };
  },
  created() {
    this.$store.dispatch("getSprites");
    this.$store.dispatch("randomizer/getItemSettings");
    this.$store.dispatch("romSettings/initialize");

    this.rom_infos = {};
    this.rom = null;
    localforage.getItem("rom").then(function(blob) {
      if (blob == null) {
        EventBus.$emit("noBlob");
        return;
      }
      EventBus.$emit("loadBlob", { target: { files: [new Blob([blob])] } });
    });
  },
  methods: {
    updateMysteryData(data) {
      this.mysteryData = data;
      this.disableGenerateButtons = !Boolean(data);
    },
    disallowSaveRom(e) {
      this.disableSaveRomButton = Boolean(e);
    },
    applyTournamentSeed() {
      this.tournament = true;
      this.spoilers = "off";
      this.applySeed();
    },
    applyTournamentSpoilerSeed() {
      this.tournament = false;
      this.spoilers = "on";
      this.applySeed();
    },
    applySeed(e, second_attempt) {
      this.error = false;
      this.generating = true;
      return new Promise(
        function(resolve, reject) {
          this.gameLoaded = false;
          axios
            .post(`/api/mystery`, {
              weights: this.mysteryData,
              tournament: this.tournament,
            })
            .then(generationResponse => {
              if (generationResponse.data.seed_generation_id) {
                this.generationId = generationResponse.data.seed_generation_id;

                retry(
                  this.checkGeneration.bind(this),
                  {
                    delay: 300,
                    maxDelay: 5000,
                    factor: 1.5,
                    maxAttempts: 0,
                    jitter: true,
                    minDelay: 300,
                    handleError (err, context, options) {
                      if (err.message != "not ready yet") {
                        context.abort();
                      }
                    },
                  }
                )
                  .then(response => {
                    let prom;
                    let branch = response.data.branch;
                    if (!this.rom_infos[branch]) {
                      console.error(`No info on branch ${branch}`);
                      // TODO: should probably give a better error message
                      this.error = this.$i18n.t("error.failed_generation");
                      reject(this.error);
                    }
                    let rom = this.rom_infos[branch].rom;
                    let hash = this.rom_infos[branch].hash;
                    if (rom.checkMD5() != hash) {
                      prom = rom.reset().then(() => {
                        if (rom.checkMD5() != hash) {
                          return new Promise((resolve, reject) => {
                            reject(rom);
                          });
                        }
                        return rom.parsePatch(response.data);
                      });
                    } else {
                      prom = rom.parsePatch(response.data);
                    }
                    prom.then(
                      function(rom) {
                        this.rom = rom;
                        if (response.data.current_rom_hash && response.data.current_rom_hash != hash) {
                          // The base ROM has been updated.
                          window.location.assign(`/h/${this.rom.hash}`);
                        }
                        if (this.rom.shuffle || this.rom.spoilers == "mystery" || this.rom.allow_quickswap) {
                          this.rom.allowQuickSwap = true;
                        }
                        this.gameLoaded = true;
                        this.gameGenerated = true;
                        this.generating = false;
                        EventBus.$emit("gameLoaded", rom);
                        resolve({ rom: this.rom, patch: response.data.patch });
                      }.bind(this)
                    );
                  })
                  .catch(error => {
                    this.error = this.$i18n.t("error.failed_generation");
                    this.generating = false;
                    reject(error);
                  });
              } else {
                this.error = this.$i18n.t("error.failed_generation");
                this.generating = false;
              }
            })
            .catch(error => {
              if (error.response) {
                switch (error.response.status) {
                  case 429:
                    this.error = this.$i18n.t("error.429");
                    break;
                  default:
                    this.error = this.$i18n.t("error.failed_generation");
                }
              }

              this.generating = false;
              reject(error);
            });
        }.bind(this)
      );
    },
    async checkGeneration() {
      const response =
        await axios.get(
          `/api/generation/seed/${this.generationId}`,
          { responseType: "json" });

      if (response.data.status === "waiting") {
        throw new Error("not ready yet");
      } else if (response.data.seed_hash) {
        return await axios.get(`/hash/${response.data.seed_hash}`);
      } else {
        throw new Error("generation failed");
      }
    },
    saveRom() {
      // track the sprite choice for usage statistics
      localforage.getItem("rom.sprite-gfx").then(value => {
        ga("event", "save", {
          dimension1: value
        });
      });
      return this.rom.save(this.rom.downloadFilename() + ".sfc", {
        quickswap: this.quickswap,
        paletteShuffle: this.paletteShuffle,
        musicOn: this.musicOn,
        msu1Resume: this.msu1Resume,
        reduceFlashing: this.reduceFlashing,
        shuffleSfx: this.shuffleSfx,
        fakeBoots: this.fakeBoots,
        icePhysics: this.icePhysics,
      });
    },
    saveSpoiler() {
      return FileSaver.saveAs(
        new Blob([JSON.stringify(this.rom.spoiler, null, 4)]),
        this.rom.downloadFilename() + ".txt"
      );
    },
    updateRom(rom_infos) {
      this.rom_infos = rom_infos;
      this.error = false;
      this.romLoaded = true;
    },
    onError(error) {
      this.error = error;
    }
  },
  computed: {
    ...mapState("romSettings", {
      heartSpeed: state => state.heartSpeed,
      menuSpeed: state => state.menuSpeed,
      heartColor: state => state.heartColor,
      quickswap: state => state.quickswap,
      musicOn: state => state.musicOn,
      msu1Resume: state => state.msu1Resume,
      paletteShuffle: state => state.paletteShuffle,
      reduceFlashing: state => state.reduceFlashing,
      shuffleSfx: state => state.shuffleSfx,
      fakeBoots: state => state.fakeBoots,
      icePhysics: state => state.icePhysics,
    }),
  }
};
</script>

