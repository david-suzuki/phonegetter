<template>
  <!--begin::Dashboard-->
  <div class="row gy-5 g-xl-8">
    <div class="col-xxl-4">
      <MixedWidget1></MixedWidget1>
    </div>
    <div class="col-xxl-4">
      <StatsWidget7></StatsWidget7>
    </div>
  </div>
  <!--end::Dashboard-->
</template>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import MixedWidget1 from "@/components/widgets/mixed/Widget1.vue";
import StatsWidget7 from "@/components/widgets/stats/Widget7.vue";
import { setCurrentPageTitle } from "@/core/helpers/breadcrumb";

export default defineComponent({
  name: "dashboard",
  components: {
    MixedWidget1,
    StatsWidget7
  },
  setup() {
    onMounted(() => {
      setCurrentPageTitle("dashboard");
    });
  }
});
</script>
