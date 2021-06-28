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
    currentRomInfos: { type: Object, default: null},
  },
  data() {
    return {
      current_rom_infos: {},
      loading: true,
      settings_loaded: false
    };
  },
  created() {
    if (this.currentRomInfos !== null) {
      this.current_rom_infos = this.currentRomInfos;
      this.settings_loaded = true;
      return;
    }
    axios.get(`/base_rom/settings`).then(response => {
      this.current_rom_infos = response.data;
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
    loadBlob(change, branch) {
      // this function doesn't let us test the way it is written
      if (!this.settings_loaded) {
        return setTimeout(this.loadBlob, 50, change);
      }
      this.loading = true;
      let blob = change.target.files[0];

      var branches = [branch];
      if (branch == null) {
        branches = Object.keys(this.current_rom_infos);
      }

      for (const current_branch of branches) {
        new ROM(blob, rom => {
          this.patchRomFromBPS(rom, current_branch)
            .then(rom => {
              if (rom.checkMD5() !== this.current_rom_infos[current_branch].rom_hash) {
                this.$emit("error", this.$i18n.t("error.bad_file"));
                this.loading = false;

                return;
              } else {
                localforage
                  .setItem("rom", rom.getOriginalArrayBuffer())
                  .catch(this.handleXHRError);

                this.$emit("update", rom, this.current_rom_infos[current_branch].rom_hash);
                EventBus.$emit("applyHash", rom);
                this.loading = false;
              }
            })
            .catch(error => {
              if (error == "base patch corrupt") {
                localforage.setItem(`vt.stored_base_${current_branch}`).catch(this.handleXHRError);

                this.loadBlob(change, current_branch);
              }
            });
        });
      }
    },
    handleXHRError(error) {
      if (error === "QuotaExceededError") {
        this.$emit("error", this.$i18n.t("error.quota_exceeded_error"));
        this.loading = false;

        return;
      }
      throw error;
    },
    patchRomFromBPS(rom, branch) {
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
          if (this.current_rom_infos[branch].base_file == stored_base_file) {
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
              .get(this.current_rom_infos[branch].base_file, {
                responseType: "arraybuffer"
              })
              .then(response => {
                localforage
                  .setItem(`vt.stored_base_${branch}`, this.current_rom_infos[branch].base_file)
                  .then(() => {
                    localforage
                      .setItem(`vt.base_bps_${branch}`, response.data)
                      .then(
                        this.patchRomFromBPS(rom, branch)
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
