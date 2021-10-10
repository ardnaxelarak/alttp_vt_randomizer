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
      <span class="glyphicon glyphicon-globe aria-hidden="true""></span>
      <span class="message">{{ this.mw_host }}</span>
    </div>

    <div
      v-if="!gameLoaded && !generating && !$store.state.multiworld.initializing"
      class="card border-success my-1"
    >
      <div class="card-header bg-success card-heading-btn">
        <h3 class="card-title text-white">{{ $t('multiworld.title') }}</h3>
      </div>
      <tabs
        v-show="!$store.state.loading"
        class="think"
        nav-type="tabs"
        :sticky="true"
        :actions="worldList.length < 8 ? [{name:'+ Add World'}] : []"
        @click="onActionClick"
      >
        <tab
          :name="'World ' + world_id"
          v-for="world_id in worldList"
          :key="world_id"
          :selected="selectedWorldId === world_id"
        >
          <div class="card-body">
            <h2>World: {{ world_id }}</h2>
            <div class="card-body">
              <div class="card border-info my-1">
                <div class="card-body">
                  <div class="row">
                    <div class="col col-xl-4 col-lg-5 my-1">
                      <vt-text
                        :sid="world_id"
                        :value="worlds[world_id].name"
                        :placeholder="'Player ' + world_id"
                        :storageKey="'multi.name.' + world_id"
                        @input="setName"
                        maxlength="20"
                      >Name</vt-text>
                    </div>
                    <div class="col col-xl-6 col-lg-7 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].preset"
                        @input="setPreset"
                        :options="optionsPreset"
                      >
                        <template v-slot:default>{{ $t('randomizer.preset.title') }}</template>
                        <template v-slot:appends v-if="worlds[world_id].preset.value !== 'custom'">
                          <button
                            class="btn btn-outline-secondary"
                            type="button"
                            @click="setPreset('custom', world_id)"
                          >{{ $t('randomizer.preset.customize')}}</button>
                        </template>
                      </Select>
                    </div>
                  </div>
                </div>
                <h5 class="card-title p-2 border-bottom">{{ $t('randomizer.placement.title') }}</h5>
                <div class="card-body">
                  <div class="row" v-if="!editable[world_id]">
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.glitches_required.title') }}: {{ $t(worlds[world_id].glitches_required.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.dungeon_items.title') }}: {{ $t(worlds[world_id].dungeon_items.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.accessibility.title') }}: {{ $t(worlds[world_id].accessibility.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.drop_shuffle.title') }}: {{ $t(worlds[world_id].drop_shuffle.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.shopsanity.title') }}: {{ $t(worlds[world_id].shopsanity.name) }}</div>
                  </div>
                  <div class="row" v-if="editable[world_id]">
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].glitches_required"
                        @input="setGlitchesRequired"
                        :options="optionsGlitchesRequired"
                      >{{ $t('randomizer.glitches_required.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].dungeon_items"
                        @input="setDungeonItems"
                        :options="optionsDungeonItems"
                      >{{ $t('randomizer.dungeon_items.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].accessibility"
                        @input="setAccessibility"
                        :options="optionsAccessibility"
                      >{{ $t('randomizer.accessibility.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].drop_shuffle"
                        @input="setDropShuffle"
                        :options="optionsDropShuffle"
                      >{{ $t('randomizer.drop_shuffle.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].shopsanity"
                        @input="setShopsanity"
                        :options="optionsShopsanity"
                      >{{ $t('randomizer.shopsanity.title') }}</Select>
                    </div>
                  </div>
                  <div
                    v-if="worlds[world_id].glitches_required.value !== 'none'"
                    class="logic-warning text-danger"
                    v-html="$t('randomizer.glitches_required.glitch_warning')"
                  />
                </div>
                <h5 class="card-title p-2 border-bottom">{{ $t('randomizer.goal.title') }}</h5>
                <div class="card-body">
                  <div class="row" v-if="!editable[world_id]">
                    <div
                      class="col-xl-6 col-lg-6 my-1"
                    >{{ $t('randomizer.goal.title') }}: {{ $t(worlds[world_id].goal.name) }}</div>
                    <div
                      class="col-xl-6 col-lg-6 my-1"
                    >{{ $t('randomizer.tower_open.title') }}: {{ $t(worlds[world_id].tower_open.name) }}</div>
                    <div
                      class="col-xl-6 col-lg-6 my-1"
                    >{{ $t('randomizer.ganon_open.title') }}: {{ $t(worlds[world_id].ganon_open.name) }}</div>
                    <div
                      class="col-xl-6 col-lg-6 my-1"
                    >{{ $t('randomizer.ganon_item.title') }}: {{ $t(worlds[world_id].ganon_item.name) }}</div>
                  </div>
                  <div class="row" v-if="editable[world_id]">
                    <div class="col-xl-6 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].goal"
                        @input="setGoal"
                        :options="optionsGoal"
                      >{{ $t('randomizer.goal.title') }}</Select>
                    </div>
                    <div class="col-xl-6 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].tower_open"
                        @input="setTowerOpen"
                        :options="optionsTowerOpen"
                      >{{ $t('randomizer.tower_open.title') }}</Select>
                    </div>
                    <div class="col-xl-6 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].ganon_open"
                        @input="setGanonOpen"
                        :options="optionsGanonOpen"
                      >{{ $t('randomizer.ganon_open.title') }}</Select>
                    </div>
                    <div class="col-xl-6 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].ganon_item"
                        @input="setGanonItem"
                        :options="optionsGanonItem"
                      >{{ $t('randomizer.ganon_item.title') }}</Select>
                    </div>
                  </div>
                </div>
                <h5 class="card-title p-2 border-bottom">{{ $t('randomizer.gameplay.title') }}</h5>
                <div class="card-body" v-if="!editable[world_id]">
                  <div class="row">
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.world_state.title') }}: {{ $t(worlds[world_id].world_state.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.entrance_shuffle.title') }}: {{ $t(worlds[world_id].entrance_shuffle.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.boss_shuffle.title') }}: {{ $t(worlds[world_id].boss_shuffle.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.enemy_shuffle.title') }}: {{ $t(worlds[world_id].enemy_shuffle.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.hints.title') }}: {{ $t(worlds[world_id].hints.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.door_shuffle.title') }}: {{ $t(worlds[world_id].door_shuffle.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1" v-if="worlds[world_id].door_shuffle.value != 'vanilla'"
                    >{{ $t('randomizer.door_intensity.title') }}: {{ $t(worlds[world_id].door_intensity.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.ow_shuffle.title') }}: {{ $t(worlds[world_id].ow_shuffle.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.ow_crossed.title') }}: {{ $t(worlds[world_id].ow_crossed.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1" v-if="worlds[world_id].ow_shuffle.value != 'vanilla' || worlds[world_id].ow_crossed.value == 'limited' || worlds[world_id].ow_crossed.value == 'chaos'"
                    >{{ $t('randomizer.ow_keep_similar.title') }}: {{ $t(worlds[world_id].ow_keep_similar.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.ow_mixed.title') }}: {{ $t(worlds[world_id].ow_mixed.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.ow_flute_shuffle.title') }}: {{ $t(worlds[world_id].ow_flute_shuffle.name) }}</div>
                  </div>
                </div>
                <div class="card-body" v-if="editable[world_id]">
                  <div class="row">
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].world_state"
                        @input="setWorldState"
                        :options="optionsWorldState"
                      >{{ $t('randomizer.world_state.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].entrance_shuffle"
                        @input="setEntranceShuffle"
                        :options="optionsEntranceShuffle"
                      >{{ $t('randomizer.entrance_shuffle.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].boss_shuffle"
                        @input="setBossShuffle"
                        :options="optionsBossShuffle"
                      >{{ $t('randomizer.boss_shuffle.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].enemy_shuffle"
                        @input="setEnemyShuffle"
                        :options="optionsEnemyShuffle"
                      >{{ $t('randomizer.enemy_shuffle.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].hints"
                        @input="setHints"
                        :options="optionsHints"
                      >{{ $t('randomizer.hints.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].door_shuffle"
                        @input="setDoorShuffle"
                        :options="optionsDoorShuffle"
                      >{{ $t('randomizer.door_shuffle.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1" v-if="worlds[world_id].door_shuffle.value != 'vanilla'">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].door_intensity"
                        @input="setDoorIntensity"
                        :options="optionsDoorIntensity"
                      >{{ $t('randomizer.door_intensity.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].ow_shuffle"
                        @input="setOverworldShuffle"
                        :options="optionsOverworldShuffle"
                      >{{ $t('randomizer.ow_shuffle.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].ow_crossed"
                        @input="setOverworldCrossed"
                        :options="optionsOverworldCrossed"
                      >{{ $t('randomizer.ow_crossed.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1" v-if="worlds[world_id].ow_shuffle.value != 'vanilla' || worlds[world_id].ow_crossed.value == 'limited' || worlds[world_id].ow_crossed.value == 'chaos'">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].ow_keep_similar"
                        @input="setOverworldKeepSimilar"
                        :options="optionsOverworldKeepSimilar"
                      >{{ $t('randomizer.ow_keep_similar.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].ow_mixed"
                        @input="setOverworldMixed"
                        :options="optionsOverworldMixed"
                      >{{ $t('randomizer.ow_mixed.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].ow_flute_shuffle"
                        @input="setFluteShuffle"
                        :options="optionsFluteShuffle"
                      >{{ $t('randomizer.ow_flute_shuffle.title') }}</Select>
                    </div>
                  </div>
                </div>
                <h5 class="card-title p-2 border-bottom">{{ $t('randomizer.difficulty.title') }}</h5>
                <div class="card-body">
                  <div class="row" v-if="!editable[world_id]">
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.weapons.title') }}: {{ $t(worlds[world_id].weapons.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.item_pool.title') }}: {{ $t(worlds[world_id].item_pool.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.item_functionality.title') }}: {{ $t(worlds[world_id].item_functionality.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.enemy_damage.title') }}: {{ $t(worlds[world_id].enemy_damage.name) }}</div>
                    <div
                      class="col-xl-4 col-lg-6 my-1"
                    >{{ $t('randomizer.enemy_health.title') }}: {{ $t(worlds[world_id].enemy_health.name) }}</div>
                  </div>
                  <div class="row" v-if="editable[world_id]">
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].weapons"
                        @input="setWeapons"
                        :options="optionsWeapons"
                      >{{ $t('randomizer.weapons.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].item_pool"
                        @input="setItemPool"
                        :options="optionsItemPool"
                      >
                        {{ $t('randomizer.item_pool.title') }}
                        <sup
                          v-if="worlds[world_id].item_pool.value === 'crowd_control'"
                        >*</sup>
                      </Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].item_functionality"
                        @input="setItemFunctionality"
                        :options="optionsItemFunctionality"
                      >{{ $t('randomizer.item_functionality.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].enemy_damage"
                        @input="setEnemyDamage"
                        :options="optionsEnemyDamage"
                      >{{ $t('randomizer.enemy_damage.title') }}</Select>
                    </div>
                    <div class="col-xl-4 col-lg-6 my-1">
                      <Select
                        :sid="world_id"
                        :value="worlds[world_id].enemy_health"
                        @input="setEnemyHealth"
                        :options="optionsEnemyHealth"
                      >{{ $t('randomizer.enemy_health.title') }}</Select>
                    </div>
                  </div>
                  <div
                    v-if="worlds[world_id].item_pool.value === 'crowd_control'"
                    class="logic-warning text-info"
                    v-html="$t('randomizer.item_pool.crowd_control_warning')"
                  />
                </div>
              </div>
            </div>
          </div>
        </tab>
      </tabs>
      <div class="card-footer">
        <div class="row">
          <div class="col-md">
            <div class="btn-group btn-flex" role="group"></div>
          </div>
          <div class="col-md">
            <div class="btn-group btn-flex" role="group">
              <button
                class="btn btn-info w-50 text-center"
                :disabled="generating"
                @click="generateMultiworld"
              >{{ $t('multiworld.generate') }}</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-show="generating" class="center">
      <div class="loading" />
      <h1>{{ $t('randomizer.generate.generating') }}</h1>
      <h5>{{ $t('multiworld.slow_warning') }}</h5>
    </div>

    <div
      id="seed-details"
      class="card border-success"
      v-if="gameLoaded && !generating"
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
import Select from "../components/Select.vue";
import Tab from "../components/VTTab.vue";
import Tabs from "../components/VTTabs.vue";
import axios from "axios";
import { mapState } from "vuex";

export default {
  components: {
    Tab: Tab,
    Tabs: Tabs,
    Select
  },
  data() {
    return {
      worldList: [1, 2],
      selectedWorldId: 1,
      error: false,
      mw_host: false,
      generating: false,
      generationId: null,
      gameLoaded: false,
      multi: null,
      tournament: false,
      spoilers: false
    };
  },
  created() {
    this.$store.dispatch("multiworld/getItemSettings");
  },
  methods: {
    setName(value, worldId) {
      this.$store.commit("multiworld/setName", { value, worldId });
    },
    setPreset(value, worldId) {
      this.$store.dispatch("multiworld/setPreset", { preset: value, worldId });
    },
    setGlitchesRequired(value, worldId) {
      this.$store.commit("multiworld/setGlitchesRequired", { value, worldId });
    },
    setItemPlacement(value, worldId) {
      this.$store.commit("multiworld/setItemPlacement", { value, worldId });
    },
    setDungeonItems(value, worldId) {
      this.$store.commit("multiworld/setDungeonItems", { value, worldId });
    },
    setDropShuffle(value, worldId) {
      this.$store.commit("multiworld/setDropShuffle", { value, worldId });
    },
    setAccessibility(value, worldId) {
      this.$store.commit("multiworld/setAccessibility", { value, worldId });
    },
    setGoal(value, worldId) {
      this.$store.dispatch("multiworld/setGoal", { value, worldId });
    },
    setGanonOpen(value, worldId) {
      this.$store.dispatch("multiworld/setGanonOpen", { value, worldId });
    },
    setTowerOpen(value, worldId) {
      this.$store.commit("multiworld/setTowerOpen", { value, worldId });
    },
    setGanonItem(value, worldId) {
      this.$store.commit("multiworld/setGanonItem", { value, worldId });
    },
    setWorldState(value, worldId) {
      this.$store.commit("multiworld/setWorldState", { value, worldId });
    },
    setEntranceShuffle(value, worldId) {
      this.$store.commit("multiworld/setEntranceShuffle", { value, worldId });
    },
    setBossShuffle(value, worldId) {
      this.$store.commit("multiworld/setBossShuffle", { value, worldId });
    },
    setEnemyShuffle(value, worldId) {
      this.$store.commit("multiworld/setEnemyShuffle", { value, worldId });
    },
    setHints(value, worldId) {
      this.$store.commit("multiworld/setHints", { value, worldId });
    },
    setDoorShuffle(value, worldId) {
      this.$store.commit("multiworld/setDoorShuffle", { value, worldId });
    },
    setDoorIntensity(value, worldId) {
      this.$store.commit("multiworld/setDoorIntensity", { value, worldId });
    },
    setOverworldShuffle(value, worldId) {
      this.$store.commit("multiworld/setOverworldShuffle", { value, worldId });
    },
    setOverworldCrossed(value, worldId) {
      this.$store.commit("multiworld/setOverworldCrossed", { value, worldId });
    },
    setOverworldKeepSimilar(value, worldId) {
      this.$store.commit("multiworld/setOverworldKeepSimilar", { value, worldId });
    },
    setOverworldMixed(value, worldId) {
      this.$store.commit("multiworld/setOverworldMixed", { value, worldId });
    },
    setFluteShuffle(value, worldId) {
      this.$store.commit("multiworld/setFluteShuffle", { value, worldId });
    },
    setShopsanity(value, worldId) {
      this.$store.commit("multiworld/setShopsanity", { value, worldId });
    },
    setItemPool(value, worldId) {
      this.$store.commit("multiworld/setItemPool", { value, worldId });
    },
    setWeapons(value, worldId) {
      this.$store.commit("multiworld/setWeapons", { value, worldId });
    },
    setItemFunctionality(value, worldId) {
      this.$store.commit("multiworld/setItemFunctionality", { value, worldId });
    },
    setEnemyDamage(value, worldId) {
      this.$store.commit("multiworld/setEnemyDamage", { value, worldId });
    },
    setEnemyHealth(value, worldId) {
      this.$store.commit("multiworld/setEnemyHealth", { value, worldId });
    },
    generateMultiworld() {
      this.error = false;
      this.generating = true;
      return new Promise((resolve, reject) => {
        this.gameLoaded = false;
        // convert page to a useful payload
        const payload = Object.keys(this.worlds).reduce((result, key) => {
          // filter out unused worlds
          if (this.worldList.indexOf(Number(key)) === -1) {
            return result;
          }
          const part = { ...this.worlds[key] };
          result[key] = Object.keys(part).reduce((result, key) => {
            if (typeof part[key] === "string") {
              result[key] = part[key];
            } else {
              result[key] = part[key].value;
            }
            return result;
          }, {});
          return result;
        }, {});
        axios
          .post(
            `/api/multiworld`,
            {
              worlds: { ...payload },
              async: true,
              tournament: false,
              lang: document.documentElement.lang
            },
            {
              responseType: "json"
            }
          )
          .then(response => {
            if (response.data.multiworld_generation_id) {
              this.generationId = response.data.multiworld_generation_id;
              setTimeout(this.checkGeneration.bind(this), 1000);
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
      });
    },
    checkGeneration() {
      axios
        .get(
          `/api/generation/multiworld/${this.generationId}`,
          {
            responseType: "json"
          }
        )
        .then(response => {
          if (response.data.status === "waiting") {
            setTimeout(this.checkGeneration.bind(this), 5000);
          } else if (response.data.multiworld_hash) {
            axios.get(`/multi/` + response.data.multiworld_hash).then(response => {
              this.multi = new Multiworld(response.data);
              this.generating = false;
              this.generationId = null;
              this.gameLoaded = true;
            });
          } else {
            this.error = this.$i18n.t("error.failed_generation");
            this.generating = false;
            this.generationId = null;
          }
        });
    },
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
          `/api/mw/host/${this.multi.hash}`,
          {
            responseType: "json"
          }
        )
        .then(response => {
          if (response.data.port && response.data.token) {
            this.mw_host = `Your game is hosted at ws://mw.gwaa.kiwi:${response.data.port} with room token ${response.data.token}`;
            this.error = false;
          } else {
            this.mw_host = false;
            this.error = this.$i18n.t("error.failed_host");
          }
        })
        .catch(error => {
          this.mw_host = false;
          this.error = this.$i18n.t("error.failed_host");
        });
    },
    onError(error) {
      this.error = error;
    },
    onActionClick(action) {
      if (action.name === "+ Add World") {
        const newWorldId = this.worldList.length + 1;
        this.worldList.push(newWorldId);
        this.selectedWorldId = newWorldId;
      }
    }
  },
  computed: {
    ...mapState("multiworld", {
      worlds: state => state.worlds,
      optionsPreset: state => state.options.preset,
      optionsGlitchesRequired: state => state.options.glitches_required,
      optionsItemPlacement: state => state.options.item_placement,
      optionsDungeonItems: state => state.options.dungeon_items,
      optionsDropShuffle: state => state.options.drop_shuffle,
      optionsAccessibility: state => state.options.accessibility,
      optionsGoal: state => state.options.goal,
      optionsTowerOpen: state => state.options.tower_open,
      optionsGanonOpen: state => state.options.ganon_open,
      optionsGanonItem: state => state.options.ganon_item,
      optionsWorldState: state => state.options.world_state,
      optionsBossShuffle: state => state.options.boss_shuffle,
      optionsEnemyShuffle: state => state.options.enemy_shuffle,
      optionsEntranceShuffle: state => state.options.entrance_shuffle,
      optionsDoorShuffle: state => state.options.door_shuffle,
      optionsDoorIntensity: state => state.options.door_intensity,
      optionsOverworldShuffle: state => state.options.ow_shuffle,
      optionsOverworldCrossed: state => state.options.ow_crossed,
      optionsOverworldKeepSimilar: state => state.options.ow_keep_similar,
      optionsOverworldMixed: state => state.options.ow_mixed,
      optionsFluteShuffle: state => state.options.ow_flute_shuffle,
      optionsShopsanity: state => state.options.shopsanity,
      optionsHints: state => state.options.hints,
      optionsWeapons: state => state.options.weapons,
      optionsItemPool: state => state.options.item_pool,
      optionsItemFunctionality: state => state.options.item_functionality,
      optionsEnemyDamage: state => state.options.enemy_damage,
      optionsEnemyHealth: state => state.options.enemy_health,
    }),
    editable() {
      return {
        1: this.worlds[1].preset.value === "custom",
        2: this.worlds[2].preset.value === "custom",
        3: this.worlds[3].preset.value === "custom",
        4: this.worlds[4].preset.value === "custom",
        5: this.worlds[5].preset.value === "custom",
        6: this.worlds[6].preset.value === "custom",
        7: this.worlds[7].preset.value === "custom",
        8: this.worlds[8].preset.value === "custom"
      };
    }
  }
};
</script>

<style scoped>
.card-body {
  padding: 0.5rem;
}
</style>
