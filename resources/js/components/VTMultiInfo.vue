<template>
  <div>
    <div
      v-if="multi.spoiler.meta.spoilers_ongen==true || multi.spoilers=='generate'"
      class="spoiler-warning"
    >{{ $t('multiworld.info.spoilerwarning') }}</div>
    <div v-if="multi.notes">
      {{ $t('multiworld.info.notes') }}:
      <span v-html="multi.notes"></span>
    </div>
    <div v-if="multi.generated">
      {{ $t('multiworld.info.generated') }}:
      <timeago :datetime="multi.generated" :auto-update="60" :locale="$i18n.locale"></timeago>
    </div>
    <div v-for="world in worlds">
      {{ world.name || "Unnamed World" }}:
      <a :href="world.permalink">{{ world.permalink }}</a>
    </div>
    <div>
      {{ $t('multiworld.info.permalink') }}:
      <a :href="permalink">{{ permalink }}</a>
    </div>
  </div>
</template>

<script>
export default {
  props: ["multi"],
  computed: {
    permalink: vm => {
      return (
        window.location.origin +
        "/" +
        (document.documentElement.lang || "en") +
        "/m/" +
        vm.multi.hash
      );
    },
    worlds: vm => {
      return vm.multi.worlds.map(function(world) {
        return {
          name: world.name,
          permalink: window.location.origin + "/" + (document.documentElement.lang || "en") + "/h/" + world.hash,
        };
      });
    },
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
