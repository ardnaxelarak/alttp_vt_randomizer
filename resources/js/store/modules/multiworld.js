import localforage from "localforage";
import axios from "axios";
import Defaults from "./defaults";

function hasValue(value, array) {
  return (
    array.filter(v => {
      return value.value === v.value;
    }).length > 0
  );
}

function asMulti(object, mKey) {
  return Object.keys(object).map(key => {
    return {
      value: key,
      name: `randomizer.${mKey}.options.${key}`
    };
  });
}

export default {
  namespaced: true,
  state: {
    worlds: {
      1: JSON.parse(JSON.stringify(Defaults)),
      2: JSON.parse(JSON.stringify(Defaults)),
      3: JSON.parse(JSON.stringify(Defaults)),
      4: JSON.parse(JSON.stringify(Defaults)),
      5: JSON.parse(JSON.stringify(Defaults)),
      6: JSON.parse(JSON.stringify(Defaults)),
      7: JSON.parse(JSON.stringify(Defaults)),
      8: JSON.parse(JSON.stringify(Defaults))
    },
    options: {},
    preset_map: {},
    initializing: true
  },
  getters: {},
  actions: {
    getItemSettings({ commit, dispatch }) {
      return axios
        .get(`/randomizer/settings`)
        .then(response => {
          commit("updateItemSettings", response.data);
        })
        .then(() => {
          let loads = [];
          for (let worldId = 0; worldId < 8; worldId++) {
            loads = loads.concat([
              dispatch("load", [worldId, "name", "setName"]),
              dispatch("load", [worldId, "preset", "setPreset"]),
              dispatch("load", [worldId, "glitches_required", "setGlitchesRequired"]),
              dispatch("load", [worldId, "item_placement", "setItemPlacement"]),
              dispatch("load", [worldId, "dungeon_items", "setDungeonItems"]),
              dispatch("load", [worldId, "drop_shuffle", "setDropShuffle"]),
              dispatch("load", [worldId, "bonk_shuffle", "setBonkShuffle"]),
              dispatch("load", [worldId, "pottery_shuffle", "setPotteryShuffle"]),
              dispatch("load", [worldId, "accessibility", "setAccessibility"]),
              dispatch("load", [worldId, "goal", "setGoal"]),
              dispatch("load", [worldId, "tower_open", "setTowerOpen"]),
              dispatch("load", [worldId, "ganon_open", "setGanonOpen"]),
              dispatch("load", [worldId, "ganon_item", "setGanonItem"]),
              dispatch("load", [worldId, "world_state", "setWorldState"]),
              dispatch("load", [worldId, "entrance_shuffle", "setEntranceShuffle"]),
              dispatch("load", [worldId, "door_shuffle", "setDoorShuffle"]),
              dispatch("load", [worldId, "door_intensity", "setDoorIntensity"]),
              dispatch("load", [worldId, "ow_shuffle", "setOverworldShuffle"]),
              dispatch("load", [worldId, "ow_crossed", "setOverworldCrossed"]),
              dispatch("load", [worldId, "ow_keep_similar", "setOverworldKeepSimilar"]),
              dispatch("load", [worldId, "ow_mixed", "setOverworldMixed"]),
              dispatch("load", [worldId, "ow_flute_shuffle", "setFluteShuffle"]),
              dispatch("load", [worldId, "shopsanity", "setShopsanity"]),
              dispatch("load", [worldId, "boss_shuffle", "setBossShuffle"]),
              dispatch("load", [worldId, "enemy_shuffle", "setEnemyShuffle"]),
              dispatch("load", [worldId, "hints", "setHints"]),
              dispatch("load", [worldId, "weapons", "setWeapons"]),
              dispatch("load", [worldId, "item_pool", "setItemPool"]),
              dispatch("load", [worldId, "item_functionality", "setItemFunctionality"]),
              dispatch("load", [worldId, "enemy_damage", "setEnemyDamage"]),
              dispatch("load", [worldId, "enemy_health", "setEnemyHealth"]),
              dispatch("load", [worldId, "spoiler", "setSpoiler"])
            ]);
          }

          return Promise.all(loads);
        })
        .then(() => {
          commit("setInitalizing", false);
        });
    },
    setPreset({ commit, state }, { worldId, preset }) {
      if (
        preset.value !== "custom" &&
        typeof state.preset_map[preset.value] !== "undefined"
      ) {
        commit("setGlitchesRequired", {
          worldId,
          value: state.preset_map[preset.value]["glitches_required"]
        });
        commit("setItemPlacement", {
          worldId,
          value: state.preset_map[preset.value]["item_placement"]
        });
        commit("setDungeonItems", {
          worldId,
          value: state.preset_map[preset.value]["dungeon_items"]
        });
        commit("setDropShuffle", {
          worldId,
          value: state.preset_map[preset.value]["drop_shuffle"]
        });
        commit("setBonkShuffle", {
          worldId,
          value: state.preset_map[preset.value]["bonk_shuffle"]
        });
        commit("setPotteryShuffle", {
          worldId,
          value: state.preset_map[preset.value]["pottery_shuffle"]
        });
        commit("setAccessibility", {
          worldId,
          value: state.preset_map[preset.value]["accessibility"]
        });
        commit("setGoal", {
          worldId,
          value: state.preset_map[preset.value]["goal"]
        });
        commit("setTowerOpen", {
          worldId,
          value: state.preset_map[preset.value]["tower_open"]
        });
        commit("setGanonOpen", {
          worldId,
          value: state.preset_map[preset.value]["ganon_open"]
        });
        commit("setGanonItem", {
          worldId,
          value: state.preset_map[preset.value]["ganon_item"]
        });
        commit("setWorldState", {
          worldId,
          value: state.preset_map[preset.value]["world_state"]
        });
        commit("setEntranceShuffle", {
          worldId,
          value: state.preset_map[preset.value]["entrance_shuffle"]
        });
        commit("setDoorShuffle", {
          worldId,
          value: state.preset_map[preset.value]["door_shuffle"]
        });
        commit("setDoorIntensity", {
          worldId,
          value: state.preset_map[preset.value]["door_intensity"]
        });
        commit("setOverworldShuffle", {
          worldId,
          value: state.preset_map[preset.value]["ow_shuffle"]
        });
        commit("setOverworldCrossed", {
          worldId,
          value: state.preset_map[preset.value]["ow_crossed"]
        });
        commit("setOverworldKeepSimilar", {
          worldId,
          value: state.preset_map[preset.value]["ow_keep_similar"]
        });
        commit("setOverworldMixed", {
          worldId,
          value: state.preset_map[preset.value]["ow_mixed"]
        });
        commit("setFluteShuffle", {
          worldId,
          value: state.preset_map[preset.value]["ow_flute_shuffle"]
        });
        commit("setShopsanity", {
          worldId,
          value: state.preset_map[preset.value]["shopsanity"]
        });
        commit("setBossShuffle", {
          worldId,
          value: state.preset_map[preset.value]["boss_shuffle"]
        });
        commit("setEnemyShuffle", {
          worldId,
          value: state.preset_map[preset.value]["enemy_shuffle"]
        });
        commit("setHints", {
          worldId,
          value: state.preset_map[preset.value]["hints"]
        });
        commit("setWeapons", {
          worldId,
          value: state.preset_map[preset.value]["weapons"]
        });
        commit("setItemPool", {
          worldId,
          value: state.preset_map[preset.value]["item_pool"]
        });
        commit("setItemFunctionality", {
          worldId,
          value: state.preset_map[preset.value]["item_functionality"]
        });
        commit("setEnemyDamage", {
          worldId,
          value: state.preset_map[preset.value]["enemy_damage"]
        });
        commit("setEnemyHealth", {
          worldId,
          value: state.preset_map[preset.value]["enemy_health"]
        });
      }

      commit("setPreset", { worldId, value: preset });
    },
    async load({ commit, state }, [worldId, key, mutate]) {
      const value = await localforage.getItem(`multiworld.${worldId}.${key}`);
      if (key !== "name" && value !== null && hasValue(value, state.options[key])) {
        commit(mutate, { worldId, value });
      }
    },
    setGoal({ commit, state }, { worldId, value }) {
      commit("setGoal", { worldId, value });

      if (state.worlds[worldId].goal.value === "dungeons") {
        commit("setGanonOpen", { worldId, value: "7" });
      }
    },
    setGanonOpen({ commit, state }, { worldId, value }) {
      commit("setGanonOpen", { worldId, value });

      if (
        state.worlds[worldId].ganon_open.value !== "7" &&
        state.worlds[worldId].goal.value === "dungeons"
      ) {
        commit("setGoal", { worldId, value: "ganon" });
      }
    },
  },
  mutations: {
    updateItemSettings(
      state,
      {
        presets,
        glitches_required,
        item_placement,
        dungeon_items,
        drop_shuffle,
        bonk_shuffle,
        pottery_shuffle,
        accessibility,
        goals,
        tower_open,
        ganon_open,
        ganon_item,
        world_state,
        entrance_shuffle,
        door_shuffle,
        door_intensity,
        ow_shuffle,
        ow_crossed,
        ow_keep_similar,
        ow_mixed,
        ow_flute_shuffle,
        shopsanity,
        boss_shuffle,
        enemy_shuffle,
        hints,
        weapons,
        item_pool,
        item_functionality,
        enemy_damage,
        enemy_health
      }
    ) {
      state.options.preset = asMulti(presets, "preset");
      state.options.glitches_required = asMulti(glitches_required, "glitches_required");
      state.options.item_placement = asMulti(item_placement, "item_placement");
      state.options.dungeon_items = asMulti(dungeon_items, "dungeon_items");
      state.options.drop_shuffle = asMulti(drop_shuffle, "drop_shuffle");
      state.options.bonk_shuffle = asMulti(bonk_shuffle, "bonk_shuffle");
      state.options.pottery_shuffle = asMulti(pottery_shuffle, "pottery_shuffle");
      state.options.accessibility = asMulti(accessibility, "accessibility");
      state.options.goal = asMulti(goals, "goal");
      state.options.tower_open = asMulti(tower_open, "tower_open");
      state.options.ganon_open = asMulti(ganon_open, "ganon_open");
      state.options.ganon_item = asMulti(ganon_item, "ganon_item");
      state.options.world_state = asMulti(world_state, "world_state");
      state.options.entrance_shuffle = asMulti(entrance_shuffle, "entrance_shuffle");
      state.options.door_shuffle = asMulti(door_shuffle, "door_shuffle");
      state.options.door_intensity = asMulti(door_intensity, "door_intensity");
      state.options.ow_shuffle = asMulti(ow_shuffle, "ow_shuffle");
      state.options.ow_crossed = asMulti(ow_crossed, "ow_crossed");
      state.options.ow_keep_similar = asMulti(ow_keep_similar, "ow_keep_similar");
      state.options.ow_mixed = asMulti(ow_mixed, "ow_mixed");
      state.options.ow_flute_shuffle = asMulti(ow_flute_shuffle, "ow_flute_shuffle");
      state.options.shopsanity = asMulti(shopsanity, "shopsanity");
      state.options.boss_shuffle = asMulti(boss_shuffle, "boss_shuffle");
      state.options.enemy_shuffle = asMulti(enemy_shuffle, "enemy_shuffle");
      state.options.hints = asMulti(hints, "hints");
      state.options.weapons = asMulti(weapons, "weapons");
      state.options.item_pool = asMulti(item_pool, "item_pool");
      state.options.item_functionality = asMulti(item_functionality, "item_functionality");
      state.options.enemy_damage = asMulti(enemy_damage, "enemy_damage");
      state.options.enemy_health = asMulti(enemy_health, "enemy_health");
      state.preset_map = presets;
    },
    setName(state, { worldId, value }) {
      state.worlds[worldId].name = value;
      localforage.setItem(`multiworld.${worldId}.name`, value);
    },
    setPreset(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.preset.find(o => o.value === value);
      }
      state.worlds[worldId].preset = value;
      localforage.setItem(`multiworld.${worldId}.preset`, value);
    },
    setGlitchesRequired(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.glitches_required.find(o => o.value === value);
      }
      state.worlds[worldId].glitches_required = value;
      localforage.setItem(`multiworld.${worldId}.glitches_required`, value);
    },
    setItemPlacement(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.item_placement.find(o => o.value === value);
      }
      state.worlds[worldId].item_placement = value;
      localforage.setItem(`multiworld.${worldId}.item_placement`, value);
    },
    setDungeonItems(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.dungeon_items.find(o => o.value === value);
      }
      state.worlds[worldId].dungeon_items = value;
      localforage.setItem(`multiworld.${worldId}.dungeon_items`, value);
    },
    setDropShuffle(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.drop_shuffle.find(o => o.value === value);
      }
      state.worlds[worldId].drop_shuffle = value;
      localforage.setItem(`multiworld.${worldId}.drop_shuffle`, value);
    },
    setBonkShuffle(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.bonk_shuffle.find(o => o.value === value);
      }
      state.worlds[worldId].bonk_shuffle = value;
      localforage.setItem(`multiworld.${worldId}.bonk_shuffle`, value);
    },
    setPotteryShuffle(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.pottery_shuffle.find(o => o.value === value);
      }
      state.worlds[worldId].pottery_shuffle = value;
      localforage.setItem(`multiworld.${worldId}.pottery_shuffle`, value);
    },
    setAccessibility(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.accessibility.find(o => o.value === value);
      }
      state.worlds[worldId].accessibility = value;
      localforage.setItem(`multiworld.${worldId}.accessibility`, value);
    },
    setGoal(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.goal.find(o => o.value === value);
      }
      state.worlds[worldId].goal = value;
      localforage.setItem(`multiworld.${worldId}.goal`, value);
    },
    setTowerOpen(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.tower_open.find(o => o.value === value);
      }
      state.worlds[worldId].tower_open = value;
      localforage.setItem(`multiworld.${worldId}.tower_open`, value);
    },
    setGanonOpen(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.ganon_open.find(o => o.value === value);
      }
      state.worlds[worldId].ganon_open = value;
      localforage.setItem(`multiworld.${worldId}.ganon_open`, value);
    },
    setGanonItem(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.ganon_item.find(o => o.value === value);
      }
      state.worlds[worldId].ganon_item = value;
      localforage.setItem(`multiworld.${worldId}.ganon_item`, value);
    },
    setWorldState(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.world_state.find(o => o.value === value);
      }
      state.worlds[worldId].world_state = value;
      localforage.setItem(`multiworld.${worldId}.world_state`, value);
    },
    setEntranceShuffle(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.entrance_shuffle.find(o => o.value === value);
      }
      state.worlds[worldId].entrance_shuffle = value;
      localforage.setItem(`multiworld.${worldId}.entrance_shuffle`, value);
    },
    setDoorShuffle(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.door_shuffle.find(o => o.value === value);
      }
      state.worlds[worldId].door_shuffle = value;
      localforage.setItem(`multiworld.${worldId}.door_shuffle`, value);
    },
    setDoorIntensity(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.door_intensity.find(o => o.value === value);
      }
      state.worlds[worldId].door_intensity = value;
      localforage.setItem(`multiworld.${worldId}.door_intensity`, value);
    },
    setOverworldShuffle(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.ow_shuffle.find(o => o.value === value);
      }
      state.worlds[worldId].ow_shuffle = value;
      localforage.setItem(`multiworld.${worldId}.ow_shuffle`, value);
    },
    setOverworldCrossed(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.ow_crossed.find(o => o.value === value);
      }
      state.worlds[worldId].ow_crossed = value;
      localforage.setItem(`multiworld.${worldId}.ow_crossed`, value);
    },
    setOverworldKeepSimilar(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.ow_keep_similar.find(o => o.value === value);
      }
      state.worlds[worldId].ow_keep_similar = value;
      localforage.setItem(`multiworld.${worldId}.ow_keep_similar`, value);
    },
    setOverworldMixed(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.ow_mixed.find(o => o.value === value);
      }
      state.worlds[worldId].ow_mixed = value;
      localforage.setItem(`multiworld.${worldId}.ow_mixed`, value);
    },
    setFluteShuffle(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.ow_flute_shuffle.find(o => o.value === value);
      }
      state.worlds[worldId].ow_flute_shuffle = value;
      localforage.setItem(`multiworld.${worldId}.ow_flute_shuffle`, value);
    },
    setShopsanity(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.shopsanity.find(o => o.value === value);
      }
      state.worlds[worldId].shopsanity = value;
      localforage.setItem(`multiworld.${worldId}.shopsanity`, value);
    },
    setBossShuffle(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.boss_shuffle.find(o => o.value === value);
      }
      state.worlds[worldId].boss_shuffle = value;
      localforage.setItem(`multiworld.${worldId}.boss_shuffle`, value);
    },
    setEnemyShuffle(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.enemy_shuffle.find(o => o.value === value);
      }
      state.worlds[worldId].enemy_shuffle = value;
      localforage.setItem(`multiworld.${worldId}.enemy_shuffle`, value);
    },
    setHints(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.hints.find(o => o.value === value);
      }
      state.worlds[worldId].hints = value;
      localforage.setItem(`multiworld.${worldId}.hints`, value);
    },
    setWeapons(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.weapons.find(o => o.value === value);
      }
      state.worlds[worldId].weapons = value;
      localforage.setItem(`multiworld.${worldId}.weapons`, value);
    },
    setItemPool(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.item_pool.find(o => o.value === value);
      }
      state.worlds[worldId].item_pool = value;
      localforage.setItem(`multiworld.${worldId}.item_pool`, value);
    },
    setItemFunctionality(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.item_functionality.find(o => o.value === value);
      }
      state.worlds[worldId].item_functionality = value;
      localforage.setItem(`multiworld.${worldId}.item_functionality`, value);
    },
    setEnemyDamage(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.enemy_damage.find(o => o.value === value);
      }
      state.worlds[worldId].enemy_damage = value;
      localforage.setItem(`multiworld.${worldId}.enemy_damage`, value);
    },
    setEnemyHealth(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.enemy_health.find(o => o.value === value);
      }
      state.worlds[worldId].enemy_health = value;
      localforage.setItem(`multiworld.${worldId}.enemy_health`, value);
    },
    setSpoiler(state, { worldId, value }) {
      if (typeof value === "string") {
        value = state.options.spoiler.find(o => o.value === value);
      }
      state.worlds[worldId].spoiler = value;
      localforage.setItem(`multiworld.${worldId}.spoiler`, value);
    },
    setInitalizing(state, init) {
      state.initializing = init;
    }
  }
};
