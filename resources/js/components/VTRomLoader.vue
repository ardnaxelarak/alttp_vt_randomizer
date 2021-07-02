<template>
  <div>
    <div v-if="!loading" id="rom-select" class="card border-info">
      <div class="card-header bg-info">
        <h4 class="card-title">{{ $t("rom.loader.title") }}</h4>
      </div>
      <div class="card-body">
        <p>
          <label class="btn btn-outline-primary btn-file">
            {{ $t("rom.loader.file_select") }}
            <input
              type="file"
              accept=".sfc, .smc"
              @change="loadBlob"
            />
          </label>
        </p>
        <p v-html="$t('rom.loader.content')" />
      </div>
    </div>
  </div>
</template>

<script>
import EventBus from "../core/event-bus";
import axios from "axios";
import localforage from "localforage";
import ROM from "../rom";

export default {
  props: {
    currentRomHash: { type: String, default: null},
    overrideBaseBps: { type: String, default: null},
    branch: { type: String, default: null},
  },
  data() {
    return {
      rom_infos: {},
      loading: true,
      settings_loaded: false
    };
  },
  created() {
    if (this.branch != null && this.currentRomHash !== null && this.overrideBaseBps != null) {
      this.rom_infos = {};
      this.rom_infos[this.branch] = {rom_hash: this.currentRomHash, base_file: this.overrideBaseBps};
      this.settings_loaded = true;
      return;
    }
    axios.get(`/base_rom/settings`).then(response => {
      this.rom_infos = response.data;
      this.settings_loaded = true;
    });
  },
  mounted() {
    EventBus.$on("loadBlob", this.loadBlob);
    EventBus.$on("noBlob", this.noBlob);
  },
  methods: {
    noBlob() {
      this.loading = false;
    },
    loadBlob(change) {
      // this function doesn't let us test the way it is written
      if (!this.settings_loaded) {
        return setTimeout(this.loadBlob, 50, change);
      }
      this.loading = true;
      let blob = change.target.files[0];
      let branches = this.branch ? [this.branch] : Object.keys(this.rom_infos);
      this.createBaseRoms(blob, branches);
    },
    createBaseRoms(blob, branches) {
      let infos = [];
      for (const branch of branches) {
        new ROM(blob, rom => {
          this.patchRomFromBPS(branch, rom)
            .then(rom => {
              if (rom.checkMD5() !== this.rom_infos[branch].rom_hash) {
                console.error(`base rom of branch ${branch} failed md5 check`);
                this.$emit("error", this.$i18n.t("error.bad_file"));
                this.loading = false;

                return;
              } else {
                localforage
                  .setItem("rom", rom.getOriginalArrayBuffer())
                  .catch(this.handleXHRError);

                infos[branch] = {rom: rom, hash: this.rom_infos[branch].rom_hash};
                this.romLoaded(infos);
              }
            })
            .catch(error => {
              if (error == "base patch corrupt") {
                localforage.setItem(`vt.stored_base_${branch}`).catch(this.handleXHRError);

                this.createBaseRoms(blob, [branch]);
              } else {
                console.error(error);
              }
            });
        });
      }
    },
    romLoaded(infos) {
      if (!this.branch) {
        for (const branch in this.rom_infos) {
          if (!infos.hasOwnProperty(branch)) {
            // we don't have all the branches loaded yet
            return;
          }
        }
      }
      this.$emit("update", infos);
      if (this.branch) {
        EventBus.$emit("applyHash", infos[this.branch].rom);
      }
      this.loading = false;
    },
    handleXHRError(error) {
      if (error === "QuotaExceededError") {
        this.$emit("error", this.$i18n.t("error.quota_exceeded_error"));
        this.loading = false;

        return;
      }
      throw error;
    },
    patchRomFromBPS(branch, rom) {
      return new Promise((resolve, reject) => {
        if (this.overrideBaseBps != null) {
          axios
            .get(this.overrideBaseBps, {
              responseType: "arraybuffer"
            })
            .then(response => {
              rom
                .parseBaseBPS(response.data)
                .then(rom => {
                  rom.setBaseBPS(response.data);

                  resolve(rom);
                })
                .catch(error => {
                  this.loading = false;
                  this.$emit("error", this.$i18n.t("error.bad_file"));
                  reject(error);
                });
            });
          return;
        }
        localforage.getItem(`vt.stored_base_${branch}`).then(stored_base_file => {
          if (this.rom_infos[branch].base_file == stored_base_file) {
            localforage.getItem(`vt.base_bps_${branch}`).then(patch => {
              rom
                .parseBaseBPS(patch)
                .then(rom => {
                  rom.setBaseBPS(patch);

                  resolve(rom);
                })
                .catch(error => {
                  this.loading = false;
                  this.$emit("error", this.$i18n.t("error.bad_file"));
                  reject(error);
                });
            });
          } else {
            axios
              .get(this.rom_infos[branch].base_file, {
                responseType: "arraybuffer"
              })
              .then(response => {
                localforage
                  .setItem(`vt.stored_base_${branch}`, this.rom_infos[branch].base_file)
                  .then(() => {
                    localforage
                      .setItem(`vt.base_bps_${branch}`, response.data)
                      .then(
                        this.patchRomFromBPS(branch, rom)
                          .then(() => {
                            resolve(rom);
                          })
                          .catch(error => {
                            console.error(error);
                          })
                      )
                      .catch(this.handleXHRError);
                  })
                  .catch(this.handleXHRError);
              });
          }
        });
      });
    }
  }
};
</script>
