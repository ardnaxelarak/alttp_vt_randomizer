<template>
  <div>
    <div
      v-if="rom.spoiler.meta.spoilers_ongen==true || rom.spoilers=='generate'"
      class="spoiler-warning"
    >{{ $t('rom.info.spoilerwarning') }}</div>
    <div v-if="rom.spoilers=='mystery'" class="mystery" >{{ $t('rom.info.mystery') }}</div>
    <div v-if="rom.logic">{{ $t('rom.info.logic') }}: {{ rom.logic }}</div>
    <div v-if="rom.build">{{ $t('rom.info.build') }}: {{ rom.build }}</div>
    <div v-if="rom.difficulty">
      {{ $t('rom.info.difficulty') }}: {{ rom.difficulty }}
      <span
        v-if="rom.difficulty_mode && rom.difficulty_mode.toUpperCase() != rom.difficulty.toUpperCase()"
      >({{ rom.difficulty_mode }})</span>
    </div>
    <div
      v-if="rom.accessibility"
    >{{ $t('rom.info.accessibility') }}: {{ $t(`randomizer.accessibility.options.${rom.accessibility}`) }}</div>
    <div v-if="rom.variation">{{ $t('rom.info.variation') }}: {{ rom.variation }}</div>
    <div v-if="rom.shuffle">{{ $t('rom.info.shuffle') }}: {{ $t(`randomizer.entrance_shuffle.options.${rom.shuffle}`) }}</div>
    <div v-if="rom.door_shuffle">{{ $t('rom.info.door_shuffle') }}: {{ $t(`randomizer.door_shuffle.options.${rom.door_shuffle}`) }}</div>
    <div v-if="rom.ow_shuffle">{{ $t('rom.info.ow_shuffle') }}: {{ $t(`randomizer.ow_shuffle.options.${rom.ow_shuffle}`) }}</div>
    <div v-if="rom.ow_crossed">{{ $t('rom.info.ow_crossed') }}: {{ $t(`randomizer.ow_crossed.options.${rom.ow_crossed}`) }}</div>
    <div v-if="rom.ow_mixed">{{ $t('rom.info.ow_mixed') }}: {{ $t(`randomizer.ow_mixed.options.${rom.ow_mixed}`) }}</div>
    <div
      v-if="rom.mode"
    >{{ $t('rom.info.mode') }}: {{ $t(`randomizer.world_state.options.${rom.mode}`) }}</div>
    <div
      v-if="rom.weapons"
    >{{ $t('rom.info.weapons') }}: {{ $t(`randomizer.weapons.options.${rom.weapons}`) }}</div>
    <div v-if="rom.goal">{{ $t('rom.info.goal') }}: {{ $t(`randomizer.goal.options.${rom.goal}`) }}</div>
    <div v-if="rom.hash">
      {{ $t('rom.info.permalink') }}:
      <a :href="permalink">{{ permalink }}</a>
    </div>
    <div v-if="rom.special">{{ $t('rom.info.special') }}: {{ rom.special }}</div>
    <div v-if="rom.notes">
      {{ $t('rom.info.notes') }}:
      <span v-html="rom.notes"></span>
    </div>
    <div v-if="rom.generated">
      {{ $t('rom.info.generated') }}:
      <timeago :datetime="rom.generated" :auto-update="60" :locale="$i18n.locale"></timeago>
    </div>
  </div>
</template>

<script>
export default {
  props: ["rom"],
  computed: {
    permalink: vm => {
      return (
        window.location.origin +
        "/" +
        (document.documentElement.lang || "en") +
        "/h/" +
        vm.rom.hash
      );
    }
  }
};
</script>

<style scoped>
.spoiler-warning {
  color: red;
  font-weight: bold;
}
.mystery {
  font-weight: bold;
}
</style>
